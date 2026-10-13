<?php

namespace Database\Seeders;

use App\Models\SpeakingBooking;
use App\Models\SpeakingTask;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SpeakingTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task1 = SpeakingTask::firstOrCreate(
            [
                'title' => 'Storytelling: The Legend of Rawa Pening',
            ],
            [
                'type' => 'storytelling',
                'minimum_pass_grade' => 75.00,
                'script_text' => "Once upon a time in Central Java, there lived a poor boy named Baru Klinthing. He was cursed to look wounded and smelly, which caused greedy villagers to shun and mock him. Hungry and tired, he walked through the village festival asking for food, but everyone turned him away except a kind, elderly widow named Mbok Rondo.\n\nMbok Rondo gave him warm rice and welcomed him with genuine warmth. Before leaving, Baru Klinthing advised her: 'Mother, if you hear a roaring sound tonight, climb into your rice mortar (lesung) and take this wooden paddle.'\n\nReturning to the village square, Baru Klinthing stuck a small wooden stick into the ground and challenged the proud villagers to pull it out. One by one, the strongest men tried, but none could budge the stick. Finally, Baru Klinthing pulled it out with ease. From the hole in the ground, water gushed out endlessly, drowning the arrogant villagers and transforming the valley into a vast lake known today as Rawa Pening. Only kind Mbok Rondo survived, teaching us that humility and empathy are greater than vanity.",
            ]
        );

        $task2 = SpeakingTask::firstOrCreate(
            [
                'title' => 'Storytelling: The Legend of Tangkuban Perahu',
            ],
            [
                'type' => 'storytelling',
                'minimum_pass_grade' => 75.00,
                'script_text' => "Long ago in West Java, Dayang Sumbi was a beautiful woman endowed with eternal youth. Her son, Sangkuriang, loved hunting in the deep forest accompanied by his loyal dog, Tumang. Unbeknownst to Sangkuriang, Tumang was actually his bewitched father.\n\nAfter a disappointing hunt, Sangkuriang made a tragic mistake that enraged Dayang Sumbi, causing her to banish him from their home. Years later, Sangkuriang wandered back as a strong warrior and unknowingly fell in love with Dayang Sumbi. Recognizing a scar on his head, she realized he was her son and sought an impossible task to prevent their marriage: Sangkuriang had to dam the Citarum river and build a gigantic wooden boat before sunrise.\n\nCalling upon mystical forest spirits, Sangkuriang almost finished the task. Panicking, Dayang Sumbi spread red silk cloths across the east to simulate dawn, tricking roosters into crowing. Furious at the deception, Sangkuriang kicked the giant boat upside down, which solidified into Mount Tangkuban Perahu, a reminder of the consequences of unbridled passion and destiny.",
            ]
        );

        SpeakingTask::firstOrCreate(
            [
                'title' => 'Wacana Akademik: Artificial Intelligence in High School Education',
            ],
            [
                'type' => 'wacana',
                'minimum_pass_grade' => 75.00,
                'script_text' => "The exponential proliferation of generative artificial intelligence is fundamentally transforming the landscape of secondary education. Rather than viewing machine learning models merely as potential cheating instruments, innovative educators must embrace them as dynamic cognitive scaffolds.\n\nFirst and foremost, AI empowers hyper-personalized learning pathways. Traditional classrooms often adhere to a standardized pace, inevitably leaving slower students behind while constraining accelerated learners. Adaptive AI tutors can instantly diagnose foundational mathematical gaps or vocabulary deficiencies, serving customized drills tailored to each student's unique cognitive cadence.\n\nSecondly, AI tools liberate teachers from time-consuming administrative grading, allowing them to redirect their finite energy toward mentorship, character development, and high-order critical reasoning seminars. However, this pedagogical revolution must be accompanied by ethical guardrails. Students must be rigorously trained in prompt engineering, source verification, and intellectual honesty. Ultimately, artificial intelligence should not replace human curiosity, but rather amplify our pursuit of profound scholastic excellence.",
            ]
        );

        SpeakingTask::firstOrCreate(
            [
                'title' => 'Wacana Akademik: Sustainable Energy Transition in Developing Economies',
            ],
            [
                'type' => 'wacana',
                'minimum_pass_grade' => 75.00,
                'script_text' => "Transitioning toward sustainable renewable energy represents one of the most pressing socio-economic imperatives of the twenty-first century, especially across emerging Southeast Asian economies. While industrialized Western nations possess the capital reserves to retire fossil fuel infrastructure rapidly, developing countries face the delicate dual mandate of curbing carbon emissions while simultaneously expanding affordable electrification for industrial growth.\n\nDecarbonization strategies must capitalize on indigenous geographical endowments. In the Indonesian archipelago, geothermal volcanic reservoirs and year-round equatorial solar irradiance present immense clean baseload potential. Furthermore, decentralized micro-grid architectures can bypass the exorbitant capital expenditures required for centralized transmission across thousands of islands.\n\nTo finance this green transition without exacerbating public sovereign debt, transparent international climate partnerships and blended green bonds must be mobilized. By coupling technological innovation with prudent fiscal incentives, emerging nations can achieve economic prosperity without compromising the ecological health of future generations.",
            ]
        );

        // Seed 1 completed booking & 1 upcoming booking for test student
        $student = Student::first();
        $teacher = Teacher::first();

        if ($student && $teacher) {
            // Completed oral exam
            SpeakingBooking::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'speaking_task_id' => $task1->id,
                    'booking_date' => Carbon::now()->subDays(5)->toDateString(),
                ],
                [
                    'mentor_id' => $student->mentor_id,
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

            // Upcoming booked oral exam
            SpeakingBooking::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'speaking_task_id' => $task2->id,
                    'booking_date' => Carbon::now()->addDays(3)->toDateString(),
                ],
                [
                    'mentor_id' => $student->mentor_id,
                    'time_slot' => '16:00 - 16:30',
                    'status' => 'pending',
                ]
            );
        }
    }
}
