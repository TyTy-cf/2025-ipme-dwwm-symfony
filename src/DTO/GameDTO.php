<?php

namespace App\DTO;

class GameDTO
{

    private string $name;
    private string $slug;
    private string $urlShow;
    private string|null $thumbnailCover;
    private string|null $thumbnailCoverLink;
    private int $price;

    /**
     * @param string $name
     * @param string $slug
     * @param string|null $thumbnailCover
     * @param string|null $thumbnailCoverLink
     * @param int $price
     */
    public function __construct(string $name, string $slug, string $urlShow, string|null $thumbnailCover, string|null $thumbnailCoverLink, int $price)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->thumbnailCover = $thumbnailCover;
        $this->thumbnailCoverLink = $thumbnailCoverLink;
        $this->price = $price;
        $this->urlShow = $urlShow;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): GameDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): GameDTO
    {
        $this->slug = $slug;
        return $this;
    }

    public function getThumbnailCover(): string|null
    {
        return $this->thumbnailCover;
    }

    public function setThumbnailCover(string|null $thumbnailCover): GameDTO
    {
        $this->thumbnailCover = $thumbnailCover;
        return $this;
    }

    public function getThumbnailCoverLink(): string|null
    {
        return $this->thumbnailCoverLink;
    }

    public function setThumbnailCoverLink(string|null $thumbnailCoverLink): GameDTO
    {
        $this->thumbnailCoverLink = $thumbnailCoverLink;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): GameDTO
    {
        $this->price = $price;
        return $this;
    }

    public function getUrlShow(): string
    {
        return $this->urlShow;
    }

    public function setUrlShow(string $urlShow): GameDTO
    {
        $this->urlShow = $urlShow;
        return $this;
    }

}
