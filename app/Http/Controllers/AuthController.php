<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Get current authenticated user status and permissions.
     */
    public function status(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'authenticated' => false,
                'user' => null,
                'role' => 'tamu',
                'permissions' => [
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_import' => false,
                    'can_export' => true,
                    'is_super_admin' => false,
                    'is_operator' => false,
                    'is_eksekutif' => false,
                    'kabupaten' => null,
                ],
            ]);
        }

        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kabupaten' => $user->kabupaten,
                'jabatan' => $user->jabatan,
            ],
            'role' => $user->role,
            'permissions' => [
                'can_create' => $user->isSuperAdmin() || $user->isOperatorKabupaten(),
                'can_edit' => $user->isSuperAdmin() || $user->isOperatorKabupaten(),
                'can_delete' => $user->isSuperAdmin() || $user->isOperatorKabupaten(),
                'can_import' => $user->isSuperAdmin() || $user->isOperatorKabupaten(),
                'can_export' => true,
                'is_super_admin' => $user->isSuperAdmin(),
                'is_operator' => $user->isOperatorKabupaten(),
                'is_eksekutif' => $user->isEksekutif(),
                'kabupaten' => $user->kabupaten,
            ],
        ]);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember', true))) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            AuditLog::record(
                'LOGIN',
                $user,
                null,
                ['email' => $user->email, 'role' => $user->role],
                "Pengguna {$user->name} berhasil masuk sistem.",
                $user
            );

            return response()->json([
                'success' => true,
                'message' => "Selamat datang, {$user->name}!",
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau kata sandi tidak cocok dengan data kredensial kami.',
        ], 401);
    }

    /**
     * 1-Click Persona Switcher for demonstration, testing and evaluation.
     */
    public function switchRole(Request $request): JsonResponse
    {
        $roleKey = $request->input('role');

        $emailMap = [
            'super_admin' => 'admin@gorontalo.go.id',
            'operator_boalemo' => 'operator.boalemo@gorontalo.go.id',
            'operator_pohuwato' => 'operator.pohuwato@gorontalo.go.id',
            'operator_gorontalo' => 'operator.gorontalo@gorontalo.go.id',
            'operator_gorut' => 'operator.gorut@gorontalo.go.id',
            'eksekutif' => 'eksekutif@gorontalo.go.id',
        ];

        $targetEmail = $emailMap[$roleKey] ?? $request->input('email');

        if (! $targetEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Target role tidak valid.',
            ], 400);
        }

        $user = User::where('email', $targetEmail)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "Pengguna dengan email {$targetEmail} tidak ditemukan.",
            ], 404);
        }

        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::record(
            'ROLE_SWITCH',
            $user,
            null,
            ['role' => $user->role, 'kabupaten' => $user->kabupaten],
            "Sesi beralih ke persona: {$user->name} ({$user->role})",
            $user
        );

        return response()->json([
            'success' => true,
            'message' => "Berhasil beralih ke persona: {$user->name}",
            'user' => $user,
        ]);
    }

    /**
     * Show the web login view.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle standard web form login.
     */
    public function loginWeb(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember', true))) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            AuditLog::record(
                'LOGIN',
                $user,
                null,
                ['email' => $user->email, 'role' => $user->role],
                "Pengguna {$user->name} berhasil masuk melalui Panel Admin.",
                $user
            );

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Kombinasi email dan kata sandi tidak cocok.']);
    }

    /**
     * 1-Click Persona Switcher for web session.
     */
    public function switchRoleWeb(Request $request): RedirectResponse
    {
        $roleKey = $request->input('role');

        $emailMap = [
            'super_admin' => 'admin@gorontalo.go.id',
            'operator_boalemo' => 'operator.boalemo@gorontalo.go.id',
            'operator_pohuwato' => 'operator.pohuwato@gorontalo.go.id',
            'operator_gorontalo' => 'operator.gorontalo@gorontalo.go.id',
            'operator_gorut' => 'operator.gorut@gorontalo.go.id',
            'eksekutif' => 'eksekutif@gorontalo.go.id',
        ];

        $targetEmail = $emailMap[$roleKey] ?? $request->input('email');

        if (! $targetEmail) {
            return back()->with('error', 'Target role tidak valid.');
        }

        $user = User::where('email', $targetEmail)->first();

        if (! $user) {
            return back()->with('error', "Pengguna {$targetEmail} tidak ditemukan.");
        }

        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::record(
            'ROLE_SWITCH',
            $user,
            null,
            ['role' => $user->role, 'kabupaten' => $user->kabupaten],
            "Sesi web beralih ke persona: {$user->name} ({$user->role})",
            $user
        );

        $redirect = $request->input('redirect_to', route('admin.dashboard'));

        return redirect($redirect)->with('success', "Berhasil masuk sebagai persona: {$user->name} ({$user->jabatan})");
    }

    /**
     * Handle user logout (API / JSON).
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            AuditLog::record(
                'LOGOUT',
                $user,
                null,
                null,
                "Pengguna {$user->name} keluar dari sistem.",
                $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Anda telah berhasil keluar.',
        ]);
    }

    /**
     * Handle web user logout.
     */
    public function logoutWeb(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            AuditLog::record(
                'LOGOUT',
                $user,
                null,
                null,
                "Pengguna {$user->name} keluar dari sesi Web Panel.",
                $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
