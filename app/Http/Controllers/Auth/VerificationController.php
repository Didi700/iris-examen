<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function notice()
    {
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Logger la vérification
            LogActivite::create([
                'utilisateur_id' => $request->user()->id,
                'action' => 'verification_email',
                'module' => 'authentification',
                'description' => 'Email vérifié avec succès',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect('/dashboard')->with('verified', true);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }
}