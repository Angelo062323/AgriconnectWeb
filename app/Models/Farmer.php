<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lgu_id',
        'rsbsa_number',
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'crop_type',
        'farm_location',
        'barangay',
        'municipality',
        'province',
        'latitude',
        'longitude',
    ];

    public function lgu()
    {
        return $this->belongsTo(Lgu::class);
    }
}
