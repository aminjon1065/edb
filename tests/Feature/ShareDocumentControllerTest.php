<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Document;
use App\Models\ShareDocument;

class ShareDocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_document_with_files()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/share-document', [
                'title' => 'Test Document Title', // Пример заголовка
                'content' => 'Test document content.', // Пример содержания
                'control' => 'Some control information',
                'type' => 'Some type',
                'date_done' => '2023-08-09',
                'files' => [/* массив файлов */],
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('documents', 1);
        $this->assertDatabaseCount('share_documents', 1);
        $this->assertDatabaseCount('files', 3);
    }

    public function test_store_document_toRais()
    {
        // Тест для метода store, когда документ отправляется к Rais
    }

    public function test_store_document_to_multiple_users()
    {
        // Тест для метода store, когда документ отправляется нескольким пользователям
    }

    // Другие тесты для других методов контроллера

    // Тесты для методов toRaisReplyDocument и sharedRaisReplyToUsers
}
