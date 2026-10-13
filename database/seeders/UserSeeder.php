<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Student;
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
                'specialization_subject' => 'Matematika & Penalaran Kuantitatif',
            ]
        );

        // 4. Teacher 2 (Ibu Siti)
        $teacherUser2 = User::firstOrCreate(
            ['email' => 'siti.guru@altura.test'],
            [
                'name' => 'Ibu Siti Rahma, S.Pd',
                'password' => Hash::make('password'),
            ]
        );
        $teacherUser2->syncRoles(['guru']);

        Teacher::firstOrCreate(
            ['user_id' => $teacherUser2->id],
            [
                'nip' => '199002202015022003',
                'specialization_subject' => 'Bahasa Inggris & Literasi',
            ]
        );

        // 5. Parent (Pak Bambang)
        $parentUser = User::firstOrCreate(
            ['email' => 'ortu@altura.test'],
            [
                'name' => 'Bambang Pratama (Wali Siswa)',
                'password' => Hash::make('password'),
            ]
        );
        $parentUser->syncRoles(['orang_tua']);

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
            ]
        );

        if (! $student->parent_user_id) {
            $student->update(['parent_user_id' => $parentUser->id]);
        }
    }
}
