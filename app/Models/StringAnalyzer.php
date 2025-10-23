<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StringAnalyzer extends Model
{

    protected $table = 'string_analyzers';


    protected $fillable = [
        'id',
        'value',
        'is_palindrome',
        'word_count',
        'length',
    ];

    protected $casts = [
        'id' => 'string',
        'value' => 'string',
        'is_palindrome' => 'boolean',
        'word_count' => 'integer',
        'length' => 'integer',
    ];

    public function getPropertiesAttribute()
    {
        return [
            'length' => $this->length,
            'is_palindrome' => $this->is_palindrome,
            'unique_char_count' => count(array_unique(str_split($this->value))),
            'word_count' => $this->word_count,
            'sha256_hash' => hash('sha256', $this->value),
            'character_frequency_map' => array_count_values(str_split($this->value))
        ];
    }
}