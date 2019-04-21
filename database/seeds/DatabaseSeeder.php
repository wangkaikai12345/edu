<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 权限要放在最前面，因为添加超管需要用到
        $this->call(RoleAndPermissionsTableSeeder::class);
        $this->call(CreateSuperAdminUserSeeder::class);
        $this->call(CategoriesTableSeeder::class);

        if (app()->environment() === 'local') {
//            $this->call(UsersTableSeeder::class);
            $this->call(TagsTableSeeder::class);


//            $this->call(TestTableSeeder::class);
//            $this->call(CoursesTableSeeder::class);
//            $this->call(ConversationsTableSeeder::class);
//            $this->call(CouponsTableSeeder::class);



//            $this->call(SettingTableSeeder::class);
//            $this->call(TestDataSeeder::class);
//            $this->call(CreateNumSeeder::class);
//            $this->call(CreateCalendrSeeder::class);
        }
    }
}
