<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends BaseModel
{
    //
    public $table = 'vehicles';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function register($vehicleData, $carFrontImage, $carBackImage, $carSideLeftImage, $carRightImage) {
        $vehicle = new Vehicle();
        $vehicle->user_id = $vehicleData['user_id'];
        $vehicle->vehicle_type = $vehicleData['vehicle_type'];
        $vehicle->plate_number = $vehicleData['plate_number'];
        $vehicle->brand = $vehicleData['brand'];
        $vehicle->year = $vehicleData['year'];
        $vehicle->model = $vehicleData['model'];
        $vehicle->color_description = $vehicleData['color_description'];
        $vehicle->car_front_image = $carFrontImage;
        $vehicle->car_back_image = $carBackImage;
        $vehicle->car_sideleft_image = $carSideLeftImage;
        $vehicle->car_sideright_image = $carRightImage;
        
        $response = $vehicle->save();
        if($response) {
            $this->setRetVal('vehicleId', $vehicle->id);
        }
        return $response;
    }
    
    
    public function getAll($where = []) {
        return Vehicle::where($where)->orderBy('id','desc')->get();
    }

}
