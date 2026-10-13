<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;

    protected Student $student;

    protected Teacher $mentor;

    protected Teacher $otherTeacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->studentUser = User::where('email', 'siswa@altura.test')->first();
        $this->student = $this->studentUser->student;
        $this->mentor = $this->student->mentor;
        $this->otherTeacher = Teacher::where('id', '!=', $this->mentor->id)->first();

        // Register test routes for middleware verification
        Route::middleware(['web', 'auth', 'mentor_locked'])->get('/test/mentor-check/{mentorId}', function ($mentorId) {
            return response()->json(['status' => 'authorized_mentor', 'mentor_id' => $mentorId]);
        });

        Route::middleware(['web', 'auth', 'auto_enrollment'])->get('/test/auto-enroll/{majorId?}', function () {
            return response()->json(['status' => 'access_granted']);
        });
    }

    /**
     * Active student can access student dashboard.
     */
    public function test_active_student_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Rizky Pratama');
    }

    /**
     * Inactive student is blocked by EnsureActiveStudent middleware with 403.
     */
    public function test_inactive_student_is_blocked_with_403(): void
    {
        $this->student->update(['status_active' => false]);

        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.dashboard'));

        $response->assertStatus(403);
    }

    /**
     * Student can interact with their own designated mentor.
     */
    public function test_student_can_access_own_mentor_interactions(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get("/test/mentor-check/{$this->mentor->id}");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'authorized_mentor']);
    }

    /**
     * Student is blocked by EnsureStudentMentorLocked when targeting unauthorized teacher.
     */
    public function test_student_blocked_from_unauthorized_mentor_interactions(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get("/test/mentor-check/{$this->otherTeacher->id}");

        $response->assertStatus(403);
    }

    /**
     * AutoEnrollmentAccess grants seamless access without paywall.
     */
    public function test_auto_enrollment_access_grants_access_to_enrolled_major(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get("/test/auto-enroll/{$this->student->major_id}");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'access_granted']);
    }

    /**
     * AutoEnrollmentAccess blocks content for non-mandatory disparate major.
     */
    public function test_auto_enrollment_access_blocks_disparate_major(): void
    {
        // Switch student major to SMK (where is_snbt_mandatory = false)
        $smkMajor = Major::where('code', 'SMK')->first();
        $this->student->update(['major_id' => $smkMajor->id]);

        $mipaMajor = Major::where('code', 'MIPA')->first();

        $response = $this->actingAs($this->studentUser)
            ->get("/test/auto-enroll/{$mipaMajor->id}");

        $response->assertStatus(403);
    }
}
