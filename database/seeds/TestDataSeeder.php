<?php

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
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
            'title' => '测试课程',
            'status' => \App\Enums\Status::PUBLISHED
        ]);

        // 免费的
        $plan = factory(\App\Models\Plan::class)->create([
            'course_id' => $course->id,
            'course_title' => $course->title,
            'status' => \App\Enums\Status::PUBLISHED,
            'is_free' => 1,
            'is_default' => 1,
            'coin_price' => 0,
            'price' => 0,
        ]);

        // 虚拟币的
        factory(\App\Models\Plan::class)->create([
            'title' => '虚拟币支付测试',
            'course_id' => $course->id,
            'course_title' => $course->title,
            'status' => \App\Enums\Status::PUBLISHED,
            'is_free' => 0,
            'coin_price' => 1,
            'price' => 0,
        ]);

        // 现金的
        factory(\App\Models\Plan::class)->create([
            'title' => '现金支付测试',
            'course_id' => $course->id,
            'course_title' => $course->title,
            'status' => \App\Enums\Status::PUBLISHED,
            'is_free' => 0,
            'coin_price' => 0,
            'price' => 1,
        ]);

        $chapter = factory(\App\Models\Chapter::class)->create([
            'title' => '测试章',
            'course_id' => $course->id,
            'plan_id' => $plan->id,
            'seq' => 1
        ]);
        $section = factory(\App\Models\Chapter::class)->create([
            'title' => '测试节',
            'course_id' => $course->id,
            'plan_id' => $plan->id,
            'parent_id' => $chapter->id,
            'seq' => 1
        ]);

        // 创建资源
//        $video = factory(\App\Models\Video::class)->create();
//
//        factory(\App\Models\Task::class)->create([
//            'course_id' => $course->id,
//            'plan_id' => $plan->id,
//            'chapter_id' =>  $section->id,
//            'target_type' => 'video',
//            'type' => 'c-task',
//            'target_id' => $video->id,
//            'status' => \App\Enums\Status::PUBLISHED
//        ]);

    }
}
