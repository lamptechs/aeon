<?php

namespace App\Http\Controllers\Backend;

use Exception;
use Illuminate\Http\Request;
use App\Models\GlobalCertificate;
use App\Events\AccountRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Backend\GlobalCertificateResource;

class GlobalCertificateController extends Controller
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
      $this->data = GlobalCertificateResource::collection(GlobalCertificate::all());
      $this->apiSuccess(" Global Certificate Load has been Successfully done");
      return $this->apiOutput();

  }catch(Exception $e){
      return $this->apiOutput($this->getError($e), 500);
  }
}

public function show(Request $request)
{
  try{
      $global_certificate = GlobalCertificate::find($request->id);
      if( empty($global_certificate) ){
          return $this->apiOutput("Customer Department Data Not Found", 400);
      }
      $this->data = (new GlobalCertificateResource ($global_certificate));
      $this->apiSuccess(" Global Certificate Detail Show Successfully");
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
              "name"                      => ["required"],
              "status"                    => 'required',

          ],[
              // "group_id.exists"     => "No Record found under this group",
          ]
         );

          if ($validator->fails()) {
              return $this->apiOutput($this->getValidationError($validator), 400);
          }
          $global_certificate = new GlobalCertificate();

          $global_certificate->name                  = $request->name;
          $global_certificate->logo                  = $request->logo;
          $global_certificate->details               = $request->details;


          $global_certificate->remarks               = $request->remarks;
          $global_certificate->status                = $request->status;
       //    $customer->created_by = $request->created_by;
       //    $customer->updated_by = $request->updated_by;
          $global_certificate->created_at            = $request->created_at;
          $global_certificate->updated_at            = $request->updated_at;
          $global_certificate->deleted_by            = $request->deleted_by;
          $global_certificate->deleted_date          = $request->deleted_date;

          $global_certificate->save();

      try{
          event(new AccountRegistration($global_certificate));
      }catch(Exception $e){

      }
      $this->apiSuccess(" Global Certificate Added Successfully");
      $this->data = (new GlobalCertificateResource($global_certificate));
      return $this->apiOutput();

  }catch(Exception $e){
      return $this->apiOutput($this->getError( $e), 500);
  }
}

public function update(Request $request,$id)
{
  try{
  $validator = Validator::make($request->all(),[
    "name"                      => ["required"],
    "status"                    => 'required',
  ],[
      // "id"                  => "No Data Found for this Id",
      // "group_id.exists"     => "No Record found under this group",
  ]
  );

     if ($validator->fails()) {
      $this->apiOutput($this->getValidationError($validator), 400);
     }

      $global_certificate = GlobalCertificate::find($request->id);
      // if(empty($admin)){
      //     return $this->apiOutput("No Data Found", $admin);
      // }

      $global_certificate->name                  = $request->name;
      $global_certificate->logo                  = $request->logo;
      $global_certificate->details               = $request->details;


      $global_certificate->remarks               = $request->remarks;
      $global_certificate->status                = $request->status;
   //    $customer->created_by = $request->created_by;
   //    $customer->updated_by = $request->updated_by;
      $global_certificate->created_at            = $request->created_at;
      $global_certificate->updated_at            = $request->updated_at;
      $global_certificate->deleted_by            = $request->deleted_by;
      $global_certificate->deleted_date          = $request->deleted_date;

      $global_certificate->save();

      $this->apiSuccess(" Global Certificate Updated Successfully");

      $this->data = (new GlobalCertificateResource($global_certificate));
      return $this->apiOutput();
  }catch(Exception $e){
      return $this->apiOutput($this->getError( $e), 500);
  }
}

public function destroy(Request $request,$id)
{
  $global_certificate = GlobalCertificate::find($request->id);
  $global_certificate->delete();
  $this->apiSuccess();
  return $this->apiOutput(" Global Certificate Deleted Successfully", 200);
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
