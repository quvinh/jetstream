<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::find(1)->assignRole('admin');

        // $admin = Role::create(['name' => 'admin']);
        // $user = Role::create(['name' => 'user']);
        // $person = User::create([
        //     'email' => 'vinhhp2620@gmail.com',
        //     'name' => 'Ngô Quang Vinh',
        //     'password' => bcrypt('12345678')
        // ]);
        // $person->assignRole($admin);
    }
}
