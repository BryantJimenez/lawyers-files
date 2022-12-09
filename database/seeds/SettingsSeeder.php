<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
    		['id' => 1, 'logo' => 'logo.svg', 'header_background_color' => '#ffffff', 'header_text_color' => '#1b2e4b', 'menu_background_color' => '#ffffff', 'menu_background_color_hover' => '#eaf1ff', 'menu_icon_color' => '#3b3f5c', 'menu_text_color' => '#3b3f5c', 'menu_border_color' => '#e0e6ed', 'header_text' => NULL, 'google_drive_client_id' => '289345783573-lf8ulmbesidpbnl1pup0jeq4csrads6c.apps.googleusercontent.com', 'google_drive_client_secret' => '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', 'google_drive_refresh_token' => '1//04mt8OJSL_TPnCgYIARAAGAQSNwF-L9IrZ53bDQhOyKrn1WmM_AxaH-q2JNezdujatGtaxNLWDeyvypHnbxPaOxKoyJvN8d-AEjo', 'google_drive_folder_id' => '1k92SyUco4FR96_MB3nsgM7oXRH49Vc_l']
    	];

    	DB::table('settings')->insert($settings);
    }
}
