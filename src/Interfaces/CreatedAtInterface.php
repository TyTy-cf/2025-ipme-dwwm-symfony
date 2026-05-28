<?php

namespace App\Interfaces;

use DateTimeImmutable;

interface CreatedAtInterface
{

    public function setCreatedAt(DateTimeImmutable $createdAt): self;

}
