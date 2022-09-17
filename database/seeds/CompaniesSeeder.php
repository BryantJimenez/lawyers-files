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

        $companies=Company::with(['user'])->get();
        foreach ($companies as $company) {
            try {
                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $company['user']->slug)->first();
                Storage::disk('google')->makeDirectory($directory['path'].'/'.$company->slug);
            } catch (Exception $e) {
                Log::error("Google API Exception: ".$e->getMessage());
            }
        }
    }
}
