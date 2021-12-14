<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscribe;

class Groups extends Model
{
    use HasFactory;

    protected $fillable = ['name',];
    protected $appends = ['subscribers', 'difference_point'];
    

    public function subscribers()
    {
        return $this->hasMany(Subscribe::class);
    }
    public function getSubscribersAttribute()
    {
        $model = Subscribe::where('group_id', '=', $this->id)->orderBy('created_at', 'desc')->first();
        return $model;
    }
    //Список участников на разный промежуток времени
    public function getDifferencePointAttribute()
    {
        $last_count = Subscribe::where('group_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(0)->take(2)->get()->first()->count_subscriber ?? 0;
        $previous_count = Subscribe::where('group_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(1)->take(2)->get()->first()->count_subscriber ?? 0;
        $count = $last_count - $previous_count;
        return $count;
    }   
}
