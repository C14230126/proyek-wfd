<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('role', '!=', 'admin');
        })->with('role')->get();
    
        return view('listusers', compact('users'));
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('listusers.index')->with('success', 'User berhasil dihapus.');
    }


    public function getRequestingUsers()
    {
        try {
            $requestingUsers = User::requesting()->get();

            // ✅ PERBAIKAN: Mengambil daftar nama peran unik dari tabel 'roles'
            // Karena tidak ada tabel definisi peran terpisah, kita ambil nama peran yang ada
            // Atau, Anda bisa hardcode daftar peran jika hanya ada beberapa yang tetap (misal: 'admin', 'mahasiswa')
            $availableRoleNames = Roles::select('role')->distinct()->get()->pluck('role')->toArray();
            
            // Untuk frontend yang butuh 'id' dan 'role', kita bisa membuat array dummy
            $formattedRoles = [];
            foreach ($availableRoleNames as $index => $roleName) {
                $formattedRoles[] = [
                    'id' => $index + 1, // ID dummy karena tidak ada ID definisi peran
                    'role' => $roleName,
                ];
            }
            // Atau jika hanya ada 2 peran tetap (admin, mahasiswa) dan Anda ingin hardcode:
            // $formattedRoles = [
            //     ['id' => 1, 'role' => 'admin'],
            //     ['id' => 2, 'role' => 'mahasiswa'],
            // ];

            return response()->json([
                'users' => $requestingUsers,
                'roles' => $formattedRoles // Mengirimkan format roles yang diharapkan frontend
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching requesting users or roles:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Failed to load requesting users.'], 500);
        }
    }

    /**
     * Menyetujui pengguna dan memberikan peran.
     * Ini adalah fungsi inti yang akan dipanggil oleh metode spesifik.
     * @param Request $request
     * @param User $user
     * @param string $roleNameToAssign Nama peran yang akan diberikan (misal: 'admin', 'mahasiswa')
     */
    private function processUserApproval(Request $request, User $user, string $roleNameToAssign)
    {
        // Periksa apakah pengguna yang akan disetujui memang berstatus 'Requesting'
        if ($user->status !== 'Requesting') {
            return response()->json(['message' => 'Pengguna ini tidak dalam status Requesting.'], 400);
        }

        // ✅ PERBAIKAN: Tidak perlu mencari $selectedRole dari Roles::where('role', $roleNameToAssign)->first();
        // karena $roleNameToAssign sudah merupakan string nama peran yang kita inginkan.
        // Kita hanya perlu memvalidasi bahwa $roleNameToAssign adalah salah satu peran yang valid (opsional)
        $validRoles = ['admin', 'mahasiswa']; // Contoh peran yang valid
        if (!in_array($roleNameToAssign, $validRoles)) {
            return response()->json(['message' => "Peran '{$roleNameToAssign}' tidak valid."], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Perbarui status pengguna menjadi 'Accepted'
            $user->status = 'Accepted';
            $user->save();

            // 2. Berikan peran kepada pengguna
            // Karena tabel 'roles' Anda adalah pure user_id dan role (nama peran)
            // dan relasi di User adalah hasMany(Roles::class), kita akan delete dan create.
            // Ini mengasumsikan satu user hanya memiliki satu entri peran di tabel 'roles'.
            
            // Hapus peran lama yang terhubung ke user ini di tabel 'roles'
            $user->role()->delete(); // 'role()' adalah nama method hasMany Anda di User model

            // Buat entri peran baru di tabel 'roles' untuk user ini
            Roles::create([
                'user_id' => $user->id,
                'role' => $roleNameToAssign, // ✅ Langsung gunakan string nama peran
            ]);

            DB::commit();
            return response()->json(['message' => 'Pengguna berhasil disetujui dan diberi peran ' . $roleNameToAssign . '.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving user in processUserApproval:', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'role_name' => $roleNameToAssign,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Terjadi kesalahan saat menyetujui pengguna.'], 500);
        }
    }

    /**
     * Menyetujui pengguna sebagai Mahasiswa.
     */
    public function approveUserAsMahasiswa(Request $request, User $user)
    {
        return $this->processUserApproval($request, $user, 'mahasiswa');
    }

    /**
     * Menyetujui pengguna sebagai Admin.
     */
    public function approveUserAsAdmin(Request $request, User $user)
    {
        return $this->processUserApproval($request, $user, 'admin');
    }

    /**
     * Menyetujui pengguna dengan peran yang dipilih secara dinamis dari modal.
     * Frontend akan mengirimkan role_name (misal: 'admin', 'mahasiswa')
     */
    public function approveUser(Request $request, User $user)
    {
        // ✅ PERBAIKAN: Validasi role_name, bukan role_id, karena tidak ada ID definisi peran.
        // Validasi bahwa role_name yang dikirim adalah salah satu dari peran yang Anda kenal.
        $request->validate([
            'role_name' => 'required|string|in:admin,mahasiswa', // Sesuaikan dengan peran yang valid
        ]);
        
        // ✅ Langsung gunakan role_name dari request
        $roleName = $request->role_name; 

        return $this->processUserApproval($request, $user, $roleName);
    }
}
