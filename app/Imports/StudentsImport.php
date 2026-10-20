<?php

namespace App\Imports;

use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row): ?Student
    {
        $email = trim($row['email'] ?? $row['email_siswa'] ?? '');
        $name = trim($row['name'] ?? $row['nama'] ?? $row['nama_lengkap'] ?? '');

        if (empty($email) || empty($name)) {
            return null;
        }

        // 1. Resolve or Create User
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($row['password'] ?? 'password'),
            ]
        );

        if (! $user->hasRole('siswa')) {
            $user->assignRole('siswa');
        }

        // 2. Resolve Major
        $majorId = null;
        if (! empty($row['major_id'])) {
            $majorId = $row['major_id'];
        } elseif (! empty($row['major_code']) || ! empty($row['jurusan'])) {
            $majorCode = strtoupper(trim($row['major_code'] ?? $row['jurusan']));
            $major = Major::where('code', $majorCode)->orWhere('name', 'like', "%{$majorCode}%")->first();
            $majorId = $major?->id;
        }
        $majorId = $majorId ?: (Major::first()?->id ?? 1);

        // 3. Resolve Class
        $classId = null;
        if (! empty($row['class_id'])) {
            $classId = $row['class_id'];
        } elseif (! empty($row['class_code']) || ! empty($row['kelas'])) {
            $classCode = trim($row['class_code'] ?? $row['kelas']);
            $class = AcademicClass::where('code', $classCode)->orWhere('name', 'like', "%{$classCode}%")->first();
            $classId = $class?->id;
        }
        $classId = $classId ?: (AcademicClass::where('major_id', $majorId)->first()?->id ?? AcademicClass::first()?->id);

        // 4. Resolve Mentor (Teacher)
        $mentorId = null;
        if (! empty($row['mentor_id'])) {
            $mentorId = $row['mentor_id'];
        } elseif (! empty($row['mentor_nip']) || ! empty($row['mentor_email'])) {
            $mentorQuery = Teacher::query();
            if (! empty($row['mentor_nip'])) {
                $mentorQuery->where('nip', trim($row['mentor_nip']));
            } else {
                $mentorQuery->whereHas('user', fn ($uq) => $uq->where('email', trim($row['mentor_email'])));
            }
            $mentor = $mentorQuery->first();
            $mentorId = $mentor?->id;
        }
        $mentorId = $mentorId ?: (Teacher::first()?->id ?? 1);

        // 5. Resolve Counselor (User)
        $counselorId = null;
        if (! empty($row['counselor_id'])) {
            $counselorId = $row['counselor_id'];
        } elseif (! empty($row['counselor_email'])) {
            $counselor = User::role('guru_bk')->where('email', trim($row['counselor_email']))->first();
            $counselorId = $counselor?->id;
        }
        $counselorId = $counselorId ?: (User::role('guru_bk')->first()?->id ?? User::role('admin')->first()?->id ?? $user->id);

        // 6. Handle Nullable NISN (Rule 1: ALTO data can lack NISN without import failure)
        $rawNisn = trim((string) ($row['nisn'] ?? ''));
        $nisn = ! empty($rawNisn) ? $rawNisn : null;

        // Ensure unique NISN if already exists
        if ($nisn && Student::where('nisn', $nisn)->where('user_id', '!=', $user->id)->exists()) {
            $nisn = null; // Prevent collision
        }

        $qrToken = ! empty($nisn) ? 'ALTURA-STD-'.$nisn : 'ALTURA-STD-'.Str::upper(Str::random(10));
        $parentPhone = trim((string) ($row['parent_phone_number'] ?? $row['telepon_orang_tua'] ?? $row['no_hp_ortu'] ?? ''));

        // 7. Update or Create Student Profile
        return Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nisn' => $nisn,
                'major_id' => $majorId,
                'class_id' => $classId,
                'mentor_id' => $mentorId,
                'counselor_id' => $counselorId,
                'parent_phone_number' => $parentPhone ?: null,
                'status_active' => true,
                'qr_identity_token' => $qrToken,
                'qr_code_token' => $qrToken,
            ]
        );
    }
}
