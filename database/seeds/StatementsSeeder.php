<?php

use App\Models\Statement\Statement;
use Illuminate\Database\Seeder;

class StatementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Statement::class, 200)->create();
    }
}
