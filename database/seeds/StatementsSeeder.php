<?php

use App\Models\Statement;
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
        factory(Statement::class, 20)->create();

        $statements=Statement::with(['company'])->get();
        foreach ($statements as $statement) {
        	$path='/';
            $recursive=false;
            $contents=collect(Storage::disk('google')->listContents($path, $recursive));
            $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();
    		Storage::disk('google')->makeDirectory($directory['path'].'/'.$statement->slug);
        }
    }
}
