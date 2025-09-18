<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\PriceExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PriceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('euro', [PriceExtensionRuntime::class, 'transformEuro']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [PriceExtensionRuntime::class, 'doSomething']),
        ];
    }
}
