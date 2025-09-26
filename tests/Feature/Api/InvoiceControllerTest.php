<?php

declare(strict_types=1);

namespace Feature\Api;

use App\Models\Invoice;
use App\Models\InvoiceMetadata;
use App\Repositories\Contracts\InvoiceRepositoryContract;
use App\Services\Invoice\Contracts\InvoiceStorageContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

final class InvoiceControllerTest extends TestCase
{
    //    use RefreshDatabase;
    use DatabaseTransactions;

    public function test_invoice_index_page_returns_a_successful_empty_response(): void
    {
        $response = $this->get('/api/invoices/get');

        $response->assertStatus(200)
            ->assertJsonStructure(['invoices' => []]);
    }

    public function test_invoice_index_page_returns_a_successful_response(): void
    {
        Invoice::factory()
            ->has(InvoiceMetadata::factory())
            ->withFilepath()
            ->count(3)
            ->create();

        $response = $this->get('/api/invoices/get');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'invoices')
            ->assertJsonStructure([
                'invoices' => [
                    '*' => [
                        'id',
                        'created_at',
                        'metadata' => [
                            'invoice_number',
                            'issue_date',
                            'supplier_id',
                            'customer_id',
                            'payable_amount',
                        ],
                    ],
                ],
            ]);
    }

    public function test_invoice_show_page_returns_a_successful_response(): void
    {
        $response = $this->get('/api/invoices/get');

        $response->assertStatus(200)
            ->assertJsonStructure(['invoices' => []]);
    }

    public function test_invoice_show_returns_streamed_response_when_file_exists(): void
    {
        $invoiceId = 1;

        $this->mock(InvoiceStorageContract::class, function (MockInterface $mock) use ($invoiceId): void {
            $mock->shouldReceive('fileExists')
                ->with($invoiceId)
                ->andReturn(true);

            $mock->shouldReceive('download')
                ->once()
                ->with($invoiceId)
                ->andReturn(
                    new StreamedResponse(
                        null,
                        200,
                        ['Content-Type' => 'application/xml']
                    )
                );
        });

        $response = $this->get(sprintf('/api/invoices/%d/xml', $invoiceId));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function test_invoice_show_returns_error_when_file_does_not_exists(): void
    {
        $response = $this->get("/api/invoices/0/xml");

        $response->assertStatus(500);
    }

    public function test_invoice_create_returns_a_successful_response(): void
    {
        $xml = file_get_contents(base_path('tests/Stubs/invoice_create_request.xml'));

        $this->mock(InvoiceStorageContract::class, function (MockInterface $mock): void {
            $mock->shouldReceive('getInvoiceFilepath')
                ->andReturn('test_path');

            $mock->shouldReceive('forcePutOrFail')
                ->once()
                ->withAnyArgs()
                ->andReturnNull();
        });

        $response = $this->call(
            'POST',
            '/api/invoices/create',
            [],
            [],
            [],
            ['Content-Type' => 'application/xml'],
            $xml
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'message']);
    }

    public function test_invoice_create_returns_a_xml_parsing_error(): void
    {
        $this->mock(InvoiceRepositoryContract::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('createWithMetadata');
        });

        $this->mock(InvoiceStorageContract::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('forcePutOrFail');
        });

        $response = $this->post(
            '/api/invoices/create',
            [],
            ['Content-Type' => 'application/xml'],
        );

        $response->assertStatus(500);
    }
}
