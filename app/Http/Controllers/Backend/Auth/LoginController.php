<?php
namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Services\StaffAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected StaffAuthService $staffAuthService
    ) {}

    public function create(): View
    {
        return view('backend.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if ($this->staffAuthService->sessionLogin($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('backend.dashboard', absolute: false));
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->staffAuthService->sessionLogout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('backend.login');
    }
}
