<?php

namespace Database\Seeders;

use App\Models\Pays;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* CI */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+225")->first()->id,
            'ip_register'       => '160.154.156.144',
            'email'             => 'boubacarly93@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+2250789842126',
            'recent_ip'         => '160.154.156.144',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+225")->first()->id,
            'ip_register'       => '41.207.192.0',
            'email'             => 'felicia-eponou@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+2250787812622',
            'recent_ip'         => '41.207.192.0',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* CI */

        /* CM */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+237")->first()->id,
            'ip_register'       => '102.135.189.255',
            'email'             => 'utilisateur-1-Cameroune@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+2370789842126',
            'recent_ip'         => '102.135.189.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+237")->first()->id,
            'ip_register'       => '41.194.43.255',
            'email'             => 'utilisateur-2-Cameroune@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+2370546358498',
            'recent_ip'         => '41.194.43.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* CM */

        /* GB */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+241")->first()->id,
            'ip_register'       => '102.129.32.0',
            'email'             => 'utilisateur-1-gabon@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+2410789842126',
            'recent_ip'         => '102.129.32.0',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+241")->first()->id,
            'ip_register'       => '136.23.0.145',
            'email'             => 'utilisateur-2-gabon@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+2410546358498',
            'recent_ip'         => '136.23.0.145',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* GB */

        /* FR */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+33")->first()->id,
            'ip_register'       => '82.224.0.0',
            'email'             => 'axel-obiang@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+330789842126',
            'recent_ip'         => '82.224.0.0',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+33")->first()->id,
            'ip_register'       => '188.165.59.127',
            'email'             => 'sydney-obiang@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+330546358498',
            'recent_ip'         => '188.165.59.127',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* FR */

        /* BE */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+32")->first()->id,
            'ip_register'       => '101.97.37.104',
            'email'             => 'utilisateur-1-belgique@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+320789842126',
            'recent_ip'         => '101.97.37.104',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+32")->first()->id,
            'ip_register'       => '109.106.15.255',
            'email'             => 'utilisateur-2-belgique@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+320546358498',
            'recent_ip'         => '109.106.15.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* BE */

        /* BR */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+55")->first()->id,
            'ip_register'       => '101.33.22.255',
            'email'             => 'utilisateur-1-bresil@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+550789842126',
            'recent_ip'         => '101.33.22.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+55")->first()->id,
            'ip_register'       => '103.4.97.58',
            'email'             => 'utilisateur-2-bresil@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+550546358498',
            'recent_ip'         => '103.4.97.58',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* BR */

        /* PA */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+507")->first()->id,
            'ip_register'       => '103.173.151.255',
            'email'             => 'utilisateur-1-Panama@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+5070789842126',
            'recent_ip'         => '103.173.151.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+507")->first()->id,
            'ip_register'       => '104.28.126.121',
            'email'             => 'utilisateur-2-Panama@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+5070546358498',
            'recent_ip'         => '104.28.126.121',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* PA */

        /* CN */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+86")->first()->id,
            'ip_register'       => '1.63.255.255',
            'email'             => 'utilisateur-1-Chine@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+860789842126',
            'recent_ip'         => '1.63.255.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+86")->first()->id,
            'ip_register'       => '101.102.95.255',
            'email'             => 'utilisateur-2-Chine@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+860546358498',
            'recent_ip'         => '101.102.95.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* CN */

        /* JP */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+81")->first()->id,
            'ip_register'       => '1.115.255.255',
            'email'             => 'utilisateur-1-Japon@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+810789842126',
            'recent_ip'         => '1.115.255.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+81")->first()->id,
            'ip_register'       => '101.128.255.255',
            'email'             => 'utilisateur-2-Japon@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+810546358498',
            'recent_ip'         => '101.128.255.255',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* JP */

        /* US */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+1201")->first()->id,
            'ip_register'       => '69.162.81.155',
            'email'             => 'utilisateur-1-USA@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+12010789842126',
            'recent_ip'         => '69.162.81.155',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+1201")->first()->id,
            'ip_register'       => '207.250.234.100',
            'email'             => 'utilisateur-2-USA@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+12010546358498',
            'recent_ip'         => '207.250.234.100',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* US */

        /* CA */
        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+1")->first()->id,
            'ip_register'       => '100.36.36.0',
            'email'             => 'utilisateur-1-Canada@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+10789842126',
            'recent_ip'         => '100.36.36.0',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'pays_register_id'  => Pays::where('indicatif', "+1")->first()->id,
            'ip_register'       => '103.140.3.2',
            'email'             => 'utilisateur-2-Canada@gmail.com',
            'email_verified_at' => now(),
            'telephone'         => '+10546358498',
            'recent_ip'         => '103.140.3.2',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token'    => Str::random(10),
        ]);
        /* CA */


        User::factory()->count(10)->create();
    }
}
