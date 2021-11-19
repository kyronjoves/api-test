<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Http\Requests\ClientRegisterRequest;

class ClientController extends Controller
{
    public function register(ClientRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $avatar = $this->processImage($request);
            $user = User::create([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'role' => 'user',
                'avatar' => $avatar,
                'password' => Hash::make($request->password),
                'registered_at' => Carbon::now()
            ]);
            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'result' => $user,

                ], Response::HTTP_OK
            );
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error. Please try again.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function processImage(Request $request)
    {
        try {
            $image = $request->file('avatar');
            $name = time().'.'.$image->getClientOriginalExtension();
            $imgFile = Image::make($image->getRealPath());
            $imgFile->resize(256, 256, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/uploads') .'/'.$name);
            return $name;
        } catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error. Please try again.',
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
