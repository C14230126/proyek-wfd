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
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center space-x-2 text-sm text-red-600 bg-white rounded-full px-3 py-1">
                                <span>Hapus</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection
