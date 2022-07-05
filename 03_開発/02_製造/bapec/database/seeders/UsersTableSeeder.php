<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    public function run() {
        DB::tabel('m_user')->insert([
            'email'=> 'admin@email.jp',
            'email_verified_at' => now(),
            'name' => 'システム　管理者',
            'password' =>Hash::make('system'),
            'remember_token'=>Str::random(10),
            'menuroles' => 'user,admin',
        ]);
    }
}