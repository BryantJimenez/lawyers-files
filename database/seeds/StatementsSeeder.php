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

        $statements=Statement::with(['company.user'])->get();
        foreach ($statements as $statement) {
            try {
                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']['user']->slug)->first();

                $path='/'.$directory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();
                Storage::disk('google')->makeDirectory($directory['path'].'/'.$subdirectory['path'].'/'.$statement->slug);
            } catch (Exception $e) {
                Log::error("Google API Exception: ".$e->getMessage());
            }
        }
    }
}
