<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoogleUserinfoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('google_userinfo')->delete();
        
        DB::table('google_userinfo')->insert(array (
            0 => 
            array (
                'id' => 1,
                'gid' => '118313210547672468881',
                'email' => 'jayveeinfinity@gmail.com',
                'givenName' => 'Jayvee',
                'familyName' => 'Infinity',
                'name' => 'Jayvee Infinity',
                'picture' => 'https://lh3.googleusercontent.com/a/ACg8ocJQfOLJw5JrAFvowWcjCx_Wo2OFsLKrVM35wXeaebKywfc=s96-c',
                'verifiedEmail' => 1,
                'hd' => NULL,
                'created_at' => '2024-03-20 00:31:14',
                'updated_at' => '2024-03-20 01:19:16',
            ),
            1 => 
            array (
                'id' => 2,
                'gid' => '109936788382192850270',
                'email' => 'johnvincent.bonza@cvsu.edu.ph',
                'givenName' => 'John Vincent ',
                'familyName' => 'Bonza',
                'name' => 'John Vincent Bonza',
                'picture' => 'https://lh3.googleusercontent.com/a/ACg8ocIXf2R6MiPPg1k1zOlJyd4lQojMY80momUjobYGAlw7zw=s96-c',
                'verifiedEmail' => 1,
                'hd' => 'cvsu.edu.ph',
                'created_at' => '2024-03-20 00:31:30',
                'updated_at' => '2024-03-25 02:00:17',
            ),
        ));
        
        
    }
}