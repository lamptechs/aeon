<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Events\AccountRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Backend\CertificateResource;
use App\Models\VendorCertificate;

class CertificateController extends Controller
{
          /**
    * Show Login
    */
    public function showLogin(Request $request){
        $this->data = [
            "email"     => "required",
            "password"  => "required",
        ];
        $this->apiSuccess("This credentials are required for Login ");
        return $this->apiOutput(200);
    }

    /**
     * Login
     */
    // public function login(Request $request){

    //     $admin=Admin::all();
    //     try{
    //         $validator = Validator::make($request->all(), [
    //             "email"     => ["required", "email", "exists:admins,email"],
    //             "password"  => ["required", "string", "min:4", "max:40"]
    //         ]);
    //         if($validator->fails()){
    //             return $this->apiOutput($this->getValidationError($validator), 400);
    //         }
    //         $admin =Admin::where("email", $request->email)->first();
    //         if( !Hash::check($request->password, $admin->password) ){
    //             return $this->apiOutput("Sorry! Password Dosen't Match", 401);
    //         }
    //         if( !$admin->status ){
    //             return $this->apiOutput("Sorry! your account is temporaly blocked", 401);
    //         }

    //         // Issueing Access Token
    //         $this->access_token = $admin->createToken($request->ip() ?? "admin_access_token")->plainTextToken;

    //         Session::put('access_token',$this->access_token);
    //         // echo Session::get('access_token');
    //         $this->apiSuccess("Login Successfully");
    //         // Flash Admin Group Permission
    //         //Session::forget("group_access");

    //         $this->data = (new AdminResource($admin));
    //         return $this->apiOutput();

    //     }catch(Exception $e){
    //         return $this->apiOutput($this->getError($e), 500);
    //     }
    // }
    // public function logout(Request $request){
    //     $user = $request->user();
    //     $user->tokens()->delete();
    //     $this->apiSuccess("Logout Successfull");
    //     return $this->apiOutput();
    // }

