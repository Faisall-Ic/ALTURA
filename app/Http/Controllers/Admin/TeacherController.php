<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $teachers = Teacher::with('user')
            ->withCount('mentees')
            ->when($search, function ($query, $search) {
                $query->where('nip', 'like', "%{$search}%")
                    ->orWhere('specialization_subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.master.guru.index', compact('teachers', 'search'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create(): View
    {
        return view('admin.master.guru.create');
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
            'specialization_subject' => ['required', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->syncRoles(['guru']);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'specialization_subject' => $validated['specialization_subject'],
            ]);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru/mentor berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $guru): View
    {
        $guru->load('user');

        return view('admin.master.guru.edit', ['teacher' => $guru]);
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
            'specialization_subject' => ['required', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($guru, $validated) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $guru->user->update($userData);

            $guru->update([
                'nip' => $validated['nip'] ?? null,
                'specialization_subject' => $validated['specialization_subject'],
            ]);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru/mentor berhasil diperbarui.');
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
            $user = $guru->user;
            $guru->delete();
            $user?->delete();
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
