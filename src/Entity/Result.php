<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="json")
     *
     * @var int[]
     */
    private array $arr = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $num;

    /**
     * @ORM\Column(type="integer")
     */
    private $index;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="results")
     */
    private User $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArr(): ?array
    {
        return $this->arr;
    }

    public function setArr(array $arr): void
    {
        $this->arr = $arr;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): void
    {
        $this->num = $num;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setIndex(int $index): void
    {
        $this->index = $index;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user): void
    {
        $this->user_id = $user;
    }
}
