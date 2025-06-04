<?php

namespace Entities;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
#[Entity(table: 'cart')]
class Cart
{

    #[Column(type: 'primary', name: 'idv', nullable: false, autoincrement: true)]
    private int $idv;

    #[BelongsTo(target: User::class)]
    private User $user;

    #[BelongsTo(target: Product::class)]
    private Product $product ;


    #[Column(type: 'integer', name: 'quantity', nullable: false)]
    private int $quantity;

    #[Column(type: 'float', name: 'price', nullable: false)]
    private Float $price;

    public function getId(): int
    {
        return $this->idv;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function getUser(): User
    {
        return $this->user;
    }


    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProductId(Product $product): void
    {
        $this->product = $product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPrice():Float
    {
        return $this->price;
    }
    public function setPrice(Float $price): void
    {
        $this->price = $price;
    }
}