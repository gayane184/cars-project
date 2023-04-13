<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    /**
     * The appended attributes
     *
     * @var array
     */
    protected $appends = ['fullPath'];

    public function getFullPathAttribute(): string
    {
        return asset('storage/' . $this['path']);
    }
}
