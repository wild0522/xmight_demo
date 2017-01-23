<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Items extends Model
{
    protected $fillable = ['channels_id', 'name', 'value'];
    public $timestamps = false;

    public function channels()
    {
        return $this->belongsTo(Channels::class);
    }
}
