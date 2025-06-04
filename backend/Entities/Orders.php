<?php

namespace Entities;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'orders')]
class Orders
{




    #[BelongsTo(target: User::class)]
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}