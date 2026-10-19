<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\ParentGuardian;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Alto
        $admin = User::firstOrCreate(
            ['email' => 'admin@altura.test'],
            [
                'name' => 'Admin Alto',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['admin']);

        // 1b. Front Desk Admin (Kakak Administrasi - Rule 2)
        $frontDesk = User::firstOrCreate(
            ['email' => 'frontdesk@altura.test'],
            [
                'name' => 'Kakak Administrasi (Front Desk ALTO)',
                'password' => Hash::make('password'),
            ]
        );
        $frontDesk->syncRoles(['admin', 'front_desk']);

        // 2. Counselor Kak Nisa (Guru BK)
        $counselor = User::firstOrCreate(
            ['email' => 'nisa.bk@altura.test'],
            [
                'name' => 'Kak Nisa, S.Psi (Guru BK)',
                'password' => Hash::make('password'),
            ]
        );
        $counselor->syncRoles(['guru_bk']);

        // 3. Teacher 1 (Pak Budi - Mentor)
        $teacherUser1 = User::firstOrCreate(
            ['email' => 'budi.guru@altura.test'],
            [
                'name' => 'Pak Budi Santoso, M.Pd',
                'password' => Hash::make('password'),
            ]
        );
        $teacherUser1->syncRoles(['guru']);

        $teacher1 = Teacher::firstOrCreate(
            ['user_id' => $teacherUser1->id],
            [
                'nip' => '198501152010011002',
                'phone_number' => '6281122334455',
                'specialization_subject' => 'Matematika & Penalaran Kuantitatif',
            ]
        );

        // Map teacher 1 to subjects & classes
        $mathSubjects = Subject::whereIn('code', ['PM', 'PK', 'MAT-PEM'])->pluck('id');
        $teacher1->subjects()->syncWithoutDetaching($mathSubjects);
        $mathClasses = AcademicClass::whereIn('code', ['12-MIPA-01', '12-MIPA-02', 'SNBT-01'])->pluck('id');
        $teacher1->academicClasses()->syncWithoutDetaching($mathClasses);

        // 4. Teacher 2 (Ibu Siti)
        $teacherUser2 = User::firstOrCreate(
            ['email' => 'siti.guru@altura.test'],
            [
                'name' => 'Ibu Siti Rahma, S.Pd',
                'password' => Hash::make('password'),
            ]
        );
        $teacherUser2->syncRoles(['guru']);

        $teacher2 = Teacher::firstOrCreate(
            ['user_id' => $teacherUser2->id],
            [
                'nip' => '199002202015022003',
                'phone_number' => '6281199887766',
                'specialization_subject' => 'Bahasa Inggris & Literasi',
            ]
        );

        $englishSubjects = Subject::whereIn('code', ['LBE'])->pluck('id');
        $teacher2->subjects()->syncWithoutDetaching($englishSubjects);
        $englishClasses = AcademicClass::whereIn('code', ['12-MIPA-01', '12-IPS-01'])->pluck('id');
        $teacher2->academicClasses()->syncWithoutDetaching($englishClasses);

        // 5. Parent (Pak Bambang)
        $parentUser = User::firstOrCreate(
            ['email' => 'ortu@altura.test'],
            [
                'name' => 'Bambang Pratama (Wali Siswa)',
                'password' => Hash::make('password'),
            ]
        );
        $parentUser->syncRoles(['orang_tua']);

        $parentGuardian = ParentGuardian::firstOrCreate(
            ['user_id' => $parentUser->id],
            [
                'phone_number' => '6281234567890',
                'occupation' => 'Wiraswasta / Profesional',
                'address' => 'Jl. Alumni Tutorial No. 45, Bandung',
            ]
        );

        // 6. Student (Ahmad Rizky Pratama)
        $mipaMajor = Major::where('code', 'MIPA')->first();
        $class12Mipa1 = AcademicClass::where('code', '12-MIPA-01')->first();

        $studentUser = User::firstOrCreate(
            ['email' => 'siswa@altura.test'],
            [
                'name' => 'Ahmad Rizky Pratama',
                'password' => Hash::make('password'),
            ]
        );
        $studentUser->syncRoles(['siswa']);

        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'nisn' => '0067829101',
                'major_id' => $mipaMajor->id,
                'class_id' => $class12Mipa1->id,
                'mentor_id' => $teacher1->id,
                'counselor_id' => $counselor->id,
                'parent_user_id' => $parentUser->id,
                'parent_phone_number' => '6281234567890',
                'status_active' => true,
                'qr_identity_token' => 'ALTURA-STD-0067829101',
                'qr_code_token' => 'ALTURA-STD-0067829101',
            ]
        );

        if (! $student->parent_user_id) {
            $student->update(['parent_user_id' => $parentUser->id]);
        }

        // Map student to parent in student_parent pivot
        $student->parents()->syncWithoutDetaching([
            $parentGuardian->id => ['relationship_type' => 'Ayah'],
        ]);

        // 7. Student 2 with Nullable NISN (Rule 1: ALTO data can lack NISN)
        $studentUser2 = User::firstOrCreate(
            ['email' => 'dewi.siswa@altura.test'],
            [
                'name' => 'Dewi Lestari (NISN Belum Dilengkapi)',
                'password' => Hash::make('password'),
            ]
        );
        $studentUser2->syncRoles(['siswa']);

        Student::firstOrCreate(
            ['user_id' => $studentUser2->id],
            [
                'nisn' => null, // Explicit null NISN
                'major_id' => $mipaMajor->id,
                'class_id' => $class12Mipa1->id,
                'mentor_id' => $teacher1->id,
                'counselor_id' => $counselor->id,
                'parent_user_id' => null,
                'parent_phone_number' => '6281987654321',
                'status_active' => true,
                'qr_identity_token' => 'ALTURA-STD-DEWINT99',
                'qr_code_token' => 'ALTURA-STD-DEWINT99',
            ]
        );
    }
}
