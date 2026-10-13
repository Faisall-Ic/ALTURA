<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@altura.test')->first();
        $this->studentUser = User::where('email', 'siswa@altura.test')->first();
    }

    /**
     * Non-admin cannot access admin master data routes.
     */
    public function test_non_admin_cannot_access_master_data(): void
    {
        $this->actingAs($this->studentUser)->get(route('admin.jurusan.index'))->assertStatus(403);
        $this->actingAs($this->studentUser)->get(route('admin.kelas.index'))->assertStatus(403);
        $this->actingAs($this->studentUser)->get(route('admin.guru.index'))->assertStatus(403);
        $this->actingAs($this->studentUser)->get(route('admin.siswa.index'))->assertStatus(403);
    }

    /**
     * Admin can view and create a major.
     */
    public function test_admin_can_manage_majors(): void
    {
        $this->actingAs($this->adminUser)
            ->get(route('admin.jurusan.index'))
            ->assertStatus(200)
            ->assertSee('Master Data Jurusan');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.jurusan.store'), [
                'name' => 'Bahasa & Budaya',
                'code' => 'BAHASA',
                'is_snbt_mandatory' => true,
            ]);

        $response->assertRedirect(route('admin.jurusan.index'));
        $this->assertDatabaseHas('majors', ['code' => 'BAHASA', 'name' => 'Bahasa & Budaya']);

        $major = Major::where('code', 'BAHASA')->first();

        // Update
        $this->actingAs($this->adminUser)
            ->put(route('admin.jurusan.update', $major), [
                'name' => 'Bahasa & Sastra',
                'code' => 'BAHASA',
                'is_snbt_mandatory' => false,
            ])
            ->assertRedirect(route('admin.jurusan.index'));

        $this->assertDatabaseHas('majors', ['code' => 'BAHASA', 'name' => 'Bahasa & Sastra', 'is_snbt_mandatory' => false]);

        // Delete
        $this->actingAs($this->adminUser)
            ->delete(route('admin.jurusan.destroy', $major))
            ->assertRedirect(route('admin.jurusan.index'));

        $this->assertDatabaseMissing('majors', ['code' => 'BAHASA']);
    }

    /**
     * Admin cannot delete major if classes are linked.
     */
    public function test_admin_cannot_delete_major_with_classes(): void
    {
        $mipaMajor = Major::where('code', 'MIPA')->first();

        $this->actingAs($this->adminUser)
            ->delete(route('admin.jurusan.destroy', $mipaMajor))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('majors', ['code' => 'MIPA']);
    }

    /**
     * Admin can view and create an academic class.
     */
    public function test_admin_can_manage_classes(): void
    {
        $mipaMajor = Major::where('code', 'MIPA')->first();

        $this->actingAs($this->adminUser)
            ->get(route('admin.kelas.index'))
            ->assertStatus(200)
            ->assertSee('Master Data Kelas');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.kelas.store'), [
                'major_id' => $mipaMajor->id,
                'name' => '12 MIPA Unggulan',
                'code' => '12-MIPA-UNGGUL',
            ]);

        $response->assertRedirect(route('admin.kelas.index'));
        $this->assertDatabaseHas('academic_classes', ['code' => '12-MIPA-UNGGUL']);

        $class = AcademicClass::where('code', '12-MIPA-UNGGUL')->first();

        // Update
        $this->actingAs($this->adminUser)
            ->put(route('admin.kelas.update', $class), [
                'major_id' => $mipaMajor->id,
                'name' => '12 MIPA Riset',
                'code' => '12-MIPA-UNGGUL',
            ])
            ->assertRedirect(route('admin.kelas.index'));

        $this->assertDatabaseHas('academic_classes', ['name' => '12 MIPA Riset']);

        // Delete
        $this->actingAs($this->adminUser)
            ->delete(route('admin.kelas.destroy', $class))
            ->assertRedirect(route('admin.kelas.index'));

        $this->assertDatabaseMissing('academic_classes', ['code' => '12-MIPA-UNGGUL']);
    }

    /**
     * Admin can view and create a teacher.
     */
    public function test_admin_can_manage_teachers(): void
    {
        $this->actingAs($this->adminUser)
            ->get(route('admin.guru.index'))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.guru.store'), [
                'name' => 'Dr. Hendra Wijaya',
                'email' => 'hendra.guru@altura.test',
                'password' => 'password123',
                'nip' => '198001012005011001',
                'specialization_subject' => 'Fisika & Penalaran Sains',
            ]);

        $response->assertRedirect(route('admin.guru.index'));

        $this->assertDatabaseHas('users', ['email' => 'hendra.guru@altura.test']);
        $this->assertDatabaseHas('teachers', ['nip' => '198001012005011001']);

        $teacherUser = User::where('email', 'hendra.guru@altura.test')->first();
        $this->assertTrue($teacherUser->hasRole('guru'));

        $teacher = Teacher::where('nip', '198001012005011001')->first();

        // Update
        $this->actingAs($this->adminUser)
            ->put(route('admin.guru.update', $teacher), [
                'name' => 'Prof. Dr. Hendra Wijaya',
                'email' => 'hendra.guru@altura.test',
                'nip' => '198001012005011001',
                'specialization_subject' => 'Fisika Kuantum & TPS',
            ])
            ->assertRedirect(route('admin.guru.index'));

        $this->assertDatabaseHas('teachers', ['specialization_subject' => 'Fisika Kuantum & TPS']);

        // Delete
        $this->actingAs($this->adminUser)
            ->delete(route('admin.guru.destroy', $teacher))
            ->assertRedirect(route('admin.guru.index'));

        $this->assertDatabaseMissing('teachers', ['nip' => '198001012005011001']);
        $this->assertDatabaseMissing('users', ['email' => 'hendra.guru@altura.test']);
    }

    /**
     * Admin can view, filter, and create a student.
     */
    public function test_admin_can_manage_students(): void
    {
        $major = Major::where('code', 'MIPA')->first();
        $class = AcademicClass::where('code', '12-MIPA-01')->first();
        $teacher = Teacher::first();
        $counselor = User::role('guru_bk')->first();

        $this->actingAs($this->adminUser)
            ->get(route('admin.siswa.index', ['major_id' => $major->id, 'q' => 'Rizky']))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.siswa.store'), [
                'name' => 'Dewi Sartika',
                'email' => 'dewi@altura.test',
                'password' => 'password123',
                'nisn' => '0098765432',
                'major_id' => $major->id,
                'class_id' => $class->id,
                'mentor_id' => $teacher->id,
                'counselor_id' => $counselor->id,
                'parent_phone_number' => '6281987654321',
                'status_active' => true,
            ]);

        $response->assertRedirect(route('admin.siswa.index'));

        $this->assertDatabaseHas('users', ['email' => 'dewi@altura.test']);
        $this->assertDatabaseHas('students', [
            'nisn' => '0098765432',
            'qr_identity_token' => 'ALTURA-STD-0098765432',
        ]);

        $student = Student::where('nisn', '0098765432')->first();

        // Update student
        $this->actingAs($this->adminUser)
            ->put(route('admin.siswa.update', $student), [
                'name' => 'Dewi Sartika Putri',
                'email' => 'dewi@altura.test',
                'nisn' => '0098765432',
                'major_id' => $major->id,
                'class_id' => $class->id,
                'mentor_id' => $teacher->id,
                'counselor_id' => $counselor->id,
                'parent_phone_number' => '6281987654321',
                'status_active' => false,
            ])
            ->assertRedirect(route('admin.siswa.index'));

        $this->assertDatabaseHas('students', ['nisn' => '0098765432', 'status_active' => false]);

        // Delete
        $this->actingAs($this->adminUser)
            ->delete(route('admin.siswa.destroy', $student))
            ->assertRedirect(route('admin.siswa.index'));

        $this->assertDatabaseMissing('students', ['nisn' => '0098765432']);
        $this->assertDatabaseMissing('users', ['email' => 'dewi@altura.test']);
    }

    /**
     * Admin can export students to CSV.
     */
    public function test_admin_can_export_students_csv(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.siswa.export'));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    /**
     * Admin can import students via CSV file.
     */
    public function test_admin_can_import_students_csv(): void
    {
        $csvContent = "nama,email,nisn,kode_jurusan,kode_kelas,nip_mentor,email_bk,no_hp_ortu,password\n";
        $csvContent .= "Farhan Akbar,farhan@altura.test,0099887766,MIPA,12-MIPA-01,198501152010011002,nisa.bk@altura.test,6281122334455,password\n";

        $file = UploadedFile::fake()->createWithContent('import_siswa.csv', $csvContent);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.siswa.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('users', ['email' => 'farhan@altura.test']);
        $this->assertDatabaseHas('students', [
            'nisn' => '0099887766',
            'qr_identity_token' => 'ALTURA-STD-0099887766',
        ]);
    }
}
