<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class BookingController extends Controller
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
    }
    //
    public function index() {
        $items = $this->bookingModel->getAll();
        return response()->json($items);
    }

    public function store(Request $request) {

        $booking = new Booking();
        $tip = $request->get('tip') ?? 0;
        $discount = $request->get('discount') ?? 0;
        $initialCost = $request->get('initial_cost') ?? 0;

        $booking->booking_reference_number = str()->random(8);
        $booking->pick_up_details = json_encode($request->get('pick_up_details'));
        $booking->drop_off_details = json_encode($request->get('drop_off_details'));
        $booking->vehicle_type = $request->get('vehicle_type');
        
        $booking->estimated_arrival_time = $request->get('estimated_arrival_time') ?? '17:30';
        $booking->estimated_travel_time = $request->get('estimated_travel_time') ?? '30mins';
        $booking->distance = $request->get('distance');
        $booking->tip = $tip;
        $booking->discount = $discount;
        $booking->initial_cost = $initialCost;

        //net cost computation
        $booking->net_cost = ($initialCost - $discount) + $tip;
        // $booking->booking_date = $request->get('booking_date');
        // $booking->customer_id = $request->get('customer_id');
        // $booking->driver_id = $request->get('driver_id');
        // $booking->booking_status = $request->get('booking_status');
        $booking->save();

        if($booking->id) {
            return response()->json([
                'data' => [
                    'booking' => Booking::find($booking->id)
                ],
                'status' => 'success',
                'message'  => 'booking created!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'something wrong with your booking'
            ]);
        }
    }

    public function get($id) {
        return response()->json([
            'data' => [
                'booking' => Booking::find($id)
            ]
        ]);
    }

    public function accept(Request $request) {
        if(empty($request->get('driver_id')) || empty($request->get('booking_id'))) {
            return response()->json([
                'message' => 'driver_id and booking_id must be set',
                'status'  => 'error'
            ]);
        }

        $booking = Booking::find($request->get('booking_id'));

        if(!$booking) {
            return response()->json([
                'message' => 'Booking not found',
                'status'  => 'error'
            ]);
        }
        
        if($booking->driver_id != null) {
            return response()->json([
                'message' => 'Booking already have a driver',
                'status'  => 'error'
            ]);
        }

        $booking->driver_id = $request->get('driver_id');
        $booking->booking_status = 'in-progress';
        $booking->save();

        return response()->json([
            'message' => 'Booking Accepted',
            'status'  => 'error',
            'data'    => [
                'driver' => $request->get('driver_id')
            ]
        ]); 
    }
}
