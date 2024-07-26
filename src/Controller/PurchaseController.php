<?php 
namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\PurchaseRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    private $entityManager;
    private $purchaseRepository;
    private $productRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, PurchaseRepository $purchaseRepository, ProductRepository $productRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->purchaseRepository = $purchaseRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/purchases", methods="GET")
     */
    public function listPurchases(): JsonResponse
    {
        $purchases = $this->purchaseRepository->findAll();
        $data = [];

        foreach ($purchases as $purchase) {
            $purchaseItems = [];

            foreach ($purchase->getPurchaseItems() as $item) {
                $purchaseItems[] = [
                    'product' => $item->getProduct()->getName(),
                    'quantity' => $item->getQuantity(),
                    //'price' => $item->getPrice()
                ];
            }

            $data[] = [
                'id' => $purchase->getId(),
                'totalAmount' => $purchase->getTotalAmount(),
                'createdAt' => $purchase->getCreatedAt()->format('Y-m-d H:i:s'),
                'items' => $purchaseItems
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/purchases/{id}", methods="GET")
     */
    public function getPurchase($id): JsonResponse
    {
        $purchase = $this->purchaseRepository->find($id);

        if (!$purchase) {
            return new JsonResponse(['error' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }

        $purchaseItems = [];

        foreach ($purchase->getPurchaseItems() as $item) {
            $purchaseItems[] = [
                'product' => $item->getProduct()->getName(),
                'quantity' => $item->getQuantity()
            ];
        }

        $data = [
            'id' => $purchase->getId(),
            'totalAmount' => $purchase->getTotalAmount(),
            'user' => $purchase->getUser()->getFirstName(). " " .$purchase->getUser()->getLastName(),
            'createdAt' => $purchase->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $purchase->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'items' => $purchaseItems
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/purchases", methods="POST")
     */
    public function createPurchase(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['items']) || !is_array($data['items'])) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $purchase = new Purchase();
        $purchase->setCreatedAt(new \DateTime());
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $purchase->setUser($user);

        $totalAmount = 0;

        foreach ($data['items'] as $itemData) {
            if (!isset($itemData['productId']) || !isset($itemData['quantity'])) {
                return new JsonResponse(['error' => 'Invalid item data'], Response::HTTP_BAD_REQUEST);
            }

            $product = $this->productRepository->find($itemData['productId']);
            if (!$product) {
                return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            $quantity = $itemData['quantity'];
            $price = $product->getPrice() * $quantity;
            $totalAmount += $price;

            $purchaseItem = new PurchaseItem();
            $purchaseItem->setProduct($product);
            $purchaseItem->setQuantity($quantity);
            $purchaseItem->setPurchaseRef($purchase);

            $this->entityManager->persist($purchaseItem);
        }

        $purchase->setTotalAmount($totalAmount);
        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Purchase created successfully'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/purchases/{id}", methods="PUT")
     */
    public function updatePurchase(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $purchase = $this->purchaseRepository->find($id);

        if (!$purchase) {
            return new JsonResponse(['error' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }

        // Remove existing purchase items
        foreach ($purchase->getPurchaseItems() as $item) {
            $this->entityManager->remove($item);
        }
        $this->entityManager->flush();

        $totalAmount = 0;

        foreach ($data['items'] as $itemData) {
            if (!isset($itemData['productId']) || !isset($itemData['quantity'])) {
                return new JsonResponse(['error' => 'Invalid item data'], Response::HTTP_BAD_REQUEST);
            }

            $product = $this->productRepository->find($itemData['productId']);
            if (!$product) {
                return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            $quantity = $itemData['quantity'];
            $price = $product->getPrice() * $quantity;
            $totalAmount += $price;

            $purchaseItem = new PurchaseItem();
            $purchaseItem->setProduct($product);
            $purchaseItem->setQuantity($quantity);
            $purchaseItem->setPurchaseRef($purchase);

            $this->entityManager->persist($purchaseItem);
        }

        $purchase->setTotalAmount($totalAmount);
        $purchase->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Purchase updated successfully'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/purchases/{id}", methods="DELETE")
     */
    public function deletePurchase($id): JsonResponse
    {
        $purchase = $this->purchaseRepository->find($id);

        if (!$purchase) {
            return new JsonResponse(['error' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($purchase);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Purchase deleted successfully'], Response::HTTP_OK);
    }
}
