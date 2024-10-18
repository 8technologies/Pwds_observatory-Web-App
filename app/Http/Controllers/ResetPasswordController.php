<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{

    //Show reset form
    public function showResetForm(Request $request, $token = null)
    {
        return view('forgot-password.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:4|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    //Ogiki Moses Odera 

    // public static function mail_send($data)
    // {
    //     // Your existing mail_send function
    //     if (!isset($data['email'])) {
    //         throw new \Exception('Failed to send email because email is not set');
    //     }

    //     if (!isset($data['name'])) {
    //         $data['name'] = $data['email'];
    //     }

    //     if (!isset($data['subject'])) {
    //         throw new \Exception('Failed to send email because subject is not set');
    //     }

    //     if (!isset($data['body'])) {
    //         throw new \Exception('Failed to send email because body is not set');
    //     }

    //     $receiverEmail = isset($data['receiver']) ? $data['receiver'] : null;
        
    //     try {
    //         Mail::send(
    //             'emails.mail-template-1',
    //             $data,
    //             function ($m) use ($data, $receiverEmail) {
    //                 $m->to($data['email'], $data['name'])
    //                   ->subject($data['subject']);

    //                 if ($receiverEmail) {
    //                     $m->to($receiverEmail);  // Send to the additional receiver
    //                 }

    //                 $m->from(env('MAIL_USERNAME', 'info@ict4personswithdisabilities.org'), $data['subject']);
    //             }
    //         );
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }

    //     return 'success';
    // }

    // public function sendEmail(Request $request)
    // {
    //     // Validate the incoming request data
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'subject' => 'required|string',
    //         'body' => 'required|string',
    //         'receiver' => 'nullable|email'  // Optional receiver email
    //     ]);

    //     // If validation fails, return error response
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     // Get validated data
    //     $data = $validator->validated();

    //     // Call the mail_send function to send the email
    //     try {
    //         $result = self::mail_send($data);
    //         return response()->json(['message' => 'Email sent successfully!'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
}
