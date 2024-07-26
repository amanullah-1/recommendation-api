<?php

namespace App\Controller;

use App\Entity\Product;
use App\Factory\JsonResponseFactory;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/v1/product')]
class ProductController extends AbstractController
{
    private array $allowedProperties;
    private array $requiredFields;
    private $entityManager;
    private $productRepository;
    private $userRepository;
    private $purchaseRepository;


    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        PurchaseRepository $purchaseRepository,
        private JsonResponseFactory $jsonResponseFactory, 
        private ApiService $apiService
    )
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->purchaseRepository = $purchaseRepository;

        $this->allowedProperties = [
            'find' => [
                'query' => ['page', 'limit'],
                'body' => []
            ],
            'create' => [
                'query' => [],
                'body' => [ "name", "description", "category", "price", "size", "color", "brand" ],
            ],
            'get' => [
                'query' => [],
                'body' => []
            ],
            'edit' => [
                'query' => [],
                'body' => [ "name", "description", "category", "price", "size", "color", "brand" ],
            ],
            'delete' => [
                'query' => [],
                'body' => []
            ]
        ];
        $this->requiredFields = [
            'find' => [
                'query' => [],
                'body' => []
            ],
            'create' => [
                'query' => [],
                'body' => [ "name", "description", "category", "price" ],
            ],
            'get' => [
                'query' => [],
                'body' => []
            ],
            'edit' => [
                'query' => [],
                'body' => [],
                'files' => []
            ],
            'delete' => [
                'query' => [],
                'body' => []
            ]
        ];
    }

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page,  #[MapQueryParameter] int $limit, ProductRepository $productRepository, Request $request): Response
    {

        $requestValidation = $this->apiService->hasValidBodyAndQueryParameters(
            $request,
            $this->allowedProperties['find']['body'],
            $this->requiredFields['find']['body'],
            $this->allowedProperties['find']['query'],
            $this->requiredFields['find']['query'],
        );


        if (!$requestValidation['yes']) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'description' => 'The request has invalid query parameters or body fields.',
                    'code' => Response::HTTP_BAD_REQUEST,
                    'body' => $requestValidation['body'],
                    'params' => $requestValidation['params']
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }


        try {
            $products = $productRepository->findBy([], [], $limit, ($page - 1) * $limit);
            return $this->jsonResponseFactory->create(
                (object) [
                    'message' => Response::$statusTexts[Response::HTTP_OK],
                    'code' => Response::HTTP_OK,
                    'datas' => $products,
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $productRepository->count([]),
                    'totalPages' => (int) ceil($productRepository->count([]) / $limit),
                    'error' => 'Great, No Error!',
                ],
                Response::HTTP_OK,
            );
        } catch (\Throwable $th) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                    'description' => $th->getMessage(),
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    #[Route('/create', name: 'app_product_create', methods: ['POST'])]
    public function create(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $requestValidation = $this->apiService->hasValidBodyAndQueryParameters(
            $request,
            $this->allowedProperties['create']['body'],
            $this->requiredFields['create']['body'],
            $this->allowedProperties['create']['query'],
            $this->requiredFields['create']['query'],
        );

        if (!$requestValidation['yes']) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'description' => 'The request has invalid query parameters or body fields.',
                    'code' => Response::HTTP_BAD_REQUEST,
                    'body' => $requestValidation['body'],
                    'params' => $requestValidation['params']
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $bodyData = json_decode($request->getContent(), true);

        $currentUser = $this->getUser();
        $product = new Product();
        $product->setName($bodyData['name']);
        $product->setCategory($bodyData['category']);
        $product->setDescription($bodyData['description']);
        $product->setPrice($bodyData['price']);
        $product->setBrand($bodyData['brand']);
        $product->setSize($bodyData['size']);
        $product->setColor($bodyData['color']);



        try {
            $productRepository->save($product, true);
            return $this->jsonResponseFactory->create(
                (object) [
                    
                    'message' => Response::$statusTexts[Response::HTTP_CREATED],
                    'description' => 'The resource has been created.',
                    'code' => Response::HTTP_CREATED,
                    'datas' => $product,
                    'error' => "Great, No Error!",
                ],
                Response::HTTP_CREATED,
            );
        } catch (\Throwable $th) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                    'description' => 'The resource has not been created.',
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'datas' => $product
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    #[Route('/get/{id}', name: 'app_product_get', methods: ['GET'])]
    public function get(Product $product = null, Request $request): Response
    {

        if (!$product) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    'description' => 'The requested resource was not found.',
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND,
            );
        }

        $requestValidation = $this->apiService->hasValidBodyAndQueryParameters(
            $request,
            $this->allowedProperties['get']['body'],
            $this->requiredFields['get']['body'],
            $this->allowedProperties['get']['query'],
            $this->requiredFields['get']['query'],
        );

        if (!$requestValidation['yes']) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'description' => 'The request has invalid query parameters or body fields.',
                    'code' => Response::HTTP_BAD_REQUEST,
                    'body' => $requestValidation['body'],
                    'params' => $requestValidation['params']
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        //Add recommendation
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }


        // Get similar products for recommendations
        $similarProducts = $this->productRepository->findSimilarProducts($product->getId());

        // Get products based on user purchase history
        $historyProducts = $this->productRepository->findProductsByUserPurchaseHistory($user->getId());

        // Combine recommendations
        $recommendations = array_merge($similarProducts, $historyProducts);

        // Ensure unique recommendations
        $recommendations = array_unique($recommendations, SORT_REGULAR);

        return $this->jsonResponseFactory->create(
            (object) [
                'message' => Response::$statusTexts[Response::HTTP_OK],
                'code' => Response::HTTP_OK,
                'datas' => $product,
                'recommendations' => $recommendations,
                'error' => "Great, No Error!",
            ],
            Response::HTTP_OK,
        );
    }

    #[Route('/edit/{id}', name: 'app_product_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Product $product = null, ProductRepository $productRepository): Response
    {
        if (!$product) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    'description' => 'The requested resource was not found.',
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND,
            );
        }

        $requestValidation = $this->apiService->hasValidBodyAndQueryParameters(
            $request,
            $this->allowedProperties['edit']['body'],
            $this->requiredFields['edit']['body'],
            $this->allowedProperties['edit']['query'],
            $this->requiredFields['edit']['query'],
            false
        );

        if (!$requestValidation['yes']) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'description' => 'The request has invalid query parameters or body fields.',
                    'code' => Response::HTTP_BAD_REQUEST,
                    'body' => $requestValidation['body'],
                    'params' => $requestValidation['params']
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $bodyData = json_decode($request->getContent(), true);

        // Ensure at least one field has been provided
        if (count($bodyData) === 0) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'description' => 'At least one field is required to update the resource.',
                    'code' => Response::HTTP_BAD_REQUEST,
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        // Only update the fields that have been changed (not null and present in the request)
        foreach ($bodyData as $key => $value) {
            if ($value !== null) {
                $setter = 'set' . ucfirst($key);
                $product->$setter($value);
            }
        }

        try {
            $productRepository->save($product, true);
            return $this->jsonResponseFactory->create(
                (object) [
                    'message' => Response::$statusTexts[Response::HTTP_OK],
                    'description' => 'The resource has been updated.',
                    'code' => Response::HTTP_OK,
                    'datas' => $product,
                    'error' => "Great, No Error!",
                ],
                Response::HTTP_OK,
            );
        } catch (\Throwable $th) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                    'description' => 'An error occured while updating the resource.',
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }


    #[Route('/delete/{id}', name: 'app_product_delete', methods: ['DELETE'])]
    public function delete(Request $request, Product $product = null, ProductRepository $productRepository): Response
    {

        // Ensure the product exists in the database
        if (!$product) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    'description' => 'The requested resource was not found.',
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND,
            );
        }

        $requestValidation = $this->apiService->hasValidBodyAndQueryParameters(
            $request,
            $this->allowedProperties['delete']['body'],
            $this->requiredFields['delete']['body'],
            $this->allowedProperties['delete']['query'],
            $this->requiredFields['delete']['query'],
        );

        if (!$requestValidation['yes']) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'description' => 'The request has invalid query parameters or body fields.',
                    'code' => Response::HTTP_BAD_REQUEST,
                    'body' => $requestValidation['body'],
                    'params' => $requestValidation['params']
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }


        try {
            $productRepository->remove($product, true);
            return $this->jsonResponseFactory->create(
                (object) [
                    'message' => Response::$statusTexts[Response::HTTP_OK],
                    'description' => 'The resource has been deleted.',
                    'code' => Response::HTTP_OK,
                    'error' => "Great, No Error!",
                ],
                Response::HTTP_OK,
            );
        } catch (\Throwable $th) {
            return $this->jsonResponseFactory->create(
                (object) [
                    'error' => true,
                    'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                    'description' => 'An error occured while deleting the resource.',
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
