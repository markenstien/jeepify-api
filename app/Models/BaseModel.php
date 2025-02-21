<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    //
    public $retVal = [];
    /**
     * save return value
     *  inside the model
     */
    protected function setRetVal($name, $value) {
        $this->retVal[$name] = $value;
    }

    public function getRetVal($name = null) {
        return is_null($name) ? $this->retVal : $this->retVal[$name];
    }

    public function printTest() {
        return 'test';
    }
}
