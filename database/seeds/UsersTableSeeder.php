<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            ['id'=> 1, 'name' => 'admin', 'email' => 'tailieuyhoc@gmail.com', 'password'=>md5(123456), 'level' => 3, 'created_at' => new DateTime(), 'updated_at' => new DateTime()]
        ]);
    }
}
