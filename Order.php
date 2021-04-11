<?php


class Order
{
    private int $id;
    private int $quantity;
    private float $price;
    private bool $spoiled;

    /**
     * Order constructor.
     * @param $id
     * @param $quantity
     * @param $price
     */
    public function __construct($id, $quantity, $price)
    {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->spoiled = 0;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getSpoiled(): int
    {
        return $this->spoiled;
    }

    /**
     * @param int $spoiled
     */
    public function setSpoiled(int $spoiled): void
    {
        $this->spoiled = $spoiled;
    }


}