<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = \App\Models\TagGroup::create(['name' => 'course', 'description' => '课程']);
        $number = 10;
        factory(\App\Models\Tag::class, $number)->create(['tag_group_id' => $group->id]);
        $group->tags_count = $number;
        $group->save();
    }
}
