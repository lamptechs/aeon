<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\ComplianceAudit;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ComplianceAuditResource;
use App\Models\Compliance;
use App\Models\ComplianceAuditUpload;
use Illuminate\Support\Facades\DB;

class ComplianceAuditController extends Controller
{

    public function index(){
        try{
                
                $this->data = ComplianceAuditResource::collection(Compliance::all());
                $this->apiSuccess("Compilance Audit Loaded Successfully");
                return $this->apiOutput();
    
            }catch(Exception $e){
                return $this->apiOutput($this->getError($e), 500);
            }
    }

    /*
    Store
    */

    public function store(Request $request){
       
        try{
            $validator = Validator::make( $request->all(),[
                //'name'          => ["required", "min:4"],
                //'description'   => ["nullable", "min:4"],
            ]);
                
            if ($validator->fails()) {    
                $this->apiOutput($this->getValidationError($validator), 400);
            }
            DB::beginTransaction();
   
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
            $this->saveFileInfo($request, $complianceAudit);
            DB::commit();
            $this->apiSuccess();
            $this->data = (new ComplianceAuditResource($complianceAudit ));
            return $this->apiOutput("Compilance Audit Added Successfully");

        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }

    // Save File Info
    public function saveFileInfo($request, $complianceAudit){
        $file_path = $this->uploadFile($request, 'file', $this->compliance_uploads, 720);
  
        if( !is_array($file_path) ){
            $file_path = (array) $file_path;
        }
        foreach($file_path as $path){
            $data = new ComplianceAuditUpload();
            $data->complianceaudit_id = $complianceAudit->id;
            $data->file_name    = $request->file_name ?? "Compliance Upload";
            $data->file_url     = $path;
            $data->save();
            return;
        }
    }

     /*
    Show
    */
    public function show(Request $request)
    {
        try{
            
            $complianceaudit = Compliance::find($request->id);
            $this->data = (new ComplianceAuditResource($complianceaudit));
            $this->apiSuccess("ComplianceAudit Showed Successfully");
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }


     /*
       Update
    */
    public function update(Request $request)
    {
         try{
            $validator = Validator::make($request->all(),[
                // 'id'            => 'required',
                // 'name'          => ["required", "min:4"],
                // 'description'   => ["nullable", "min:4"],
            ]);
            
            if ($validator->fails()) {    
                return $this->apiOutput($this->getValidationError($validator), 400);
            }
   
            $complianceAudit = Compliance::find($request->id);
            // if(empty($compliance)){
            //     return $this->apiOutput("No Data Found", $complianceAudit);
            // }
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
            //$group->created_by = $request->user()->id ;
            //$group->created_at = Carbon::Now();
            //$complianceAudit->save();
            $this->apiSuccess();
            $this->data = (new ComplianceAuditResource($complianceAudit));
            return $this->apiOutput("Compliance Audit Updated Successfully");
        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }


     /*
       Delete
    */
    public function destroy(Request $request)
    {
        Compliance::where("id", $request->id)->delete();
        $this->apiSuccess();
        return $this->apiOutput("ComplianceAudit Deleted Successfully", 200);
    }

}
