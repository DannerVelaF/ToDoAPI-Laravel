<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'Titulo',
        'Descripcion',
        'Completada',
        'user_id',
    ];

    public function casts()
    {
        return [
            'Completada' => 'boolean',
        ];
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
