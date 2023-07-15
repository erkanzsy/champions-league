<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'home_id',
        'away_id',
        'date',
        'term',
        'week',
        'home_score',
        'away_score',
        'home_rate',
        'away_rate',
        'winner',
    ];

    public function home()
    {
        return $this->belongsTo(Team::class, 'home_id', 'id');
    }

    public function away()
    {
        return $this->belongsTo(Team::class, 'away_id', 'id');
    }
}
