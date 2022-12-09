<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $setting=Setting::where('id', 1)->firstOrFail();
        \Storage::extend('google', function($app, $config) use ($setting) {
            $client = new \Google_Client();
            $client->setClientId($setting->google_drive_client_id);
            $client->setClientSecret($setting->google_drive_client_secret);
            $client->refreshToken($setting->google_drive_refresh_token);
            $service = new \Google_Service_Drive($client);

            $options = [];
            if(isset($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            $adapter = new GoogleDriveAdapter($service, $setting->google_drive_folder_id, $options);

            return new \League\Flysystem\Filesystem($adapter);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}