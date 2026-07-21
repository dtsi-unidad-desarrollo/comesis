<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\PageExpiredException;
use Tests\TestCase;

class PageExpiredExceptionTest extends TestCase
{
    public function test_page_expired_exception_uses_419_status(): void
    {
        $exception = new PageExpiredException('La página ha expirado.');

        $this->assertSame(419, $exception->getStatusCode());
        $this->assertSame('La página ha expirado.', $exception->getMessage());
    }
}
