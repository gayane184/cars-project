<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(\App\Models\Model::class, 'mark_id', 'id');
    }
}
