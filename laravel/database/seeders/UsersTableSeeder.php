<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('users')->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'John Vincent Bonza',
                'email' => 'johnvincent.bonza@cvsu.edu.ph',
                'email_verified_at' => NULL,
                'password' => '$2y$10$auIoeXiy7sg1OAVEVeNFkeeStTZmb6nfOFW7aV/M5EZIUziJUOI9q',
                'remember_token' => NULL,
                'created_at' => '2024-03-20 00:35:26',
                'updated_at' => '2024-03-20 00:35:26',
            ),
        ));
        
        
    }
}