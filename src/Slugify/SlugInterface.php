<?php

namespace App\Slugify;

interface SlugInterface
{

    public function setSlug(string $slug): self;

    public function getFields(): ?string;

}
