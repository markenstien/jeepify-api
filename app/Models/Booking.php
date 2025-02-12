<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    public $table = 'bookings';
    protected $guarded = [
        'id'
    ];

    public function getAll() {
        $bookings = Booking::orderBy('id','desc')->get();
        return $bookings;
    }
}
