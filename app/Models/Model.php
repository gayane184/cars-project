<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['mark_id', 'name'];

    public function mark()
    {
        return $this->hasOne(Mark::class, 'id', 'mark_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'model_id', 'id');
    }
}
