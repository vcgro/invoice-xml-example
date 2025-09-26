<?php

declare(strict_types=1);

namespace Feature\Api;

use Tests\TestCase;

final class ApiTest extends TestCase
{
    public function test_main_api_page_returns_not_found(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(404);
    }

    public function test_error_api_page_returns_not_found(): void
    {
        $response = $this->get('/api/not_found_page');

        $response->assertStatus(404);
    }
}
