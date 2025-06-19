@extends('layouts.app')
@section('content')

<div class="w-11/12 md:w-10/12 lg:w-[1200px] mx-auto bg-white rounded-3xl shadow-md p-4 md:p-8 mt-8 md:mt-16 mb-4 md:mb-8 relative">
    <h2 class="text-xl md:text-2xl font-bold mb-4">Users</h2>

    <div class="space-y-3">
        @foreach($users as $user)
        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-200 px-3 py-2 md:px-5 md:py-3 rounded-2xl text-center md:text-left">
            <span class="font-semibold text-lg md:text-xl mb-2 md:mb-0">{{ $user->name }}</span>

            @if($user->status === 'Waiting')
            <div class="flex items-center space-x-2 text-xs md:text-sm text-gray-600 bg-white rounded-full px-2 py-1 md:px-3 md:py-1">
                <span>Waiting</span>
            </div>
            @else
            <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                onclick="confirmDelete(this)"
                class="flex items-center space-x-2 text-xs md:text-sm text-red-600 bg-white rounded-full px-2 py-1 md:px-3 md:py-1 hover:bg-red-100 transition">
                <span>Hapus</span>
            </button>
            @endif
        </div>
        @endforeach

        <div class="flex justify-end mt-4">
            <button id="openApprovalModalBtn"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 md:py-3 md:px-6 rounded-full shadow-lg z-50 text-sm md:text-base">
                <i class="fas fa-user-plus mr-2"></i> Pengguna Baru
            </button>
        </div>
    </div>

    <div id="userApprovalModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-[100] hidden p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm md:max-w-xl lg:max-w-2xl max-h-[90vh] overflow-y-auto relative">
            <button id="closeApprovalModalBtn"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold">
                &times;
            </button>
            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Persetujuan Pengguna Baru</h3>

            <div id="requestingUsersList" class="space-y-4">
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
</div>

<script>
    function confirmDelete(buttonElement) {
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

        openModalBtn.addEventListener('click', function() {
            userApprovalModal.classList.remove('hidden');
            loadRequestingUsers();
        });

        closeModalBtn.addEventListener('click', function() {
            userApprovalModal.classList.add('hidden');
        });

        userApprovalModal.addEventListener('click', function(e) {
            if (e.target === userApprovalModal) {
                userApprovalModal.classList.add('hidden');
            }
        });

        async function loadRequestingUsers() {
            requestingUsersList.innerHTML = '';
            noRequestingUsersMessage.classList.add('hidden');
            loadingUsersMessage.classList.remove('hidden');

            try {
                const response = await fetch('/api/users/requesting', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'include'
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({ message: 'Gagal memuat pengguna. Respons bukan JSON.', errorStatus: response.status }));
                    throw new Error(errorData.message || 'Gagal memuat pengguna: ' + response.statusText);
                }
                const { users, roles } = await response.json();

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
                            <p class="font-semibold text-lg md:text-xl text-gray-800">${user.name} <span class="text-sm text-gray-500">(${user.email})</span></p>
                            <p class="text-sm text-gray-600">${user.nrp ? 'NRP: ' + user.nrp : (user.nip ? 'NIP: ' + user.nip : '')}</p>
                            <p class="text-sm text-gray-600">Status: <span class="font-medium text-yellow-600">Requesting</span></p>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                            <button data-user-id="${user.id}" data-role="mahasiswa" class="approve-role-btn w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition text-sm">
                                Setujui Mahasiswa
                            </button>
                            <button data-user-id="${user.id}" data-role="admin" class="approve-role-btn w-full md:w-auto bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition text-sm">
                                Setujui Admin
                            </button>
                        </div>
                    `;
                    requestingUsersList.appendChild(userDiv);
                });

                document.querySelectorAll('.approve-role-btn').forEach(button => {
                    button.addEventListener('click', async function() {
                        const userId = this.dataset.userId;
                        const targetRole = this.dataset.role;

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
                                    const endpointUrl = `/api/users/${userId}/approve/${targetRole}`;

                                    const response = await fetch(endpointUrl, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        credentials: 'include',
                                        body: JSON.stringify({})
                                    });

                                    if (!response.ok) {
                                        const errorData = await response.json().catch(() => ({ message: 'Gagal menyetujui pengguna. Respons bukan JSON.', errorStatus: response.status }));
                                        throw new Error(errorData.message || 'Gagal menyetujui pengguna.');
                                    }

                                    const result = await response.json();
                                    Swal.fire('Berhasil!', result.message, 'success');

                                    userApprovalModal.classList.add('hidden');
                                    window.location.reload();
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