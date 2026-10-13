<?php

namespace Tests\Feature;

use App\Models\Tryout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::role('admin')->first();
    }

    public function test_admin_can_manage_tryout_packages(): void
    {
        // 1. Index
        $response = $this->actingAs($this->adminUser)->get(route('admin.tryout.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Paket Tryout SNBT');

        // 2. Store
        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.tryout.store'), [
            'title' => 'Tryout Spesial UTBK Gelombang 4',
            'date_start' => now()->addDays(5)->toDateString(),
            'date_end' => now()->addDays(12)->toDateString(),
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('admin.tryout.index'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('tryouts', [
            'title' => 'Tryout Spesial UTBK Gelombang 4',
        ]);

        $createdTryout = Tryout::where('title', 'Tryout Spesial UTBK Gelombang 4')->first();

        // 3. Delete
        $deleteResponse = $this->actingAs($this->adminUser)->delete(route('admin.tryout.destroy', $createdTryout->id));
        $deleteResponse->assertRedirect(route('admin.tryout.index'));
        $this->assertDatabaseMissing('tryouts', ['id' => $createdTryout->id]);
    }

    public function test_admin_can_audit_whatsapp_logs_and_send_test_message(): void
    {
        // 1. Index
        $response = $this->actingAs($this->adminUser)->get(route('admin.whatsapp.index'));
        $response->assertStatus(200);
        $response->assertSee('WhatsApp Gateway');

        // 2. Test send
        $testResponse = $this->actingAs($this->adminUser)->post(route('admin.whatsapp.test'), [
            'phone' => '628999888777',
            'message' => 'Pesan uji coba admin Altura.',
        ]);

        $testResponse->assertRedirect(route('admin.whatsapp.index'));
        $testResponse->assertSessionHas('success');

        $this->assertDatabaseHas('wa_message_logs', [
            'parent_phone_number' => '628999888777',
            'trigger_event' => 'admin_manual_test',
        ]);
    }
}
