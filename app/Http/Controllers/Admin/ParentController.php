<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentGuardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ParentController extends Controller
{
    /**
     * Display a listing of parents/guardians.
     */
    public function index(Request $request): View
    {
        $parents = ParentGuardian::with(['user', 'students.academicClass'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.master.orang_tua.index', compact('parents'));
    }

    /**
     * Show form for creating a new parent account.
     */
    public function create(): View
    {
        $students = Student::with(['user', 'academicClass'])->where('status_active', true)->get();

        return view('admin.master.orang_tua.create', compact('students'));
    }

    /**
     * Store a newly created parent account and map students.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'nullable|string|max:25',
            'occupation' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'relationship_type' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->syncRoles(['orang_tua']);

        $parent = ParentGuardian::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone_number'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        if (! empty($validated['student_ids'])) {
            $relType = $validated['relationship_type'] ?: 'Wali';
            $syncData = [];
            foreach ($validated['student_ids'] as $sid) {
                $syncData[$sid] = ['relationship_type' => $relType];
                // Also update parent_user_id on Student for direct reference
                Student::where('id', $sid)->update(['parent_user_id' => $user->id]);
            }
            $parent->students()->sync($syncData);
        }

        return redirect()->route('admin.orang-tua.index')
            ->with('success', "Akun Orang Tua / Wali {$user->name} berhasil didaftarkan.");
    }

    /**
     * Show form for editing a parent.
     */
    public function edit(ParentGuardian $orangTua): View
    {
        $parent = $orangTua->load(['user', 'students']);
        $students = Student::with(['user', 'academicClass'])->get();
        $selectedStudentIds = $parent->students->pluck('id')->toArray();
        $currentRelationship = $parent->students->first()?->pivot?->relationship_type ?? 'Wali';

        return view('admin.master.orang_tua.edit', compact('parent', 'students', 'selectedStudentIds', 'currentRelationship'));
    }

    /**
     * Update the specified parent account and mappings.
     */
    public function update(Request $request, ParentGuardian $orangTua): RedirectResponse
    {
        $parent = $orangTua;
        $user = $parent->user;

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'phone_number' => 'nullable|string|max:25',
            'occupation' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'relationship_type' => 'nullable|string|max:50',
        ]);

        $userData = ['name' => $validated['name'], 'email' => $validated['email']];
        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $user->update($userData);

        $parent->update([
            'phone_number' => $validated['phone_number'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        $relType = $validated['relationship_type'] ?: 'Wali';
        $syncData = [];
        if (! empty($validated['student_ids'])) {
            foreach ($validated['student_ids'] as $sid) {
                $syncData[$sid] = ['relationship_type' => $relType];
                Student::where('id', $sid)->update(['parent_user_id' => $user->id]);
            }
        }
        $parent->students()->sync($syncData);

        return redirect()->route('admin.orang-tua.index')
            ->with('success', "Data Orang Tua / Wali {$user->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified parent account.
     */
    public function destroy(ParentGuardian $orangTua): RedirectResponse
    {
        $name = $orangTua->user?->name;
        $userId = $orangTua->user_id;

        $orangTua->delete();
        if ($userId) {
            User::destroy($userId);
        }

        return redirect()->route('admin.orang-tua.index')
            ->with('success', "Akun Orang Tua {$name} berhasil dihapus.");
    }
}
