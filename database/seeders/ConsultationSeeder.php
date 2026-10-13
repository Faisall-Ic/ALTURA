<?php

namespace Database\Seeders;

use App\Models\ConsultationBooking;
use App\Models\ConsultationSlot;
use App\Models\CounselingBooking;
use App\Models\CounselingSlot;
use App\Models\ParentGuardian;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectConsultationBooking;
use App\Models\SubjectConsultationSlot;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = Teacher::first();
        $student = Student::first();

        if (! $teacher || ! $student) {
            return;
        }

        // Slot 1: Active booked slot
        $slot1 = ConsultationSlot::firstOrCreate(
            [
                'mentor_id' => $teacher->id,
                'topic' => 'Tentoring Intensif TPS Penalaran Matematika',
                'date_time' => Carbon::now()->addDays(2)->setHour(15)->setMinute(30)->setSecond(0),
            ],
            [
                'max_quota' => 5,
                'remaining_quota' => 4,
                'location_or_link' => 'Ruang Konsultasi Alto Lantai 2',
            ]
        );

        ConsultationBooking::firstOrCreate(
            [
                'slot_id' => $slot1->id,
                'student_id' => $student->id,
            ],
            [
                'type' => 'student_mapel',
                'status' => 'confirmed',
                'notes' => 'Ingin konsultasi soal-soal peluang dan kombinatorika sulit',
            ]
        );

        // Slot 2: Open future slot with full quota
        ConsultationSlot::firstOrCreate(
            [
                'mentor_id' => $teacher->id,
                'topic' => 'Klinik Soal Literasi & TPS Skolastik Lanjutan',
                'date_time' => Carbon::now()->addDays(4)->setHour(16)->setMinute(0)->setSecond(0),
            ],
            [
                'max_quota' => 4,
                'remaining_quota' => 4,
                'location_or_link' => 'Ruang Bimbingan 1A',
            ]
        );

        // Slot 3: Past completed slot
        $pastSlot = ConsultationSlot::firstOrCreate(
            [
                'mentor_id' => $teacher->id,
                'topic' => 'Evaluasi Hasil Tryout SNBT 1 & Pemetaan Materi Lemah',
                'date_time' => Carbon::now()->subDays(5)->setHour(14)->setMinute(0)->setSecond(0),
            ],
            [
                'max_quota' => 3,
                'remaining_quota' => 2,
                'location_or_link' => 'Ruang Konsultasi Alto Lantai 2',
            ]
        );

        // Seed Subject Consultation (Tentoring Mata Pelajaran)
        $subject = Subject::where('code', 'PM')->first() ?? Subject::first();
        if ($subject) {
            $subjSlot = SubjectConsultationSlot::firstOrCreate(
                [
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'date_time' => Carbon::now()->addDays(2)->setHour(15)->setMinute(30)->setSecond(0),
                ],
                [
                    'topic' => 'Tentoring Intensif TPS Penalaran Matematika',
                    'room_or_link' => 'Ruang Tentoring Alto Lantai 2',
                    'max_quota' => 5,
                    'remaining_quota' => 4,
                ]
            );

            SubjectConsultationBooking::firstOrCreate(
                [
                    'slot_id' => $subjSlot->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => 'booked',
                ]
            );
        }

        // Seed Counseling Slot & Booking (Bimbingan Konseling BK - Kak Nisa)
        $counselorUser = User::where('email', 'nisa.bk@altura.test')->first();
        $parent = ParentGuardian::first();
        if ($counselorUser) {
            $bkSlot = CounselingSlot::firstOrCreate(
                [
                    'counselor_user_id' => $counselorUser->id,
                    'date_time' => Carbon::now()->addDays(3)->setHour(10)->setMinute(0)->setSecond(0),
                ],
                [
                    'room_or_link' => 'Ruang BK Alto / Tatap Muka Konseling',
                    'max_quota' => 1,
                    'remaining_quota' => 0,
                ]
            );

            CounselingBooking::firstOrCreate(
                [
                    'slot_id' => $bkSlot->id,
                    'student_id' => $student->id,
                ],
                [
                    'parent_id' => $parent?->id,
                    'booking_type' => 'siswa',
                    'notes' => 'Konsultasi strategi pemilihan prodi SNBP dan SNBT serta manajemen stres belajar.',
                    'status' => 'booked',
                ]
            );
        }
    }
}
