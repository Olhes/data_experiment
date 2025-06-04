<?php

namespace Entities;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'products')]
class Product
{
    #[Column(type: 'primary', name: 'id', nullable: false, autoincrement: true)]
    private int $id;

    #[BelongsTo(target: Category::class)]
    private Category $category;

    #[Column(type: 'string', name: 'name', nullable: false)]
    private string $name;

    #[Column(type: 'string', name: 'description', nullable: false)]
    private string $description;

    #[Column(type: 'float', name: 'price', nullable: false)]
    private float $price;

    #[Column(type: 'integer', name: 'stock', nullable: false)]
    private int $stock;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }
    public function getCatergory(): Category
    {
        return $this->category;
    }
    public function setProduct(Category $category): void
    {
        $this->category = $category;
    }

}