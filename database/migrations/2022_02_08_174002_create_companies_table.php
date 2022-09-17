<?php

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('social_reason');
            $table->string('rfc');
            $table->string('address')->nullable();
            $table->enum('state', [0, 1])->default(1);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            #Relations
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $setting=Setting::where('id', 1)->first();
        if (!is_null($setting)) {
            config(['filesystems.disks.google.clientId' => $setting->google_drive_client_id, 'filesystems.disks.google.clientSecret' => $setting->google_drive_client_secret, 'filesystems.disks.google.refreshToken' => $setting->google_drive_refresh_token, 'filesystems.disks.google.folderId' => $setting->google_drive_folder_id]);

            try {
                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));

                $customers=User::where('id', '!=', '1')->get();
                foreach ($customers as $customer) {
                    $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $customer->slug)->first();
                    if ($directory) {
                        Storage::disk('google')->deleteDirectory($directory['path']);
                    }
                }
            } catch (Exception $e) {
                Log::error("Google API Exception: ".$e->getMessage());
            }
        }

        Schema::dropIfExists('companies');
    }
}
