<?php

namespace App\Twig\Runtime;

use App\Service\SessionCartService;
use Twig\Extension\RuntimeExtensionInterface;

class CartExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private SessionCartService $sessionCartService)
    {
        // Inject dependencies if needed
    }

    public function getCartQuantity(): int
    {
        return $this->sessionCartService->getCartQty();
    }
}
