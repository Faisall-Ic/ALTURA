<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Display a listing of the teachers & mentors.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $teachers = Teacher::with(['user', 'subjects', 'academicClasses'])
            ->withCount('mentees')
            ->when($search, function ($query, $search) {
                $query->where('nip', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('specialization_subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subjects', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.master.guru.index', compact('teachers', 'search'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $classes = AcademicClass::orderBy('name')->get();

        return view('admin.master.guru.create', compact('subjects', 'classes'));
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:teachers,nip'],
            'phone_number' => ['nullable', 'string', 'max:25'],
            'specialization_subject' => ['nullable', 'string', 'max:100'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['exists:academic_classes,id'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->syncRoles(['guru']);

            // Auto-fallback specialization text if empty
            $specialization = $validated['specialization_subject'] ?? null;
            if (empty($specialization) && ! empty($request->input('subject_ids'))) {
                $specialization = Subject::whereIn('id', $request->input('subject_ids'))->pluck('name')->implode(', ');
            }

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'specialization_subject' => $specialization ?: 'Pengajar',
            ]);

            if ($request->has('subject_ids')) {
                $teacher->subjects()->sync($request->input('subject_ids', []));
            }

            if ($request->has('class_ids')) {
                $teacher->academicClasses()->sync($request->input('class_ids', []));
            }
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru/mentor beserta pemetaan mata pelajaran dan kelas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $guru): View
    {
        $guru->load(['user', 'subjects', 'academicClasses']);
        $subjects = Subject::orderBy('name')->get();
        $classes = AcademicClass::orderBy('name')->get();

        return view('admin.master.guru.edit', [
            'teacher' => $guru,
            'subjects' => $subjects,
            'classes' => $classes,
            'selectedSubjectIds' => $guru->subjects->pluck('id')->toArray(),
            'selectedClassIds' => $guru->academicClasses->pluck('id')->toArray(),
        ]);
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, Teacher $guru): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$guru->user_id],
            'password' => ['nullable', 'string', 'min:6'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:teachers,nip,'.$guru->id],
            'phone_number' => ['nullable', 'string', 'max:25'],
            'specialization_subject' => ['nullable', 'string', 'max:100'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['exists:academic_classes,id'],
        ]);

        DB::transaction(function () use ($guru, $validated, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $guru->user->update($userData);

            $specialization = $validated['specialization_subject'] ?? null;
            if (empty($specialization) && ! empty($request->input('subject_ids'))) {
                $specialization = Subject::whereIn('id', $request->input('subject_ids'))->pluck('name')->implode(', ');
            }

            $guru->update([
                'nip' => $validated['nip'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'specialization_subject' => $specialization ?: $guru->specialization_subject ?: 'Pengajar',
            ]);

            $guru->subjects()->sync($request->input('subject_ids', []));
            $guru->academicClasses()->sync($request->input('class_ids', []));
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru/mentor dan pemetaan penugasan berhasil diperbarui.');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $guru): RedirectResponse
    {
        if ($guru->mentees()->exists()) {
            return redirect()->route('admin.guru.index')
                ->with('error', 'Guru tidak dapat dihapus karena masih menjadi mentor bagi siswa binaan.');
        }

        DB::transaction(function () use ($guru) {
            $guru->subjects()->detach();
            $guru->academicClasses()->detach();
            $user = $guru->user;
            $guru->delete();
            $user?->delete();
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
