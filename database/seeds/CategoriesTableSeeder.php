<?php

use App\Models\Category;
use App\Models\CategoryGroup;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = config('categories');

        foreach ($categories as $group) {
            // 分类组
            $databaseGroup = CategoryGroup::create(['name' => $group['name']]);
            // 分类
            if (isset($group['children']) && count($group['children'])) {
                foreach ($group['children'] as $item) {
                    $databaseCategory = Category::create([
                        'name' => $item['name'],
                        'category_group_id' => $databaseGroup->id
                    ]);
                    // 子分类
                    if (isset($item['children']) && count($item['children']) ) {
                        foreach ($item['children'] as $sonItem) {
                            Category::create([
                                'name' => $sonItem['name'],
                                'category_group_id' => $databaseGroup->id,
                                'parent_id' => $databaseCategory->id
                            ]);
                        }
                    }
                }
            }
        }
    }
}
