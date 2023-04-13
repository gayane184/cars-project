<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['model_id', 'description', 'published'];

    public function model()
    {
        return $this->hasOne(\App\Models\Model::class, 'id', 'model_id');
    }

    /**
     * @return MorphOne
     */
    public function thumbnail(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('type', 'thumbnail');
    }
}
