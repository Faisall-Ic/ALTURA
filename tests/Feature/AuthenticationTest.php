<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test login screen renders successfully.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('ALTURA');
        $response->assertSee('Masuk ke Portal ALTURA');
    }

    /**
     * Test root URL redirects guest to login.
     */
    public function test_guest_is_redirected_to_login_from_root(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    /**
     * Test guest cannot access protected role dashboards.
     */
    public function test_guest_cannot_access_protected_dashboards(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/siswa/dashboard')->assertRedirect('/login');
        $this->get('/guru/dashboard')->assertRedirect('/login');
        $this->get('/bk/dashboard')->assertRedirect('/login');
        $this->get('/ortu/dashboard')->assertRedirect('/login');
    }

    /**
     * Test admin user login redirects to admin dashboard.
     */
    public function test_admin_user_can_login_and_redirect_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@altura.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Test student user login redirects to student dashboard.
     */
    public function test_student_user_can_login_and_redirect_to_student_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'siswa@altura.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('siswa.dashboard'));
    }

    /**
     * Test teacher user login redirects to teacher dashboard.
     */
    public function test_teacher_user_can_login_and_redirect_to_teacher_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'budi.guru@altura.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('guru.dashboard'));
    }

    /**
     * Test counselor user login redirects to counselor dashboard.
     */
    public function test_counselor_user_can_login_and_redirect_to_counselor_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'nisa.bk@altura.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('bk.dashboard'));
    }

    /**
     * Test parent user login redirects to parent dashboard.
     */
    public function test_parent_user_can_login_and_redirect_to_parent_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'ortu@altura.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('ortu.dashboard'));
    }

    /**
     * Test role boundary enforcement: student cannot access admin dashboard.
     */
    public function test_student_cannot_access_admin_dashboard(): void
    {
        $studentUser = User::where('email', 'siswa@altura.test')->first();

        $response = $this->actingAs($studentUser)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'siswa@altura.test')->first();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
