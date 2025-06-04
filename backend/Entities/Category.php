<?php

namespace Entities;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;

#[Entity(table: 'category')]
class Category
{
    #[Column(type: 'primary', name: 'idcat', nullable: false, autoincrement: true)]
    private int $idcat;

    #[Column(type: 'string', name: 'nomcat', nullable: false)]
    private string $nomcat;

    #[Column(type: 'string', name: 'state', nullable: false)]
    private string $state;

    #[Column(type: 'datetime', name: 'fere', nullable: false)]
    private \DateTime $fere;



    public function getFere(): \DateTime
    {
        return $this->fere;
    }
    public function getId(): int
    {
        return $this->idcat;
    }

    public function getName(): string
    {
        return $this->nomcat;
    }

    public function setName(string $name): void
    {
        $this->nomcat = $name;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }


}