    public function index()
    {
        try{
            $this->data = CertificateResource::collection(VendorCertificate::all());
            $this->apiSuccess("Certificate Load has been Successfully done");
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }

    public function show(Request $request)
    {
        try{
            $certificate = VendorCertificate::find($request->id);
            if( empty($certificate) ){
                return $this->apiOutput("Customer Department Data Not Found", 400);
            }
            $this->data = (new CertificateResource ($certificate));
            $this->apiSuccess("certificate Detail Show Successfully");
            return $this->apiOutput();
        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }

    public function store(Request $request)
    {

        try{
            $validator = Validator::make(
                $request->all(),
                [
                    "vendor_id"                 => ["required"],
                    "global_certificate_id"     => ["required"],
                    "status"                    => 'required',

                ],[
                    // "group_id.exists"     => "No Record found under this group",
                ]
               );

                if ($validator->fails()) {
                    return $this->apiOutput($this->getValidationError($validator), 400);
                }
                $certificate = new VendorCertificate();

                $certificate->vendor_id             = $request->vendor_id;
                $certificate->global_certificate_id = $request->global_certificate_id;
                $certificate->issue_date            = $request->issue_date;
                $certificate->validity_start_date   = $request->validity_start_date;
                $certificate->validity_end_date     = $request->validity_end_date;
                $certificate->renewal_date          = $request->renewal_date;
                $certificate->attachment            = $request->attachment;
                $certificate->score                 = $request->score;

                $certificate->remarks               = $request->remarks;
                $certificate->status                = $request->status;
             //    $customer->created_by = $request->created_by;
             //    $customer->updated_by = $request->updated_by;
                $certificate->created_at            = $request->created_at;
                $certificate->updated_at            = $request->updated_at;
                $certificate->deleted_by            = $request->deleted_by;
                $certificate->deleted_date          = $request->deleted_date;

                $certificate->save();

            try{
                event(new AccountRegistration($certificate));
            }catch(Exception $e){

            }
            $this->apiSuccess("Certificate Added Successfully");
            $this->data = (new CertificateResource($certificate));
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }

    public function update(Request $request,$id)
    {
        try{
        $validator = Validator::make($request->all(),[
            "vendor_id"                 => ["required"],
            "global_certificate_id"     => ["required"],
            "status"                    => 'required',
        ],[
            // "id"                  => "No Data Found for this Id",
            // "group_id.exists"     => "No Record found under this group",
        ]
        );

           if ($validator->fails()) {
            $this->apiOutput($this->getValidationError($validator), 400);
           }

            $certificate = VendorCertificate::find($request->id);
            // if(empty($admin)){
            //     return $this->apiOutput("No Data Found", $admin);
            // }

            $certificate->vendor_id             = $request->vendor_id;
            $certificate->global_certificate_id = $request->global_certificate_id;
            $certificate->issue_date            = $request->issue_date;
            $certificate->validity_start_date   = $request->validity_start_date;
            $certificate->validity_end_date     = $request->validity_end_date;
            $certificate->renewal_date          = $request->renewal_date;
            $certificate->attachment            = $request->attachment;
            $certificate->score                 = $request->score;

            $certificate->remarks               = $request->remarks;
            $certificate->status                = $request->status;
         //    $customer->created_by = $request->created_by;
         //    $customer->updated_by = $request->updated_by;
            $certificate->created_at            = $request->created_at;
            $certificate->updated_at            = $request->updated_at;
            $certificate->deleted_by            = $request->deleted_by;
            $certificate->deleted_date          = $request->deleted_date;

            $certificate->save();

            $this->apiSuccess("Certificate Updated Successfully");

            $this->data = (new CertificateResource($certificate));
            return $this->apiOutput();
        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }

    public function destroy(Request $request,$id)
    {
        $certificate = VendorCertificate::find($request->id);
        $certificate->delete();
        $this->apiSuccess();
        return $this->apiOutput("Certificate Deleted Successfully", 200);
    }

    /**
     * Forget Password
     */
    // public function forgetPassword(Request $request){
    //     try{
    //         $validator = Validator::make($request->all(), [
    //             "email"     => ["required", "exists:admins,email"],
    //         ],[
    //             "email.exists"  => "No Record found under this email",
    //         ]);

    //         if($validator->fails()){
    //             return $this->apiOutput($this->getValidationError($validator), 400);
    //         }
    //         $admin = CustomerDepartment::where("email", $request->email)->first();
    //         $password_reset = PasswordReset::where("tableable", $admin->getMorphClass())
    //             ->where("tableable_id", $admin->id)->where("is_used", false)
    //             ->where("expire_at", ">=", now()->format('Y-m-d H:i:s'))
    //             ->orderBy("id", "DESC")->first();
    //         if( empty($password_reset) ){
    //             $token = rand(111111, 999999);
    //             $password_reset = new PasswordReset();
    //             $password_reset->tableable      = $admin->getMorphClass();
    //             $password_reset->tableable_id   = $admin->id;
    //             $password_reset->email          = $admin->email;
    //             $password_reset->token          = $token;
    //         }
    //         $password_reset->expire_at      = now()->addHour();
    //         $password_reset->save();

    //         // Send Password Reset Email
    //         // event(new PasswordResetEvent($password_reset));

    //         $this->apiSuccess("Password Reset Code sent to your registared Email.");
    //         return $this->apiOutput();
    //     }catch(Exception $e){
    //         return $this->apiOutput($this->getError($e), 500);
    //     }
    // }

    /**
     * Password Reset
     */
    // public function passwordReset(Request $request){
    //     try{
    //         $validator = Validator::make($request->all(), [
    //             "email"     => ["required", "exists:admins,email"],
    //             "code"      => ["required", "exists:password_resets,token"],
    //             "password"  => ["required", "string"],
    //         ],[
    //             "email.exists"  => "No Record found under this email",
    //             "code.exists"   => "Invalid Verification Code",
    //         ]);
    //         if($validator->fails()){
    //             return $this->apiOutput($this->getValidationError($validator), 400);
    //         }

    //         DB::beginTransaction();
    //         $password_reset = PasswordReset::where("email", $request->email)
    //             ->where("is_used", false)
    //             ->where("expire_at", ">=", now()->format('Y-m-d H:i:s'))
    //             ->first();
    //         if( empty($password_reset) ){
    //             return $this->apiOutput($this->getValidationError($validator), 400);
    //         }
    //         $password_reset->is_used = true;
    //         $password_reset->save();

    //         $user = $password_reset->user;
    //         $user->password = bcrypt($request->password);
    //         $user->save();

    //         DB::commit();
    //         try{
    //             event(new PasswordReset($password_reset, true));
    //         }catch(Exception $e){

    //         }
    //         $this->apiSuccess("Password Reset Successfully.");
    //         return $this->apiOutput();
    //     }catch(Exception $e){
    //         return $this->apiOutput($this->getError($e), 500);
    //     }
    // }
}
