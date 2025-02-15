<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPickupDropOffModel;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class BookingController extends Controller
{
    private $bookingModel;
    private $bookingDropOffModel;
    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->bookingDropOffModel = new BookingPickupDropOffModel();
    }

    public function getPickupDropOff(Request $request) {
        if(empty($request->get('booking_id')) || empty($request->get('action'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking ID and Action must not be empty'
            ]);
        }

        $detail = BookingPickupDropOffModel::where([
            'booking_id' => $request->get('booking_id'),
            'action'     => $request->get('action')
        ])->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'detail' => $detail,
                'payload' => [
                    $request->get('booking_id'),
                    $request->get('action')
                ]
            ]
        ]);
    }

    public function pickupDropOff(Request $request) { 

        if(!empty($request->get('booking_id')) && $request->hasFile('image')) {
           // Store the image in the 'public' disk
           $imagePath = $request->file('image')->store('images');
           // Get the URL of the uploaded image
           $imageUrl = asset('storage/' . $imagePath);

           $bookingDropOffModel = $this->bookingDropOffModel->addRecord(...[
               $request->get('booking_id'),
               $request->get('action'),
               $request->get('status'),
               $imageUrl,
               $request->get('notes') ?? 'test notes'
           ]);

           return response()->json([
               'status' => 'success',
               'message' => 'data upload',
               'data' => [
                   'booking-id' => $bookingDropOffModel->id    
               ]
           ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking and Image are both required'
            ]);
        }
    }
    //
    public function index(Request $request) {
        $validQueryNames = [
            'booking_status', 'customer_id',
            'driver_id'
        ];
        $where = [];
        foreach($request->all() as $key => $val) {
            if(in_array($key, $validQueryNames)) {
                array_push($where, [
                    $key, '=', $val
                ]);
            }
        }
        $items = $this->bookingModel->getAll($where);
        return response()->json($items);
    }

    public function store(Request $request) {

        $booking = new Booking();
        $tip = $request->get('tip') ?? 0;
        $discount = $request->get('discount') ?? 0;
        $initialCost = $request->get('initial_cost') ?? 0;

        $booking->customer_id = $request->get('customer_id');
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
        $booking->notes = $request->get('notes');
        $booking->parcel_description = $request->get('parcel_description');

        //net cost computation
        $booking->net_cost = ($initialCost - $discount) + $tip;
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
                'booking' => $this->bookingModel->get($id   )
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
        
        // if($booking->driver_id != null) {
        //     return response()->json([
        //         'message' => 'Booking already have a driver',
        //         'status'  => 'error'
        //     ]);
        // }

        $booking->driver_id = $request->get('driver_id');
        $booking->booking_status = 'in-progress';
        $booking->save();

        return response()->json([
            'message' => 'Booking Accepted',
            'status'  => 'error',
            'data'    => [
                'driver' => $request->get('driver_id'),
                'booking' => $booking
            ]
        ]); 
    }
}
