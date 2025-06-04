<?php

namespace Entities;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(table: 'users')]
class User
{
    #[Column(type: 'primary', name: 'id', nullable: false, autoincrement: true)]
    private int $id;

    #[HasMany(target: Orders::class)]
    private array $orders = [];

    #[Column(type: 'string' ,name: 'username',  nullable: false , unique: true)]
    private string $username;

    #[Column(type: 'string' , name : 'name', nullable: false)]
    private  string $name;

    #[Column(type: 'string', name: 'email', nullable: false, unique: true)]
    private string $email;

    #[Column(type: 'longText',name: 'password' ,  nullable: false)]
    private string $password;

    #[Column(type: 'string' ,name : 'rol',nullable: false)]
    private string $rol;

    #[Column(type: 'datetime',name: 'created_at', nullable: false )]
    private \DateTime $created_at;

    public function get_id(): int
    {
        return $this->id;
    }
    public function set_username(string $username): void
    {
        $this->username = $username;
    }
    public function get_username(): string
    {
        return $this->username;
    }
    public function set_name(string $name): void
    {
        $this->name = $name;
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function set_email(string $email): void
    {
        $this->email = $email;
    }
    public function get_email(): string
    {
        return $this->email;
    }
    public function set_password(string $password): void
    {
        $this->password = $password;
    }

    public function get_password(): string
    {
        return $this->password;
    }
    public function set_rol(string $rol): void
    {
        $this->rol = $rol;
    }
    public function get_rol(): string
    {
        return $this->rol;
    }
    public function set_created_at(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }
    public function get_created_at(): \DateTime
    {
        return $this->created_at;
    }
    public function getOrders(): array
    {
        return $this->orders;
    }

    public function addOrder(Orders $orders): void
    {
        $this->orders[] = $orders;
        $orders->setUser($this);
    }


}