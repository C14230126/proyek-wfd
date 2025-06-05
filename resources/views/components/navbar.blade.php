<header class="w-full flex justify-center relative">
  <div class="bg-[#19304B] rounded-full px-10 py-8 shadow-md w-full max-w-[1400px] relative flex items-center justify-between">
    <div class="text-white text-base leading-tight text-left pl-2">
      <div class="font-bold">UPPK</div>
      <div>PETRA</div>
    </div>

    <div class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center space-x-4">
      <img src="{{ asset('images/logo_pcu.webp') }}" alt="Logo Petra" class="h-12">
      <div class="border-l border-[#42556b] h-10 mx-2"></div>
      <div class="text-white text-base leading-tight text-left">
        <div class="font-bold">UPPK</div>
        <div>PETRA</div>
      </div>
    </div>

    <div class="w-[64px]"></div>
  </div>

  @php
    $currentRoute = Request::route()->getName();
  @endphp

  {{-- Tampilkan navbar kecuali di halaman login dan register --}}
  @if (!in_array($currentRoute, ['login', 'register']))
    <nav class="absolute bottom-[-40px] left-1/2 transform -translate-x-1/2 bg-white rounded-full shadow-md px-8 py-4 w-full max-w-[1200px] z-10">
      <div class="flex justify-between items-center text-base font-medium w-full">
        @if ($currentRoute === 'listpeminjaman.index')
          <a href="{{ route('home') }}" class="text-gray-700 hover:text-black">Beranda</a>
          <a href="{{ route('listpeminjaman.index') }}" class="text-gray-700 hover:text-black">List Peminjaman</a>
          <a href="{{ route('listpeminjaman.create')}}" class="text-gray-700 hover:text-black">Buat Peminjaman</a>
        @else
          <a href="{{ route('home') }}" class="text-gray-700 hover:text-black">Beranda</a>
          <a href="{{ route('listpeminjaman.index') }}" class="text-gray-700 hover:text-black">List Peminjaman</a>
          <a href="{{ route('listbarang.index') }}" class="text-gray-700 hover:text-black">List Barang</a>
          <a href="{{ route('listusers.index') }}" class="text-gray-700 hover:text-black">List Users</a>
          <a href="{{ route('pengajuan') }}" class="text-gray-700 hover:text-black">Pengajuan</a>
        @endif
      </div>
    </nav>
  @endif
</header>
