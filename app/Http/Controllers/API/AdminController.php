<?php

namespace App\Http\Controllers\API;

use App\Mail\Invite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteRequest;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function invite(InviteRequest $request)
    {
        try {
            $data = $request->validated();
            Mail::to($request->email)->send(new Invite($data));
            return response()->json([
                'success' => true,
                'message' => 'Email sent succesfully',
            ], Response::HTTP_OK);
        } catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error sending email. Please try again.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
