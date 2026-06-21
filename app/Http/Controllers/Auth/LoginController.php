<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $stats = [
            'mahasiswa_aktif' => Mahasiswa::where('status', 'aktif')->count(),
            'dosen_pa'        => Dosen::count(),
            'kelas_aktif'     => Kelas::where('tahun_akademik', config('akademik.tahun_akademik'))->count(),
        ];

        return view('auth.login', $stats);
    }

    // Redirect ke dashboard sesuai role setelah login
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('dosen')) {
            return redirect()->route('dosen.dashboard');
        }

        if ($user->hasRole('mahasiswa')) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return redirect('/');
    }
}