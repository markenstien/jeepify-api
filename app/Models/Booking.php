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

    public function getAll($where = []) {
        
        $query = Booking::with('bookingPickUpDropOff');
        if(!empty($where)) {
            $query->where($where);
        }
        $bookings = $query->orderBy('id','desc')->get();
        return $bookings;
    }

    public function get($id) {
        $query = Booking::with('bookingPickUpDropOff');
        return $query->find($id);
    }

    public function bookingPickUpDropOff() {
        return $this->hasMany(BookingPickupDropOffModel::class, 'booking_id', 'id');
    }
}
