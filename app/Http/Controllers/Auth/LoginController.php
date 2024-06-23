<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $client = new Client();
        try {
            $response = $client->post('http://localhost/backend-koperasi/login.php', [
                'json' => [
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ]
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);

            // Logging the response body for debugging
            Log::info("Response body from native PHP endpoint: " . $body);

            if ($response->getStatusCode() == 200 && isset($data['success']) && $data['success'] === true && isset($data['id'])) {
                Auth::loginUsingId($data['id']);
                return $this->sendLoginResponse($request);
            } else {
                // Logging the reason for failed login
                Log::warning("Login failed with response: " . print_r($data, true));
            }

            return $this->sendFailedLoginResponse($request);
        } catch (\Exception $e) {
            Log::error("Error during login: " . $e->getMessage());
            return $this->sendFailedLoginResponse($request);
        }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
