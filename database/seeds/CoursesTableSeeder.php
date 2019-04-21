<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 课程、版本、章、节、任务、任务完成状况
        $course = factory(\App\Models\Course::class)->create([
            'default_plan_id' => 1,
            'status' => \App\Enums\Status::PUBLISHED
        ]);

        $plan = factory(\App\Models\Plan::class)->create([
            'course_id' => $course->id,
            'course_title' => $course->title,
            'status' => \App\Enums\Status::PUBLISHED
        ]);

        $chapter = factory(\App\Models\Chapter::class)->create([
            'title' => '第一章',
            'course_id' => $course->id,
            'plan_id' => $plan->id,
            'seq' => 1
        ]);
        $section = factory(\App\Models\Chapter::class)->create([
            'title' => '第一节',
            'course_id' => $course->id,
            'plan_id' => $plan->id,
            'parent_id' => $chapter->id,
            'seq' => 1
        ]);

        // 创建资源
        $audio = factory(\App\Models\Audio::class)->create();
        $video = factory(\App\Models\Video::class)->create();
        $ppt = factory(\App\Models\Ppt::class)->create();
        $doc = factory(\App\Models\Doc::class)->create();
        $text = factory(\App\Models\Text::class)->create();
        $test = \App\Models\Test::find(1);

        foreach (compact('audio', 'video', 'ppt', 'doc', 'text', 'test') as $key => $item) {
            factory(\App\Models\Task::class)->create([
                'course_id' => $course->id,
                'plan_id' => $plan->id,
                'chapter_id' =>  $section->id,
                'target_type' => $key,
                'target_id' => $item->id,
                'status' => \App\Enums\Status::PUBLISHED
            ]);
        }
    }
}
