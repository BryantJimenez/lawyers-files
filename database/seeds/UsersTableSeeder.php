<?php

use App\Models\User;
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
        User::create([
        	'name' => 'Super',
            'lastname' => 'Admin',
            'phone' => '12345678',
        	'photo' => 'usuario.png',
        	'slug' => 'super-admin',
        	'email' => 'admin@gmail.com',
        	'password' => bcrypt('12345678'),
        	'state' => "1"
        ]);
    }
}
