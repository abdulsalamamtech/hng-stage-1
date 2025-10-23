<?php

namespace Database\Seeders;

use App\Models\StringAnalyzer;
use App\Services\StringAnalyzerService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Inspiring;

class StringAnalyzerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data to seed the string_analyzers table
        $sampleData = ['A journey of a thousand miles begins with a single step.', 'PHP', 'race-car', 'hello world', 'madam', 'Laravel', 'PHP is awesome', '12321', 'OpenAI', 'A man a plan a canal Panama', 'Not a palindrome', 'Level'];

        $stringAnalyzer = new StringAnalyzerService();
        foreach ($sampleData as $value) {
            $stringAnalyzer->analyzeAndStore((string) $value);
        }
    }

}
