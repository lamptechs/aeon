<?php

namespace App\Http\Controllers\Backend;
namespace App\Traits;

use App\Http\Components\Traits\Upload;
use Exception;
use App\Models\Vendor;
use App\Models\VendorUpload;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;
use App\Events\AccountRegistration;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Models\Vendor as ModelsVendor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Backend\VendorResource;


class VendorController extends Controller
{
    // use uploadFile;

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
            $this->data = VendorResource::collection(Vendor::all());
            $this->apiSuccess("Admin Load has been Successfully done");
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }

    public function show(Request $request)
    {
        try{
            $vendor = Vendor::find($request->id);
            if( empty($vendor) ){
                return $this->apiOutput("Vendor Data Not Found", 400);
            }
            $this->data = (new VendorResource ($vendor));
            $this->apiSuccess("Vendor Detail Show Successfully");
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
                    "name"          => ["required", "min:4"],
                    "email"         => ["required","email"],
                    "status"        => 'required',

                ],[
                    // "group_id.exists"     => "No Record found under this group",
                ]
               );

                if ($validator->fails()) {
                    return $this->apiOutput($this->getValidationError($validator), 400);
                }
                $vendor = new Vendor();
                $vendor->name = $request->name;
              //$vendor->logo = $request->logo;

                if($request->hasFile('picture')){
                    //$vendor->logo = $this->uploadFile($request, 'picture', $this->vendor_uploads , null,null,$vendor->logo);
                    $vendor->logo = $this->uploadFile($request, 'picture', $this->vendor_uploads , null,null,$vendor->logo);
                }


                // image insert

                // if($request->file('logo')){
                //     $file= $request->file('logo');

                //     $filename= 'storage/uploads/vendor/'.$file->getClientOriginalName();
                //     $file-> move(public_path('public/Image'), $filename);
                //     $vendor['logo']= $filename;
                // }

                $vendor->address = $request->address;
                $vendor->email = $request->email;
                $vendor->contact_number = $request->contact_number;
                $vendor->remarks = $request->remarks;
                $vendor->status = $request->status;
                $vendor->created_at = $request->created_at;
                $vendor->updated_at = $request->updated_at;
                $vendor->deleted_by = $request->deleted_by;
                $vendor->deleted_date = $request->deleted_date;

                $vendor->save();
                // $this->saveFileInfo($request, $vendor);

            try{
                event(new AccountRegistration($vendor));
            }catch(Exception $e){

            }
            $this->apiSuccess("Vendor Added Successfully");
            $this->data = (new VendorResource($vendor));
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }


    public function update(Request $request,$id)
    {
        try{
        $validator = Validator::make($request->all(),[
            "id"            => ["required"],
            "name"          => ["required", "min:4"],
            "email"         => ["required","email"],
            "status"        => 'required',
        ],[
            // "id"                  => "No Data Found for this Id",
            // "group_id.exists"     => "No Record found under this group",
        ]
        );

           if ($validator->fails()) {
            $this->apiOutput($this->getValidationError($validator), 400);
           }

            $vendor = Vendor::find($request->id);
            // if(empty($admin)){
            //     return $this->apiOutput("No Data Found", $admin);
            // }
            $vendor->name = $request->name;
            $vendor->logo = $request->logo;

            // if($request->file('logo')){
            //     $file= $request->file('logo');

            //     $filename= 'storage/uploads/vendor/'.$file->getClientOriginalName();
            //     $file-> move(public_path('public/Image'), $filename);
            //     $vendor['logo']= $filename;
            // }

            $vendor->address = $request->address;
            $vendor->email = $request->email;
            $vendor->contact_number = $request->contact_number;
            $vendor->remarks = $request->remarks;
            $vendor->status = $request->status;
            $vendor->created_at = $request->created_at;
            $vendor->updated_at = $request->updated_at;
            $vendor->deleted_by = $request->deleted_by;
            $vendor->deleted_date = $request->deleted_date;

            // $admin->password = !empty($request->password) ? bcrypt($request->password) : $admin->password ;
            $vendor->save();
            $this->apiSuccess("Vendor Updated Successfully");
            $this->data = (new VendorResource($vendor));
            return $this->apiOutput();
        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }
    }

    public function destroy(Request $request,$id)
    {
        $vendor = Vendor::find($request->id);
        $vendor->delete();
        $this->apiSuccess();
        return $this->apiOutput("Vendor Deleted Successfully", 200);
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
            $admin = Vendor::where("email", $request->email)->first();
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
       /*
     * ---------------------------------------------
     * Upload an Image
     * Change Image height and width
     * Send the null value in height or width to keep
     * the Image Orginal Ratio.
     * ---------------------------------------------
     */
    // protected function uploadFile($request, $fileName, $dir, $width = null, $height =  null, $oldFile = ""){
    //     if(!$request->hasFile($fileName)){
    //         return $oldFile;
    //     }
    //     $this->CheckDir($dir);
    //     $this->RemoveFile($oldFile);

    //     ini_set('memory_limit', '1024M');
    //     $path_arr = [];

    //     if(is_array($request->$fileName) ){
    //         foreach($request->$fileName as $key => $file){
    //             $file = $request->file($fileName)[$key];
    //             $filename = $fileName.'_'.time().$key.'.'.$file->getClientOriginalExtension();
    //             $path = $dir.$filename;

    //             if( $this->isImage($path) ){
    //                 if( empty($height) && empty($width)){
    //                     Image::make($file)->save($path);
    //                 }
    //                 elseif( empty($height) && !empty($width) ){
    //                     Image::make($file)->resize($width,null,function($constant){
    //                         $constant->aspectRatio();
    //                     })->save($path);
    //                 }
    //                 elseif( !empty($height) && empty($width) ){
    //                     Image::make($file)->resize(null,$height,function($constant){
    //                         $constant->aspectRatio();
    //                     })->save($path);
    //                 }
    //                 else{
    //                     Image::make($file)->resize($width,$height)->save($path);
    //                 }
    //             }else{
    //                 $_dir = trim(str_replace("storage/", "", $dir), "/");
    //                 $path = "storage/".Storage::disk("public")->putFile($_dir, $file);
    //             }
    //             $path_arr[] = $path;
    //         }
    //     }else{
    //         $image = $request->file($fileName);
    //         $filename = $fileName.'_'.time().'.'.$image->getClientOriginalExtension();
    //         $path = $dir.$filename;

    //         if( empty($height) && empty($width)){
    //             Image::make($image)->save($path);
    //         }
    //         elseif( empty($height) && !empty($width) ){
    //             Image::make($image)->resize($width,null,function($constant){
    //                 $constant->aspectRatio();
    //             })->save($path);
    //         }
    //         elseif( !empty($height) && empty($width) ){
    //             Image::make($image)->resize(null,$height,function($constant){
    //                 $constant->aspectRatio();
    //             })->save($path);
    //         }
    //         else{
    //             Image::make($image)->resize($width,$height)->save($path);
    //         }
    //         $path_arr   = $path;
    //     }
    //     return $path_arr;
    // }
}
