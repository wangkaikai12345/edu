<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建 Admin
        $admin = factory(\App\Models\User::class)->create(['username' => 'admin']);
        $admin->profile()->save(factory(\App\Models\Profile::class)->make());
        $admin->assignRole(App\Enums\UserType::ADMIN);

        factory(\App\Models\User::class, 100)->create()->each(function ($item) {
            $item->profile()->save(factory(\App\Models\Profile::class)->make());
            $item->assignRole(App\Enums\UserType::STUDENT);
        });
    }
}
