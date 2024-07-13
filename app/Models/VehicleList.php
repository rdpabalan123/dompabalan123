<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleList extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = ['tracker_id','tracker_label','label'];

    public $timestamps = true;
    
}
