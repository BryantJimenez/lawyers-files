<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(StatementsSeeder::class);
    }
}
