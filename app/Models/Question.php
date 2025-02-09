<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'options',
        'author',
        'difficulty',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Accessor to decode JSON when retrieving from database
    public function getOptionsAttribute($value)
    {
        return json_decode($value, true);
    }

    // Mutator to encode JSON when saving to database
    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }
}