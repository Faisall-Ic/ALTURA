<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceFlag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlertFlagController extends Controller
{
    /**
     * Display automated student alerts & flag inbox.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $flagType = $request->query('flag_type');

        $query = AttendanceFlag::with(['student.user', 'student.academicClass', 'student.mentor.user', 'resolvedBy'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($flagType) {
            $query->where('flag_type', $flagType);
        }

        $flags = $query->paginate(15)->withQueryString();

        $openCount = AttendanceFlag::where('status', 'open')->count();
        $inProgressCount = AttendanceFlag::where('status', 'in_progress')->count();
        $resolvedCount = AttendanceFlag::where('status', 'resolved')->count();

        return view('guru_bk.flagging.index', compact(
            'flags',
            'status',
            'flagType',
            'openCount',
            'inProgressCount',
            'resolvedCount'
        ));
    }

    /**
     * Resolve or update status of an automated flag.
     */
    public function resolve(Request $request, int|string $flagId): RedirectResponse
    {
        $flag = AttendanceFlag::findOrFail($flagId);

        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,resolved'],
            'resolution_notes' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $flag->update([
            'status' => $validated['status'],
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_by_counselor_id' => $user->id,
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
        ]);

        $statusLabel = $validated['status'] === 'resolved' ? 'diselesaikan' : 'ditandai dalam penanganan';

        return redirect()->back()->with('success', "Indikator alert untuk {$flag->student->user->name} berhasil {$statusLabel}.");
    }
}
