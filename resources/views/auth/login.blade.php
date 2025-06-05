@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
  <div class="backdrop-blur-sm bg-white/90 rounded-2xl shadow-lg p-12 w-full max-w-2xl mt-10 min-h-[500px] flex flex-col justify-center">
    <h2 class="text-3xl font-bold text-left text-[#193048] mb-8">Login</h2>

    <form action="/login" method="POST" class="space-y-6">
      @csrf

      <div>
        <label class="block text-base font-semibold text-gray-700 mb-2">Email</label>
        <input type="email" name="email" class="w-full px-4 py-3 rounded bg-gray-100 focus:outline-none" placeholder="Email">
      </div>

      <div>
        <label class="block text-base font-semibold text-gray-700 mb-2">Password</label>
        <input type="password" name="password" class="w-full px-4 py-3 rounded bg-gray-100 focus:outline-none" placeholder="Password">
      </div>

      <button type="submit" class="w-full bg-[#3B9BC8] text-white py-3 rounded-full hover:bg-[#338AB0] transition">
        Login
      </button>
    </form>
  </div>
</div>
@endsection
