<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the teacher/mentor dashboard with separate Subject Teacher vs Mentor views.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $viewMode = $request->query('view', 'guru_mapel'); // 'guru_mapel' or 'mentor'

        $teacher = $user->teacher()
            ->with([
                'subjects',
                'academicClasses',
                'lessonSchedules.subject',
                'lessonSchedules.academicClass',
                'subjectConsultationSlots.subject',
                'monthlySubmissionAlerts.student.user',
                'mentees.user',
                'mentees.academicClass',
                'mentees.vocabQuizResults',
                'mentees.speakingBookings.assessment',
                'mentees.attendances',
            ])
            ->first();

        return view('guru.dashboard', [
            'user' => $user,
            'teacher' => $teacher,
            'viewMode' => $viewMode,
        ]);
    }
}
