@extends('layouts.app')

@section('content')
<div class="relative w-full h-screen bg-cover bg-center z-0">

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white text-center px-4">
        <h2 class="text-xl md:text-2xl font-light mb-2">Introduction to</h2>
        <h1 class="text-5xl md:text-6xl font-extrabold mb-8">UPPK PETRA</h1>

        <div class="flex space-x-6">
            <a href="#" class="bg-white text-black px-8 py-3 rounded-full font-medium hover:bg-gray-100 transition">Login</a>
            <a href="#" class="bg-white text-black px-8 py-3 rounded-full font-medium hover:bg-gray-100 transition">Register</a>
        </div>
    </div>
</div>
@endsection
