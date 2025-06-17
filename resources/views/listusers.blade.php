@extends('layouts.app')
@section('content')

<div class="w-[1200px] mx-auto bg-white rounded-3xl shadow-md p-6 mt-16 mb-8">
    <h2 class="text-2xl font-bold mb-4">Users</h2>

    <div class="space-y-3">
        @foreach($users as $user)
        <div class="flex justify-between items-center bg-gray-200 px-4 py-3 rounded-2xl">
            <span class="font-semibold text-xl">{{ $user->name }}</span>

                    @if($user->status === 'Waiting')
                        <div class="flex items-center space-x-2 text-sm text-gray-600 bg-white rounded-full px-3 py-1">
                            <span>Waiting</span>
                        </div>
                    @else
                        <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button"
                                data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->name }}"
                                onclick="confirmDelete(this)"
                                class="flex items-center space-x-2 text-sm text-red-600 bg-white rounded-full px-3 py-1 hover:bg-red-100 transition">
                            <span>Hapus</span>
                        </button>
                    @endif
                </div>
            @endif
        @endforeach
    </div>

    <div id="userApprovalModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-[100] hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">
            <button id="closeApprovalModalBtn"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold">
                &times;
            </button>
            <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Persetujuan Pengguna Baru</h3>

            <div id="requestingUsersList" class="space-y-4">
                {{-- Users will be loaded here by JavaScript --}}
                <p class="text-gray-500 text-center">Memuat pengguna...</p>
            </div>

            <div id="noRequestingUsers" class="text-gray-500 text-center py-8 hidden">
                Tidak ada pengguna yang sedang menunggu persetujuan.
            </div>

            <div id="loadingUsers" class="text-gray-500 text-center py-8">
                <i class="fas fa-spinner fa-spin text-xl"></i> Memuat...
            </div>
        </div>
    </div>

    <button id="openApprovalModalBtn"
        class="fixed bottom-4 right-4 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full shadow-lg z-50">
        <i class="fas fa-user-plus mr-2"></i> Pengguna Baru
    </button>
</div>

