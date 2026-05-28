<?php

namespace App\DTO;

class CartDTO
{

    private array $gamesDTO = [];

    private int $totalPrice = 0;

    /**
     * @param int $totalPrice
     * @param array $gamesDTO
     */
    public function __construct(int $totalPrice, array $gamesDTO)
    {
        $this->totalPrice = $totalPrice;
        $this->gamesDTO = $gamesDTO;
    }

    public function getGamesDTO(): array
    {
        return $this->gamesDTO;
    }

    public function setGamesDTO(array $gamesDTO): CartDTO
    {
        $this->gamesDTO = $gamesDTO;
        return $this;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): CartDTO
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

}
