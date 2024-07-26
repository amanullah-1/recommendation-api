<?php

namespace App\Entity;

use App\Repository\PurchaseItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseItemRepository::class)]
class PurchaseItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Purchase $purchase_ref = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?float $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseRef(): ?Purchase
    {
        return $this->purchase_ref;
    }

    public function setPurchaseRef(?Purchase $purchase_ref): static
    {
        $this->purchase_ref = $purchase_ref;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
