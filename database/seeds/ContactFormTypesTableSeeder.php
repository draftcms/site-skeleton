<?php

use Illuminate\Database\Seeder;

class ContactFormTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_form_types')->insert([
            'id' => 1,
            'friendly_name' => 'I have a question',
            'recipients' => 'don@hustleworks.com',
        ]);

        DB::table('contact_form_types')->insert([
            'id' => 2,
            'friendly_name' => 'Website issue',
            'recipients' => 'don@hustleworks.com',
        ]);
    }
}
