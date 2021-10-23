<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Note extends Model
{
    public $timestamps = false;
    // public $incrementing = false;
    protected $table = 'note';
    protected $primaryKey = 'note_id';
    protected $guarded = [];
    
}
