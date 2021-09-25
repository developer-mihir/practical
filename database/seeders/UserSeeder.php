<?php

namespace Database\Seeders;

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
        $user = new \App\Models\User();

        $user->truncate();
        $user->create([
            'firstname' => 'Jone',
            'lastname' => 'doe',
            'email' => 'jone.doe@gmail.com',
            'password' => bcrypt(123456789),
            'email_verified_at' => \Carbon\Carbon::now()
        ]);

        $user->create([
            'firstname' => 'Chris',
            'lastname' => 'Anthemum',
            'email' => 'chris.anthemum@gmail.com',
            'password' => bcrypt(123456789),
            'email_verified_at' => \Carbon\Carbon::now()
        ]);
    }
}
