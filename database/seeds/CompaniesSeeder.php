<?php

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Company::class, 5)->create();

        $companies=Company::all();
        foreach ($companies as $company) {
        	Storage::disk('google')->makeDirectory($company->slug);
        }
    }
}
