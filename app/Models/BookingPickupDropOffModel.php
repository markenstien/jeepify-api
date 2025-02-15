<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPickupDropOffModel extends Model
{
    //
    public $table = 'booking_pickup_dropoffs';

    public function addRecord($bookingId, $action, $status, $imageProof, $notes) {
        $recordExist = $this->where([
            'action' => $action,
            'booking_id' => $bookingId
        ])->first();

        if($recordExist) {
            $bookingDropOffModel = BookingPickupDropOffModel::find($recordExist->id);
        } else {
            $bookingDropOffModel = new BookingPickupDropOffModel();
        }
        $bookingDropOffModel->booking_id = $bookingId;
        $bookingDropOffModel->action = $action;
        $bookingDropOffModel->image_proof = $imageProof;
        $bookingDropOffModel->notes = $notes;
        $bookingDropOffModel->status = $status;
        $response = $bookingDropOffModel->save();

        return $bookingDropOffModel;
    }
}
