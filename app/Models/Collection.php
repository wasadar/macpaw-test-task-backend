<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contributor;

class Collection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'target_amount',
        'link',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
    ];

    // public $timestamps = ['created_at'];
    public $timestamps = false;

    public function contributors()
    {
        return $this->hasMany(Contributor::class); // Має багато вкладів
    }
}
