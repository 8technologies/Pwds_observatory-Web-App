<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
   
   public function showForgotRequestForm()
   {
       return view('forgot-password.forgot_password');
   }

   // Send Reset Link (Handles both API and Web)
   public function sendResetLinkEmail(Request $request)
   {
       // Validate the request
       $validator = Validator::make($request->all(), [
           'email' => 'required|email',
       ]);

       // Handle validation errors
       if ($validator->fails()) {
           // Check if the request is from the API or web
           if ($request->wantsJson()) {
               // Respond with JSON for API requests
               return response()->json(['errors' => $validator->errors()], 422);
           }
           // Redirect back with validation errors for web requests
           return back()->withErrors($validator)->withInput();
       }

       // Send password reset link
       $status = Password::sendResetLink($request->only('email'));

       // Handle the response for API and web
       if ($status === Password::RESET_LINK_SENT) {
           // API response
           if ($request->wantsJson()) {
               return response()->json(['message' => __($status)], 200);
           }
           // Web response (redirect back with status)
           return back()->with('status', __($status));
       } else {
           // API response
           if ($request->wantsJson()) {
               return response()->json(['message' => __($status)], 400);
           }
           // Web response (redirect back with error)
           return back()->withErrors(['email' => __($status)]);
       }
   }
}
