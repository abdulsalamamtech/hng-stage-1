<?php

namespace App\Services;

use App\Models\StringAnalyzer;
use Exception;
use Illuminate\Support\Str;

class StringAnalyzerService
{
 

    public static function analyzeAndStore(string $value)
    {
        info('crating new string analyzer!');
        $stringAnalyzer = StringAnalyzer::where('value', $value)->first();
        if($stringAnalyzer){
            abort(403, 'string value already exist!');
        }
        $id = hash('sha256', $value);
        return StringAnalyzer::create([
            'id' => $id,
            // id using sha256 hash of value
            // 'id' => hash('sha256', $value . time(), true),
            'value' => $value,
            'word_count' => Str::wordCount($value),
            'is_palindrome' => $value === Str::reverse($value),
            'length' => Str::length($value),
        ]);
    }


    public static function getProperties(StringAnalyzer $stringAnalyzer)
    {
        return  [
            'length' => $stringAnalyzer->length ?? Str::length($stringAnalyzer->value),
            'is_palindrome' => $stringAnalyzer->is_palindrome,
            'unique_char_count' => count(array_unique(str_split($stringAnalyzer->value))),
            'word_count' => $stringAnalyzer->word_count,
            'sha256_hash' => hash('sha256', $stringAnalyzer->value),
            'character_frequency_map' => array_count_values(str_split($stringAnalyzer->value))
        ];
    }

}