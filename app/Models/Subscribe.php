<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Subscribe extends Model
{
    use HasFactory;

    protected $table = 'subscribe';

    protected $fillable = ['group_id', 'count_subscriber'];

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
}
