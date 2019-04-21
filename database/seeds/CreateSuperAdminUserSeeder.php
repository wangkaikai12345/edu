<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'username' => 'superAdmin',
            'password' => bcrypt('123456'),
            'invitation_code' => str_random(6),
        ];
        $user = User::create($user);
        // 额外信息
        $user->profile()->save(factory(\App\Models\Profile::class)->make());
        // 配置权限
        $user->assignRole(\App\Enums\UserType::SUPER_ADMIN);
    }
}
