<?php

declare(strict_types=1);

namespace Feature\Web;

use Tests\TestCase;

final class WebTest extends TestCase
{
    public function test_main_web_page_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_error_web_page_returns_not_found(): void
    {
        $response = $this->get('/not_found_page');

        $response->assertStatus(404);
    }
}
