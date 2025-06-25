<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Template::create([
            'name' => 'Test Template',
            'type' => 'sms',
            'content' => 'Test content',
            'description' => 'Test description',
            'is_default' => false
        ]);
    }
}