<script>
    function confirmDelete(buttonElement) {
        // Ambil data dari atribut data-* pada tombol yang diklik
        const userId = buttonElement.dataset.userId;
        const userName = buttonElement.dataset.userName;

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Anda tidak akan bisa mengembalikan user '${userName}' ini!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6', 
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                
                document.getElementById('delete-user-' + userId).submit();
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
            const openModalBtn = document.getElementById('openApprovalModalBtn');
            const closeModalBtn = document.getElementById('closeApprovalModalBtn');
            const userApprovalModal = document.getElementById('userApprovalModal');
            const requestingUsersList = document.getElementById('requestingUsersList');
            const noRequestingUsersMessage = document.getElementById('noRequestingUsers');
            const loadingUsersMessage = document.getElementById('loadingUsers');

            // Fungsi untuk membuka modal
            openModalBtn.addEventListener('click', function() {
                userApprovalModal.classList.remove('hidden');
                loadRequestingUsers(); // Muat pengguna saat modal dibuka
            });

            // Fungsi untuk menutup modal
            closeModalBtn.addEventListener('click', function() {
                userApprovalModal.classList.add('hidden');
            });

            // Tutup modal jika klik di luar konten modal
            userApprovalModal.addEventListener('click', function(e) {
                if (e.target === userApprovalModal) {
                    userApprovalModal.classList.add('hidden');
                }
            });

            // Fungsi untuk memuat daftar pengguna yang statusnya 'Requesting'
            async function loadRequestingUsers() {
                requestingUsersList.innerHTML = ''; // Kosongkan daftar sebelumnya
                noRequestingUsersMessage.classList.add('hidden');
                loadingUsersMessage.classList.remove('hidden');

                try {
                    // Endpoint untuk mengambil user yang statusnya 'Requesting' dan daftar peran
                    const response = await fetch('/api/users/requesting', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                        credentials: 'include' // PENTING: Untuk mengirim cookie sesi
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({ message: 'Gagal memuat pengguna. Respons bukan JSON.', errorStatus: response.status }));
                        throw new Error(errorData.message || 'Gagal memuat pengguna: ' + response.statusText);
                    }
                    const { users, roles } = await response.json(); // Ambil user dan roles dari respons

                    loadingUsersMessage.classList.add('hidden');

                    if (users.length === 0) {
                        noRequestingUsersMessage.classList.remove('hidden');
                        return;
                    }

                    users.forEach(user => {
                        const userDiv = document.createElement('div');
                        userDiv.className = 'bg-gray-100 p-4 rounded-lg flex flex-col md:flex-row items-center justify-between shadow-sm';
                        userDiv.innerHTML = `
                            <div class="mb-3 md:mb-0 md:mr-4 text-center md:text-left">
                                <p class="font-semibold text-lg text-gray-800">${user.name} <span class="text-sm text-gray-500">(${user.email})</span></p>
                                <p class="text-sm text-gray-600">${user.nrp ? 'NRP: ' + user.nrp : (user.nip ? 'NIP: ' + user.nip : '')}</p>
                                <p class="text-sm text-gray-600">Status: <span class="font-medium text-yellow-600">Requesting</span></p>
                            </div>
                            <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                                {{-- Tombol Setujui sebagai Mahasiswa --}}
                                <button data-user-id="${user.id}" data-role="mahasiswa" class="approve-role-btn w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition text-sm">
                                    Setujui Mahasiswa
                                </button>
                                {{-- Tombol Setujui sebagai Admin --}}
                                <button data-user-id="${user.id}" data-role="admin" class="approve-role-btn w-full md:w-auto bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition text-sm">
                                    Setujui Admin
                                </button>
                                {{-- Anda bisa menyertakan dropdown 'Pilih Peran' asli jika masih ingin opsi generik --}}
                                {{--
                                <select id="roleSelect_${user.id}" class="block w-full md:w-40 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white">
                                    <option value="">Pilih Peran Lain</option>
                                    ${roles.map(role => `<option value="${role.role}">${role.role}</option>`).join('')}
                                </select>
                                <button data-user-id="${user.id}" data-role-from-select="true" class="approve-role-btn w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition">
                                    Setujui (Dropdown)
                                </button>
                                --}}
                            </div>
                        `;
                        requestingUsersList.appendChild(userDiv);
                    });

                    // ✅ Hanya satu event listener untuk semua tombol persetujuan
                    document.querySelectorAll('.approve-role-btn').forEach(button => {
                        button.addEventListener('click', async function() {
                            const userId = this.dataset.userId;
                            const targetRole = this.dataset.role; // Mengambil peran langsung dari data-role
                            // const isFromSelect = this.dataset.roleFromSelect === 'true'; // Untuk dropdown opsional

                            // Jika Anda menggunakan dropdown opsional
                            // let roleNameToSend = targetRole;
                            // if (isFromSelect) {
                            //     const roleSelect = document.getElementById(`roleSelect_${userId}`);
                            //     if (!roleSelect.value) {
                            //         Swal.fire('Peringatan', 'Silakan pilih peran untuk pengguna ini dari dropdown.', 'warning');
                            //         return;
                            //     }
                            //     roleNameToSend = roleSelect.value;
                            // }

                            Swal.fire({
                                title: 'Konfirmasi',
                                text: `Anda yakin ingin menyetujui ${users.find(u => u.id == userId).name} sebagai ${targetRole}?`,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Setujui!',
                                cancelButtonText: 'Batal'
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    try {
                                        // Tentukan URL endpoint berdasarkan peran target
                                        const endpointUrl = `/api/users/${userId}/approve/${targetRole}`; // Menggunakan endpoint spesifik

                                        const response = await fetch(endpointUrl, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                            },
                                            credentials: 'include',
                                            body: JSON.stringify({}) // Body kosong atau sesuai kebutuhan jika tidak ada data selain ID/peran di URL
                                        });

                                        if (!response.ok) {
                                            const errorData = await response.json().catch(() => ({ message: 'Gagal menyetujui pengguna. Respons bukan JSON.', errorStatus: response.status }));
                                            throw new Error(errorData.message || 'Gagal menyetujui pengguna.');
                                        }

                                        const result = await response.json();
                                        Swal.fire('Berhasil!', result.message, 'success');
                                        
                                        // ✅ Tambahkan reload halaman setelah sukses dan keluar modal
                                        // Ini akan me-refresh daftar user di halaman utama
                                        userApprovalModal.classList.add('hidden'); // Sembunyikan modal
                                        window.location.reload(); // Reload halaman
                                    } catch (error) {
                                        console.error('Error approving user:', error);
                                        Swal.fire('Error', error.message, 'error');
                                    }
                                }
                            });
                        });
                    });

                } catch (error) {
                    console.error('Error loading requesting users:', error);
                    loadingUsersMessage.classList.add('hidden');
                    requestingUsersList.innerHTML = `<p class="text-red-500 text-center">Error: ${error.message}</p>`;
                }
            }
        });
</script>
@endsection