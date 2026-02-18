<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lgu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lgu_name',
        'municipality',
        'province',
        'contact_email',
    ];

    public function farmers()
    {
        return $this->hasMany(Farmer::class);
    }
}
