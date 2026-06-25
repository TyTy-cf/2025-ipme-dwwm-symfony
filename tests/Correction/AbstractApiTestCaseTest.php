<?php

declare(strict_types=1);

namespace App\Tests\Correction;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\AbstractBrowser;

class AbstractApiTestCaseTest extends ApiTestCase
{
    static string $LOGIN_CHECK = '/api/login_check';
    static string $ME = '/api/me';

    protected string $defaultUrl;
    protected bool $skipAccessTest = false;
    protected KernelBrowser|AbstractBrowser|null $client;

    protected function setUp(): void
    {
    }



}
