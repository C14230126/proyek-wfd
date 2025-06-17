@extends('layouts.app')
@section('content')
<div class="w-[1200px] mx-auto bg-white rounded-3xl shadow-md p-6 mt-16 mb-8">
    <h2 class="text-2xl font-bold mb-4">Users</h2>

    <div class="space-y-3">
        @foreach($users as $user)
            @if($user->role && $user->role->role !== 'admin')
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
</script>
@endsection