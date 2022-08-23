<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class VideoFormat extends Model
{
    use Notifiable;

    protected $table = 'video_format';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'p144', 'p240', 'p360', 'p480', 'p720', 'p1080', 'video_id'
    ];
}
