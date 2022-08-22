<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="shipping_fee")
 * @ORM\Entity(repositoryClass="App\Repository\ShippingFeeRepository")
 */
class ShippingFee
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected ?int $id;

    /**
     * @ORM\Column(type="string")
     */
    protected string $fromPostcode;

    /**
     * @ORM\Column(type="string")
     */
    protected string $toPostcode;

    /**
     * @ORM\Column(type="string")
     */
    protected string $fromWeight;

    /**
     * @ORM\Column(type="string")
     */
    protected string $toWeight;

    /**
     * @ORM\Column(type="float")
     */
    protected ?float $cost;

    public function __construct(?float $cost = null)
    {
        $this->cost = $cost;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFromPostcode(): string
    {
        return $this->fromPostcode;
    }

    /**
     * @param string $fromPostcode
     */
    public function setFromPostcode(string $fromPostcode): void
    {
        $this->fromPostcode = $fromPostcode;
    }

    /**
     * @return string
     */
    public function getToPostcode(): string
    {
        return $this->toPostcode;
    }

    /**
     * @param string $toPostcode
     */
    public function setToPostcode(string $toPostcode): void
    {
        $this->toPostcode = $toPostcode;
    }

    /**
     * @return string
     */
    public function getFromWeight(): string
    {
        return $this->fromWeight;
    }

    /**
     * @param string $fromWeight
     */
    public function setFromWeight(string $fromWeight): void
    {
        $this->fromWeight = $fromWeight;
    }

    /**
     * @return string
     */
    public function getToWeight(): mixed
    {
        return $this->toWeight;
    }

    /**
     * @param mixed $toWeight
     */
    public function setToWeight(mixed $toWeight): void
    {
        $this->toWeight = $toWeight;
    }

    /**
     * @return float|null
     */
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }
}
