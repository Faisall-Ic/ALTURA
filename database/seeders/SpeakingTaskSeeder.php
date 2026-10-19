<?php

namespace Database\Seeders;

use App\Models\SpeakingAssessment;
use App\Models\SpeakingBooking;
use App\Models\SpeakingTask;
use App\Models\StorytellingSubmission;
use App\Models\StorytellingTheme;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\WacanaSubmission;
use App\Models\WacanaText;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SpeakingTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Storytelling Themes (Rule 5: ALTO assigns monthly themes rather than fixed scripts)
        $themeOct = StorytellingTheme::firstOrCreate(
            [
                'month' => 10,
                'year' => 2026,
            ],
            [
                'theme_name' => 'Technological Breakthroughs and Society',
                'description' => 'Describe a major historical or futuristic scientific invention and its ethical, social, and economic impacts on human civilization.',
            ]
        );

        $themeNov = StorytellingTheme::firstOrCreate(
            [
                'month' => 11,
                'year' => 2026,
            ],
            [
                'theme_name' => 'Overcoming Adversity & Building Resilience',
                'description' => 'Share a narrative about a community, leader, or personal figure who persevered against severe odds to bring positive change.',
            ]
        );

        StorytellingTheme::firstOrCreate(
            [
                'month' => 12,
                'year' => 2026,
            ],
            [
                'theme_name' => 'Environmental Stewardship & Global Sustainability',
                'description' => 'Explore the balance between economic development and ecological preservation through a compelling real or fictional story.',
            ]
        );

        // 2. Wacana Texts (Rule 5 & Table 18)
        $wacana1 = WacanaText::firstOrCreate(
            ['title' => 'Artificial Intelligence in Secondary and Higher Education'],
            [
                'content' => "The exponential proliferation of generative artificial intelligence is fundamentally transforming the landscape of secondary education. Rather than viewing machine learning models merely as potential cheating instruments, innovative educators must embrace them as dynamic cognitive scaffolds.\n\nFirst and foremost, AI empowers hyper-personalized learning pathways. Traditional classrooms often adhere to a standardized pace, inevitably leaving slower students behind while constraining accelerated learners. Adaptive AI tutors can instantly diagnose foundational mathematical gaps or vocabulary deficiencies, serving customized drills tailored to each student's unique cognitive cadence.\n\nSecondly, AI tools liberate teachers from time-consuming administrative grading, allowing them to redirect their finite energy toward mentorship, character development, and high-order critical reasoning seminars. However, this pedagogical revolution must be accompanied by ethical guardrails. Students must be rigorously trained in prompt engineering, source verification, and intellectual honesty. Ultimately, artificial intelligence should not replace human curiosity, but rather amplify our pursuit of profound scholastic excellence.",
                'paragraph_count' => 3,
            ]
        );

        $wacana2 = WacanaText::firstOrCreate(
            ['title' => 'Sustainable Energy Transition in Developing Economies'],
            [
                'content' => "Developing nations face a paradoxical dilemma: accelerating rapid economic industrialization while simultaneously honoring ambitious international carbon emission reduction treaties. The conventional assumption that economic prosperity necessitates fossil fuel combustion is increasingly invalidated by recent breakthroughs in renewable infrastructure.\n\nSolar photovoltaic and onshore wind power have achieved grid parity in numerous emerging markets, drastically reducing capital expenditure requirements. Decentralized micro-grids empower isolated rural communities without requiring multi-billion-dollar transmission grid expansions, simultaneously fostering localized commercial resilience and gender empowerment.\n\nNevertheless, the intermittent nature of renewable sources demands strategic grid modernization, advanced battery energy storage systems, and comprehensive policy de-risking. Multilateral financial institutions must provide concessional climate financing to mitigate initial foreign exchange exposure. With deliberate technological leapfrogging, developing economies can achieve both robust sovereign energy security and planetary sustainability.",
                'paragraph_count' => 3,
            ]
        );

        // 3. Legacy SpeakingTask (for backwards compatibility with existing views)
        $task1 = SpeakingTask::firstOrCreate(
            ['title' => 'Storytelling: The Legend of Rawa Pening'],
            [
                'type' => 'storytelling',
                'minimum_pass_grade' => 75.00,
                'script_text' => 'Once upon a time in Central Java, there lived a poor boy named Baru Klinthing...',
            ]
        );

        $task2 = SpeakingTask::firstOrCreate(
            ['title' => 'Wacana Akademik: Artificial Intelligence in High School Education'],
            [
                'type' => 'wacana',
                'minimum_pass_grade' => 75.00,
                'script_text' => 'The exponential proliferation of generative artificial intelligence is fundamentally transforming the landscape of secondary education.',
            ]
        );

        // 4. Student submissions & bookings
        $student = Student::first();
        $teacher = Teacher::first();

        if ($student && $teacher) {
            // Storytelling Submission
            StorytellingSubmission::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'theme_id' => $themeOct->id,
                ],
                [
                    'title' => 'The Spark of Innovation: How Alan Turing Shaped Computing',
                    'script_text' => 'Alan Mathison Turing was an English mathematician, computer scientist, logician, cryptanalyst, philosopher, and theoretical biologist. He played a pivotal role in cracking intercepted coded messages during World War II.',
                    'notes' => 'Presentation prepared for October Monthly Theme. Script outline attached.',
                    'status' => 'approved',
                ]
            );

            // Wacana Submission
            WacanaSubmission::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'wacana_id' => $wacana1->id,
                ],
                [
                    'status' => 'submitted',
                ]
            );

            // Completed oral exam booking
            $completedBooking = SpeakingBooking::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'booking_date' => Carbon::now()->subDays(5)->toDateString(),
                ],
                [
                    'mentor_id' => $student->mentor_id,
                    'task_type' => 'storytelling',
                    'speaking_task_id' => $task1->id,
                    'time_slot' => '15:30 - 16:00',
                    'status' => 'completed',
                    'grade' => 88.00,
                    'rubric_pronunciation' => 22,
                    'rubric_fluency' => 23,
                    'rubric_vocabulary' => 21,
                    'rubric_comprehension' => 22,
                    'rubric_notes' => 'Pelafalan fonetik intonasi sangat natural. Alur penceritaan hidup dan ekspresif.',
                    'graded_at' => Carbon::now()->subDays(5)->setHour(16),
                ]
            );

            // Create assessment record (Table 19)
            SpeakingAssessment::firstOrCreate(
                ['booking_id' => $completedBooking->id],
                [
                    'grade' => 88.00,
                    'rubric_scores' => [
                        'pronunciation' => 22,
                        'fluency' => 23,
                        'vocabulary' => 21,
                        'comprehension' => 22,
                    ],
                    'notes' => 'Pelafalan fonetik intonasi sangat natural. Alur penceritaan hidup dan ekspresif.',
                ]
            );

            // Upcoming booked oral exam
            SpeakingBooking::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'booking_date' => Carbon::now()->addDays(3)->toDateString(),
                ],
                [
                    'mentor_id' => $student->mentor_id,
                    'task_type' => 'storytelling',
                    'speaking_task_id' => $task1->id,
                    'time_slot' => '16:00 - 16:30',
                    'status' => 'pending',
                ]
            );
        }
    }
}
