<?php

namespace App\Http\Controllers\Backend;

use Exception;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Events\AccountRegistration;
use App\Models\VendorContactPeople;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Backend\VendorContactPeopleResource;
use Illuminate\Support\Facades\DB;

class VendorContactPeopleController extends Controller
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
           $this->data = VendorContactPeopleResource::collection(VendorContactPeople::all());
           $this->apiSuccess("Vendor Contact People Load has been Successfully done");
           return $this->apiOutput();

       }catch(Exception $e){
           return $this->apiOutput($this->getError($e), 500);
       }
   }

   public function show(Request $request)
   {
       try{
           $vendor = VendorContactPeople::find($request->id);
           if( empty($vendor) ){
               return $this->apiOutput("Vendor Contact People Data Not Found", 400);
           }
           $this->data = (new VendorContactPeopleResource ($vendor));
           $this->apiSuccess("Vendor Contact People Detail Show Successfully");
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
                   "vendor_id"          => ["required"],
                   "employee_id"          => ["required"],
                   "email"                => ["required","email"],
                   "status"               => 'required',

               ],[
                   // "group_id.exists"     => "No Record found under this group",
               ]
              );

               if ($validator->fails()) {
                   return $this->apiOutput($this->getValidationError($validator), 400);
               }
               $vendor_contact_people = new VendorContactPeople();
               $vendor_contact_people->vendor_id = $request->vendor_id;
               $vendor_contact_people->employee_id = $request->employee_id;
               $vendor_contact_people->first_name = $request->first_name;
               $vendor_contact_people->last_name = $request->last_name;
               $vendor_contact_people->designation = $request->designation;
               $vendor_contact_people->department = $request->department;
               $vendor_contact_people->category = $request->category;
               $vendor_contact_people->phone = $request->phone;
               $vendor_contact_people->email = $request->email;
               $vendor_contact_people->remarks = $request->remarks;
               $vendor_contact_people->status = $request->status;
            //    $vendor_contact_people->created_by = $request->created_by;
            //    $vendor_contact_people->updated_by = $request->updated_by;
               $vendor_contact_people->created_at = $request->created_at;
               $vendor_contact_people->updated_at = $request->updated_at;
               $vendor_contact_people->deleted_by = $request->deleted_by;
               $vendor_contact_people->deleted_date = $request->deleted_date;
               $vendor_contact_people->save();

           try{
               event(new AccountRegistration($vendor_contact_people));
           }catch(Exception $e){

           }
           $this->apiSuccess("Vendor Contact People Added Successfully");
           $this->data = (new VendorContactPeopleResource($vendor_contact_people));
           return $this->apiOutput();

       }catch(Exception $e){
           return $this->apiOutput($this->getError( $e), 500);
       }
   }

   public function update(Request $request,$id)
   {
       try{
       $validator = Validator::make($request->all(),[
        "id"                   => ["required"],
        "vendor_id"            => ["required"],
        "employee_id"          => ["required"],
        "email"                => ["required","email"],
        "status"               => 'required',
       ],[
           // "id"                  => "No Data Found for this Id",
           // "group_id.exists"     => "No Record found under this group",
       ]
       );

          if ($validator->fails()) {
           $this->apiOutput($this->getValidationError($validator), 400);
          }

           $vendor_contact_people = VendorContactPeople::find($request->id);
           // if(empty($admin)){
           //     return $this->apiOutput("No Data Found", $admin);
           // }
           $vendor_contact_people->vendor_id = $request->vendor_id;
           $vendor_contact_people->employee_id = $request->employee_id;
           $vendor_contact_people->first_name = $request->first_name;
           $vendor_contact_people->last_name = $request->last_name;
           $vendor_contact_people->designation = $request->designation;
           $vendor_contact_people->department = $request->department;
           $vendor_contact_people->category = $request->category;
           $vendor_contact_people->phone = $request->phone;
           $vendor_contact_people->email = $request->email;
           $vendor_contact_people->remarks = $request->remarks;
           $vendor_contact_people->status = $request->status;
        //    $vendor_contact_people->created_by = $request->created_by;
        //    $vendor_contact_people->updated_by = $request->updated_by;
           $vendor_contact_people->created_at = $request->created_at;
           $vendor_contact_people->updated_at = $request->updated_at;
           $vendor_contact_people->deleted_by = $request->deleted_by;
           $vendor_contact_people->deleted_date = $request->deleted_date;

           $vendor_contact_people->save();
           $this->apiSuccess("Vendor Contact People Updated Successfully");
           $this->data = (new VendorContactPeopleResource($vendor_contact_people));
           return $this->apiOutput();
       }catch(Exception $e){
           return $this->apiOutput($this->getError( $e), 500);
       }
   }

   public function destroy(Request $request,$id)
   {
       $vendor_contact_people = VendorContactPeople::find($request->id);
       $vendor_contact_people->delete();
       $this->apiSuccess();
       return $this->apiOutput("Vendor Contact People Deleted Successfully", 200);
   }

   /**
    * Forget Password
    */
   public function forgetPassword(Request $request){
       try{
           $validator = Validator::make($request->all(), [
               "email"     => ["required", "exists:admins,email"],
           ],[
               "email.exists"  => "No Record found under this email",
           ]);

           if($validator->fails()){
               return $this->apiOutput($this->getValidationError($validator), 400);
           }
           $admin = VendorContactPeople::where("email", $request->email)->first();
           $password_reset = PasswordReset::where("tableable", $admin->getMorphClass())
               ->where("tableable_id", $admin->id)->where("is_used", false)
               ->where("expire_at", ">=", now()->format('Y-m-d H:i:s'))
               ->orderBy("id", "DESC")->first();
           if( empty($password_reset) ){
               $token = rand(111111, 999999);
               $password_reset = new PasswordReset();
               $password_reset->tableable      = $admin->getMorphClass();
               $password_reset->tableable_id   = $admin->id;
               $password_reset->email          = $admin->email;
               $password_reset->token          = $token;
           }
           $password_reset->expire_at      = now()->addHour();
           $password_reset->save();

           // Send Password Reset Email
           // event(new PasswordResetEvent($password_reset));

           $this->apiSuccess("Password Reset Code sent to your registared Email.");
           return $this->apiOutput();
       }catch(Exception $e){
           return $this->apiOutput($this->getError($e), 500);
       }
   }

   /**
    * Password Reset
    */
   public function passwordReset(Request $request){
       try{
           $validator = Validator::make($request->all(), [
               "email"     => ["required", "exists:admins,email"],
               "code"      => ["required", "exists:password_resets,token"],
               "password"  => ["required", "string"],
           ],[
               "email.exists"  => "No Record found under this email",
               "code.exists"   => "Invalid Verification Code",
           ]);
           if($validator->fails()){
               return $this->apiOutput($this->getValidationError($validator), 400);
           }

           DB::beginTransaction();
           $password_reset = PasswordReset::where("email", $request->email)
               ->where("is_used", false)
               ->where("expire_at", ">=", now()->format('Y-m-d H:i:s'))
               ->first();
           if( empty($password_reset) ){
               return $this->apiOutput($this->getValidationError($validator), 400);
           }
           $password_reset->is_used = true;
           $password_reset->save();

           $user = $password_reset->user;
           $user->password = bcrypt($request->password);
           $user->save();

           DB::commit();
           try{
               event(new PasswordReset($password_reset, true));
           }catch(Exception $e){

           }
           $this->apiSuccess("Password Reset Successfully.");
           return $this->apiOutput();
       }catch(Exception $e){
           return $this->apiOutput($this->getError($e), 500);
       }
   }
}
