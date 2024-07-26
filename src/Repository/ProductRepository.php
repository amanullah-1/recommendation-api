<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findSimilarProducts($productId, $limit = 5)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT p2.*
            FROM product p
            INNER JOIN product p2 ON (p2.category = p.category OR p2.brand = p.brand) AND p2.id != p.id
            WHERE p.id = :productId
            LIMIT :limit
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('productId', $productId);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();

        // Fetch all results as associative arrays
        return $result->fetchAllAssociative();
    }

    public function findProductsByUserPurchaseHistory($userId, $limit = 10)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT DISTINCT p2.*
            FROM product p
            INNER JOIN purchase_item pi ON p.id = pi.product_id
            INNER JOIN purchase pu ON pi.purchase_ref_id = pu.id
            INNER JOIN product p2 ON (p2.category = p.category OR p2.brand = p.brand) AND p2.id != p.id
            WHERE pu.user_id = :userId
            ORDER BY p2.id
            LIMIT :limit
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $userId);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();

        // Fetch all results as associative arrays
        return $result->fetchAllAssociative();
    }

}
