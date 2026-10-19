<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of students with search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $majorId = $request->query('major_id');
        $classId = $request->query('class_id');
        $status = $request->query('status');

        $students = Student::with(['user', 'major', 'academicClass', 'mentor.user', 'counselor'])
            ->when($search, function ($query, $search) {
                $query->where('nisn', 'like', "%{$search}%")
                    ->orWhere('parent_phone_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($majorId, fn ($q) => $q->where('major_id', $majorId))
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status_active', (bool) $status))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $majors = Major::orderBy('name')->get();
        $classes = AcademicClass::orderBy('name')->get();

        return view('admin.master.siswa.index', compact('students', 'majors', 'classes', 'search', 'majorId', 'classId', 'status'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        $majors = Major::orderBy('name')->get();
        $classes = AcademicClass::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        $counselors = User::role('guru_bk')->orderBy('name')->get();

        return view('admin.master.siswa.create', compact('majors', 'classes', 'teachers', 'counselors'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'nisn' => ['nullable', 'string', 'max:30', 'unique:students,nisn'],
            'major_id' => ['required', 'exists:majors,id'],
            'class_id' => ['required', 'exists:academic_classes,id'],
            'mentor_id' => ['required', 'exists:teachers,id'],
            'counselor_id' => ['required', 'exists:users,id'],
            'parent_phone_number' => ['nullable', 'string', 'max:25'],
            'status_active' => ['nullable', 'boolean'],
        ]);

        $statusActive = $request->boolean('status_active', true);
        $qrToken = 'ALTURA-STD-'.(! empty($validated['nisn']) ? strtoupper($validated['nisn']) : Str::upper(Str::random(10)));

        DB::transaction(function () use ($validated, $statusActive, $qrToken) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->syncRoles(['siswa']);

            Student::create([
                'user_id' => $user->id,
                'nisn' => $validated['nisn'],
                'major_id' => $validated['major_id'],
                'class_id' => $validated['class_id'],
                'mentor_id' => $validated['mentor_id'],
                'counselor_id' => $validated['counselor_id'],
                'parent_phone_number' => $validated['parent_phone_number'],
                'status_active' => $statusActive,
                'qr_identity_token' => $qrToken,
                'qr_code_token' => $qrToken,
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $siswa): View
    {
        $siswa->load(['user', 'major', 'academicClass', 'mentor', 'counselor']);

        $majors = Major::orderBy('name')->get();
        $classes = AcademicClass::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        $counselors = User::role('guru_bk')->orderBy('name')->get();

        return view('admin.master.siswa.edit', [
            'student' => $siswa,
            'majors' => $majors,
            'classes' => $classes,
            'teachers' => $teachers,
            'counselors' => $counselors,
        ]);
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$siswa->user_id],
            'password' => ['nullable', 'string', 'min:6'],
            'nisn' => ['nullable', 'string', 'max:30', 'unique:students,nisn,'.$siswa->id],
            'major_id' => ['required', 'exists:majors,id'],
            'class_id' => ['required', 'exists:academic_classes,id'],
            'mentor_id' => ['required', 'exists:teachers,id'],
            'counselor_id' => ['required', 'exists:users,id'],
            'parent_phone_number' => ['nullable', 'string', 'max:25'],
            'status_active' => ['nullable', 'boolean'],
        ]);

        $statusActive = $request->boolean('status_active');

        DB::transaction(function () use ($siswa, $validated, $statusActive) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $siswa->user->update($userData);

            $siswa->update([
                'nisn' => $validated['nisn'],
                'major_id' => $validated['major_id'],
                'class_id' => $validated['class_id'],
                'mentor_id' => $validated['mentor_id'],
                'counselor_id' => $validated['counselor_id'],
                'parent_phone_number' => $validated['parent_phone_number'],
                'status_active' => $statusActive,
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $siswa): RedirectResponse
    {
        DB::transaction(function () use ($siswa) {
            $user = $siswa->user;
            $siswa->delete();
            $user?->delete();
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa dan akun berhasil dihapus.');
    }

    /**
     * Import students from an uploaded Excel / CSV file.
     */
    public function importExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diimpor dari file.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses file impor: '.$e->getMessage());
        }
    }

    /**
     * Export all students to a downloadable CSV file.
     */
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'data_siswa_altura_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($handle, [
                'ID',
                'Nama Siswa',
                'Email',
                'NISN',
                'Jurusan',
                'Kelas',
                'Mentor',
                'Guru BK',
                'No HP Orang Tua',
                'Status Aktif',
                'Token QR Identitas',
            ]);

            Student::with(['user', 'major', 'academicClass', 'mentor.user', 'counselor'])
                ->chunk(100, function ($students) use ($handle) {
                    foreach ($students as $s) {
                        fputcsv($handle, [
                            $s->id,
                            $s->user?->name,
                            $s->user?->email,
                            $s->nisn,
                            $s->major?->name,
                            $s->academicClass?->name,
                            $s->mentor?->user?->name,
                            $s->counselor?->name,
                            $s->parent_phone_number,
                            $s->status_active ? 'Aktif' : 'Non-Aktif',
                            $s->qr_identity_token,
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, $headers);
    }
}
