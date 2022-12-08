<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\ComplianceAudit;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ComplianceAuditResource;
use App\Models\Compliance;

class ComplianceAuditController extends Controller
{
    public function index(){

    }
    public function store(Request $request){
       
        try{
            $validator = Validator::make( $request->all(),[
                //'name'          => ["required", "min:4"],
                //'description'   => ["nullable", "min:4"],
            ]);
                
            if ($validator->fails()) {    
                $this->apiOutput($this->getValidationError($validator), 400);
            }
   
            $complianceAudit = new Compliance();
            $complianceAudit->factory_name = $request->factory_name;
            $complianceAudit->factory_concern_person_name = $request->factory_concern_person_name;
            $complianceAudit->status = $request->status;
            $complianceAudit->email = $request->email;
            $complianceAudit->phone = $request->phone;
            $complianceAudit->audit_name = $request->audit_name;
            $complianceAudit->vendor_name = $request->vendor_name;
            $complianceAudit->audit_conducted_by = $request->audit_conducted_by;
            $complianceAudit->audit_request_date = $request->audit_request_date;
            $complianceAudit->requirement_date = $request->requirement_date;
            $complianceAudit->requirement_details = $request->requirement_details;
            $complianceAudit->note_remarks = $request->note_remarks;
            $complianceAudit->save();
            $this->apiSuccess();
            $this->data = (new ComplianceAuditResource($complianceAudit ));
            return $this->apiOutput("Compilance Audit Added Successfully");

        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }
}
