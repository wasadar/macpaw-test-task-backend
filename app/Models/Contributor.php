<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Collection;

class Contributor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'collection_id',
        'user_name',
        'amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'collection_id',
    ];

    public $timestamps = false;

    public function collection()
    {
        return $this->belongsTo(Collection::class); // Належить до одного збору
    }
}
