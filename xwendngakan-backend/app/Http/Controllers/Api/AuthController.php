<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\IraqPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => ['nullable', 'string', 'max:20', IraqPhone::rule()],
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => IraqPhone::normalize($request->phone),
            'password'    => Hash::make($request->password),
            'is_approved' => true,
            'user_type'   => 'mobile',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'ئەکاونتەکەت بە سەرکەوتوویی دروست کرا!',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['ئیمەیڵ یان وشەی نهێنی هەڵەیە.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'بە سەرکەوتوویی چوویتە ژوورەوە!',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Sign in (or register) using a verified Firebase ID token.
     *
     * The mobile app authenticates the user with Firebase Authentication
     * (email/password) and sends us the resulting ID token. We verify it,
     * find-or-create the matching local user, and hand back a Sanctum token
     * so the rest of the API keeps working unchanged.
     */
    public function firebaseLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'name'     => 'nullable|string|max:255',
            'phone'    => ['nullable', 'string', 'max:20', IraqPhone::rule()],
        ]);

        /** @var FirebaseAuth|null $firebaseAuth */
        $firebaseAuth = app('firebase.auth');

        if (!$firebaseAuth) {
            return response()->json([
                'success' => false,
                'message' => 'خزمەتگوزاری Firebase لە سێرڤەر ڕێک نەخراوە.',
            ], 503);
        }

        try {
            $verified = $firebaseAuth->verifyIdToken($request->id_token);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'تۆکنی Firebase نادروستە یان بەسەرچووە.',
            ], 401);
        }

        $claims = $verified->claims();
        $email  = $claims->get('email');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم هەژمارەی Firebase ئیمەیڵی نییە.',
            ], 422);
        }

        $name = $request->name
            ?: ($claims->get('name') ?: Str::before($email, '@'));

        $user = User::where('email', $email)->first();

        $phone = IraqPhone::normalize($request->phone);

        if (!$user) {
            $user = User::create([
                'name'        => $name,
                'email'       => $email,
                'phone'       => $phone,
                // Random password: auth happens via Firebase, this is never used.
                'password'    => Str::random(40),
                'is_approved' => true,
                'user_type'   => 'mobile',
            ]);
        } elseif ($phone && !$user->phone) {
            // Backfill for accounts created before the phone field existed.
            $user->update(['phone' => $phone]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'بە سەرکەوتوویی چوویتە ژوورەوە!',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'بە سەرکەوتوویی چوویتە دەرەوە.',
        ]);
    }

    /**
     * Permanently delete the authenticated user's account and all related data.
     * Required by App Store Guideline 5.1.1(v) for apps that support account creation.
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        DB::transaction(function () use ($user) {
            // Revoke all API tokens
            $user->tokens()->delete();

            // Remove related data
            $user->favorites()->delete();
            $user->reports()->delete();

            // Finally delete the user record itself
            $user->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'هەژمارەکەت و هەموو زانیارییەکانت بە سەرکەوتوویی سڕانەوە.',
        ]);
    }

    /**
     * Get current user.
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }

    /**
     * Send password reset code.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate a 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the code (expires in 15 minutes)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token'      => Hash::make($code),
                'created_at' => now(),
            ]
        );

        // Send email with the code
        try {
            Mail::raw("کۆدی گۆڕینی وشەی نهێنی: $code\n\nئەم کۆدە لە ماوەی ١٥ خولەکدا بەسەردەچێت.", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('کۆدی گۆڕینی وشەی نهێنی - خوێندنگاکانم');
            });
        } catch (\Exception $e) {
            // Log the error but don't expose it to the user
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'کۆدی گۆڕینی وشەی نهێنی نێردرا بۆ ئیمەیڵەکەت.',
        ]);
    }

    /**
     * Verify reset code.
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            throw ValidationException::withMessages([
                'code' => ['هیچ داواکارییەکی گۆڕینی وشەی نهێنی نەدۆزرایەوە.'],
            ]);
        }

        // Check if code is expired (15 minutes)
        if (now()->diffInMinutes($record->created_at) > 15) {
            throw ValidationException::withMessages([
                'code' => ['کۆدەکە بەسەرچووە. تکایە کۆدی نوێ داوا بکە.'],
            ]);
        }

        if (!Hash::check($request->code, $record->token)) {
            throw ValidationException::withMessages([
                'code' => ['کۆدەکە هەڵەیە.'],
            ]);
        }

        // Generate a temporary token for the reset
        $resetToken = Str::random(60);
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->update(['token' => Hash::make($resetToken)]);

        return response()->json([
            'success' => true,
            'message' => 'کۆدەکە دروستە.',
            'data'    => [
                'reset_token' => $resetToken,
            ],
        ]);
    }

    /**
     * Reset password with token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'       => 'required|email|exists:users,email',
            'reset_token' => 'required|string',
            'password'    => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->reset_token, $record->token)) {
            throw ValidationException::withMessages([
                'reset_token' => ['تۆکنەکە نادروستە یان بەسەرچووە.'],
            ]);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Revoke all tokens for security
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'وشەی نهێنی بە سەرکەوتوویی گۆڕدرا!',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Update FCM token for push notifications.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();

        // A token belongs to one device = one current user. Detach it from any
        // other account first (e.g. several accounts logged in on the same
        // phone) so a broadcast never sends duplicates to the same device.
        User::where('fcm_token', $request->fcm_token)
            ->whereKeyNot($user->getKey())
            ->update(['fcm_token' => null]);

        $user->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تۆکنی ئاگادارکردنەوە نوێکرایەوە.',
        ]);
    }

    /**
     * Toggle notifications.
     */
    public function toggleNotifications(Request $request)
    {
        $user = $request->user();
        $user->notifications_enabled = !$user->notifications_enabled;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->notifications_enabled
                ? 'ئاگادارکردنەوەکان چالاک کرا.'
                : 'ئاگادارکردنەوەکان ناچالاک کرا.',
            'data'    => [
                'notifications_enabled' => $user->notifications_enabled,
            ],
        ]);
    }
}
