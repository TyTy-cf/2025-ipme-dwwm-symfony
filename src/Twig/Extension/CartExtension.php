<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\CartExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_cart_quantity', [CartExtensionRuntime::class, 'getCartQuantity']),
        ];
    }
}
