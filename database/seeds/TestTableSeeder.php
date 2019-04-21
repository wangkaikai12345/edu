<?php

use Illuminate\Database\Seeder;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $test = factory(\App\Models\Test::class)->create([
            'questions_count' => 10,
            'single_count' => 10,
            'total_score' => 50,
        ]);

        factory(\App\Models\Question::class, 10)->create([
            'type' => \App\Enums\QuestionType::SINGLE
        ])->each(function ($question) use ($test) {
            $test->questions()->attach($question->id, ['score' => 5]);
        });
    }
}
