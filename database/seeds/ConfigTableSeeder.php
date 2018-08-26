<?php

use Illuminate\Database\Seeder;


class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('config')->insert([
            ['name' => 'Web Title', 'ascii' => 'web-title', 'desc'=> 'Web Title', 'value' => 'Tài Liệu Y Học', 'created_by' => 1, 'changed_by' => 1, 'locked'=>0, 'created_at' => new DateTime(), 'changed_at' => new DateTime()]
        ]);

    }
}
