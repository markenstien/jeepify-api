<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    private $vehicleModel;
    public function __construct()
    {
        $this->vehicleModel = new Vehicle();
    }

    public function index(Request $request) {
        $validQueryNames = ['user_id', ''];
        $where = [];
        foreach($request->all() as $key => $val) {
            if(in_array($key, $validQueryNames)) {
                array_push($where, [
                    $key, '=', $val
                ]);
            }
        }

        return response()->json([
            'message' => 'list of vehicles',
            'status'   => 'ok',
            'data' => [
                'vehicles' => $this->vehicleModel->getAll($where)
            ]
        ]);
    }

    public function get($id) {
        return response()->json([
            'message' => 'list of vehicles',
            'stats'   => 'ok',
            'data' => [
                'vehicle' => $this->vehicleModel->find($id)
            ]
        ]);
    }

public function getUserVehicle($userId) {
        return response()->json([
            'message' => 'list of vehicles',
            'stats'   => 'ok',
            'data' => [
                'vehicle' => $this->vehicleModel->where([
                    'user_id' => $userId
                ])->first()
            ]
        ]);
    }
    //
    public function register(Request $request) {

        /**
         * registration check
         */
        if($request->hasFile('car_front_image') &&
        $request->hasFile('car_back_image') &&
        $request->hasFile('car_sideleft_image') &&
        $request->hasFile('car_sideright_image')
        ) {

            $carFrontImage = $this->imagePath($request, 'car_front_image');
            $carBackImage = $this->imagePath($request, 'car_back_image');
            $carSideLeftImage = $this->imagePath($request, 'car_sideleft_image');
            $carRightImage = $this->imagePath($request, 'car_sideright_image');

            $response = $this->vehicleModel->register($request->all(), $carFrontImage, $carBackImage, $carSideLeftImage, $carRightImage);
            return response()->json([
                'message' => 'Data Saved',
                'data'  => 'response data',
                'status' => 'success',
                'reqest' => Vehicle::find($this->vehicleModel->getRetVal('vehicleId'))
            ]);
        } else {
            return response()->json([
                'message' => 'something went wrong',
                'data'  => 'response data',
                'status' => 'error',
                'reqest' => $request->all()
            ]);
        }
    }
    

    private function imagePath($request, $imageName) {
        $retval = '';
        $imagePath = $request->file($imageName)->store('images', 'public');
           // Get the URL of the uploaded image
        $retval = asset('storage/' . $imagePath);
        return $retval;
    }
}
