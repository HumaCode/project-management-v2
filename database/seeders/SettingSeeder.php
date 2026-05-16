<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Profil Sistem
            ['key' => 'app_name', 'value' => 'Project Management System', 'group' => 'profile'],
            ['key' => 'app_version', 'value' => 'v2.0.0', 'group' => 'profile'],
            ['key' => 'app_description', 'value' => 'Platform manajemen proyek terpadu untuk efisiensi kerja tim.', 'group' => 'profile'],
            ['key' => 'app_url', 'value' => 'http://localhost:8000', 'group' => 'profile'],
            ['key' => 'app_email', 'value' => 'admin@pmssystem.id', 'group' => 'profile'],
            ['key' => 'language', 'value' => 'id', 'group' => 'profile'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'group' => 'profile'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'group' => 'profile'],

            // Keamanan
            ['key' => 'password_min_length', 'value' => '8', 'group' => 'security'],
            ['key' => 'password_require_symbol', 'value' => '1', 'group' => 'security'],
            ['key' => 'password_require_number', 'value' => '1', 'group' => 'security'],
            ['key' => 'enable_otp', 'value' => '0', 'group' => 'security'],
            ['key' => 'enable_google_login', 'value' => '1', 'group' => 'security'],
            ['key' => 'allow_registration', 'value' => '1', 'group' => 'security'],
            ['key' => 'admin_approval', 'value' => '1', 'group' => 'security'],

            // Email / SMTP
            ['key' => 'mail_mailer', 'value' => 'smtp', 'group' => 'email'],
            ['key' => 'mail_host', 'value' => 'sandbox.smtp.mailtrap.io', 'group' => 'email'],
            ['key' => 'mail_port', 'value' => '2525', 'group' => 'email'],
            ['key' => 'mail_username', 'value' => '7110b8dd241fec', 'group' => 'email'],
            ['key' => 'mail_password', 'value' => '353adb56136dff', 'group' => 'email'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'group' => 'email'],
            ['key' => 'mail_from_address', 'value' => 'hello@example.com', 'group' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'Project Management System', 'group' => 'email'],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'maintenance'],
            ['key' => 'maintenance_message', 'value' => 'Kami sedang melakukan pemeliharaan sistem rutin. Mohon kembali lagi nanti.', 'group' => 'maintenance'],
            ['key' => 'maintenance_end_time', 'value' => '', 'group' => 'maintenance'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
