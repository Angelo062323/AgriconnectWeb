<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'farmer_id',
        'lgu_id',
        'da_id',
        'request_type',
        'description',
        'priority',
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
