<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCompletionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class InactiveUserController extends Controller
{
    /**
     * Update user profile data while in inactive state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'gender' => 'sometimes|nullable|in:L,P',
            'city'   => 'sometimes|nullable|string|max:100',
            'phone'  => 'sometimes|nullable|string|max:20',
            'bio'    => 'required|string|min:5|max:400',
        ];

        // If username is null, it's required
        if (is_null($user->username)) {
            $rules['username'] = 'required|string|alpha_dash|min:3|max:50|unique:users,username';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        // Update User
        $updateData = [];
        if ($request->has('username') && is_null($user->username)) $updateData['username'] = $request->username;
        if ($request->has('gender') && is_null($user->gender)) $updateData['gender'] = $request->gender;
        if ($request->has('city') && is_null($user->city))     $updateData['city'] = $request->city;
        if ($request->has('phone') && is_null($user->phone))   $updateData['phone'] = $request->phone;
        if ($request->has('bio'))     $updateData['bio'] = $request->bio;

        $user->update($updateData);

        // Send Notification to Admin & Confirmation to User
        try {
            $admins = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['admin', 'dev']);
            })->get();

            if ($admins->isEmpty()) {
                $admins = User::where('id', 1)->get();
            }

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AccountCompletionNotification($user, $request->bio));
            }

            // Send Email Confirmation directly to User
            \Illuminate\Support\Facades\Mail::raw(
                "Halo " . $user->name . ",\n\nTerima kasih telah melengkapi data profil pendaftaran Anda.\n\nData pendaftaran Anda telah kami terima dan saat ini dalam proses verifikasi oleh Admin (1x24 jam kerja).\nKami akan memberi tahu Anda via email setelah akun Anda disetujui.\n\nSalam,\nProject Management Team",
                function ($mail) use ($user) {
                    $mail->to($user->email)
                         ->subject('📌 Pendaftaran Akun Diterima - Menunggu Verifikasi');
                }
            );
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan dan menunggu verifikasi admin.'
        ]);
    }

    /**
     * Resend notification email to user and admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendNotification(Request $request)
    {
        $user = auth()->user();

        try {
            $admins = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['admin', 'dev']);
            })->get();

            if ($admins->isEmpty()) {
                $admins = User::where('id', 1)->get();
            }

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AccountCompletionNotification($user, $user->bio ?? '-'));
            }

            // Send Email Confirmation directly to User
            \Illuminate\Support\Facades\Mail::raw(
                "Halo " . $user->name . ",\n\nTerima kasih telah melengkapi data profil pendaftaran Anda.\n\nData pendaftaran Anda telah kami terima dan saat ini dalam proses verifikasi oleh Admin (1x24 jam kerja).\nKami akan memberi tahu Anda via email setelah akun Anda disetujui.\n\nSalam,\nProject Management Team",
                function ($mail) use ($user) {
                    $mail->to($user->email)
                         ->subject('📌 Pendaftaran Akun Diterima - Menunggu Verifikasi');
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Email notifikasi verifikasi berhasil dikirim ulang ke ' . $user->email . '!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim ulang notifikasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang email notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
