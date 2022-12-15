<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ManualPoDeliveryDetailsResource;
use App\Models\ManualPoDeliveryDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ManualPoDeliveryDetailsController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make( $request->all(),[
                //'name'          => ["required", "min:4"],
                //'description'   => ["nullable", "min:4"],
            ]);
                
            if ($validator->fails()) {    
                $this->apiOutput($this->getValidationError($validator), 400);
            }
   
            $manualpoDetails = new ManualPoDeliveryDetails();
            $manualpoDetails->ship_method = $request->ship_method;
            $manualpoDetails->inco_terms = $request->inco_terms;
            $manualpoDetails->landing_port = $request->landing_port;
            $manualpoDetails->discharge_port = $request->discharge_port;
            $manualpoDetails->country_of_origin = $request->country_of_origin;
            $manualpoDetails->ex_factor_date = $request->ex_factor_date;
            $manualpoDetails->care_label_date = $request->care_label_date;
            $manualpoDetails->save();
            $this->apiSuccess();
            $this->data = (new ManualPoDeliveryDetailsResource($manualpoDetails));
            return $this->apiOutput("Manual Po Added Successfully");

        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }
      
}
