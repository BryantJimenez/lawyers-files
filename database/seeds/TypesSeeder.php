<?php

use Illuminate\Database\Seeder;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
    		['id' => 1, 'name' => 'Caso', 'slug' => 'caso', 'state' => '1'],
    		['id' => 2, 'name' => 'Declaración', 'slug' => 'declaracion', 'state' => '1']
    	];

    	DB::table('types')->insert($types);
    }
}
