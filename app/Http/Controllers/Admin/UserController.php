<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Daftar 4 Kabupaten target di Provinsi Gorontalo.
     *
     * @var list<string>
     */
    protected array $kabupatenList = [
        'Kabupaten Boalemo',
        'Kabupaten Pohuwato',
        'Kabupaten Gorontalo',
        'Kabupaten Gorontalo Utara',
    ];

    /**
     * Menampilkan daftar seluruh akun aparatur dengan fitur pencarian dan filter peran.
     */
    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin();

        $query = User::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('kabupaten', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
            'operator' => User::where('role', 'operator_kabupaten')->count(),
            'eksekutif' => User::where('role', 'eksekutif')->count(),
        ];

        return view('admin.users.index', [
            'users' => $users,
            'stats' => $stats,
            'search' => $request->input('search', ''),
            'selectedRole' => $request->input('role', ''),
            'kabupatenList' => $this->kabupatenList,
        ]);
    }

    /**
     * Menampilkan formulir pendaftaran aparatur baru.
     */
    public function create(): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.users.create', [
            'kabupatenList' => $this->kabupatenList,
        ]);
    }

    /**
     * Menyimpan data aparatur baru ke basis data.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'operator_kabupaten', 'eksekutif'])],
            'kabupaten' => ['nullable', 'required_if:role,operator_kabupaten', 'string'],
            'jabatan' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap aparatur wajib diisi.',
            'email.required' => 'Alamat email kedinasan wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required' => 'Peran akun aparatur wajib dipilih.',
            'kabupaten.required_if' => 'Kabupaten penugasan wajib dipilih untuk Operator Kabupaten.',
        ]);

        if ($validated['role'] !== 'operator_kabupaten') {
            $validated['kabupaten'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AuditLog::record(
            action: 'CREATE_USER',
            auditable: $user,
            oldValues: null,
            newValues: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kabupaten' => $user->kabupaten,
                'jabatan' => $user->jabatan,
            ],
            notes: "Membuat akun aparatur baru: {$user->name} ({$user->email}) peran {$user->role}"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun aparatur '{$user->name}' berhasil didaftarkan.");
    }

    /**
     * Menampilkan formulir ubah data akun aparatur.
     */
    public function edit(User $user): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.users.edit', [
            'user' => $user,
            'kabupatenList' => $this->kabupatenList,
        ]);
    }

    /**
     * Memperbarui data akun aparatur.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'operator_kabupaten', 'eksekutif'])],
            'kabupaten' => ['nullable', 'required_if:role,operator_kabupaten', 'string'],
            'jabatan' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap aparatur wajib diisi.',
            'email.required' => 'Alamat email kedinasan wajib diisi.',
            'email.unique' => 'Email ini telah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'role.required' => 'Peran akun aparatur wajib dipilih.',
            'kabupaten.required_if' => 'Kabupaten penugasan wajib dipilih untuk Operator Kabupaten.',
        ]);

        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'kabupaten' => $user->kabupaten,
            'jabatan' => $user->jabatan,
        ];

        // Mencegah super admin mencabut peran super_admin miliknya sendiri saat sedang aktif
        if ($user->id === Auth::id() && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri saat sedang masuk.');
        }

        if ($validated['role'] !== 'operator_kabupaten') {
            $validated['kabupaten'] = null;
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        AuditLog::record(
            action: 'UPDATE_USER',
            auditable: $user,
            oldValues: $oldValues,
            newValues: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kabupaten' => $user->kabupaten,
                'jabatan' => $user->jabatan,
            ],
            notes: "Memperbarui data akun aparatur: {$user->name} ({$user->email})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Data akun aparatur '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Menghapus akun aparatur dari sistem.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang aktif masuk.');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $userRole = $user->role;

        $user->delete();

        AuditLog::record(
            action: 'DELETE_USER',
            auditable: null,
            oldValues: [
                'name' => $userName,
                'email' => $userEmail,
                'role' => $userRole,
            ],
            newValues: null,
            notes: "Menghapus akun aparatur: {$userName} ({$userEmail})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun aparatur '{$userName}' berhasil dihapus dari sistem.");
    }

    /**
     * Memastikan hanya Super Admin Provinsi yang memiliki otorisasi.
     */
    protected function authorizeSuperAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->isSuperAdmin(),
            403,
            'Akses ditolak: Hanya Super Admin Provinsi yang berhak mengelola akun aparatur.'
        );
    }
}
