<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'farmer_id',
        'lgu_id',
        'da_id',
        'report_type',
        'severity',
        'description',
        'status',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function lgu()
    {
        return $this->belongsTo(Lgu::class);
    }
}
