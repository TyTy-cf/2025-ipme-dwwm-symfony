<?php

namespace App\Interfaces;

use DateTimeImmutable;

interface UpdatedAtInterface
{

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self;

}
