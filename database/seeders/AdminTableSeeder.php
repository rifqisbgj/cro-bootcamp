<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@crocamp.com',
            'email_verified_at' => date('Y-m-d H:i:s', time()),
            'password' => bcrypt('adminla'),
            'is_admin' => true,
        ]);
    }
}
