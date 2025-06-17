<footer class="bg-[#193048] text-white px-4 py-10">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 items-start">

    <!-- Logo -->
    <div class="space-y-4 text-center md:text-left">
      <a href="{{ url('/') }}">
        <img src="{{ asset('UPPK.png') }}" alt="Logo UPPK" class="h-24 mx-auto md:mx-0">        
      </a>
    </div>
    
    <div>
      <h3 class="text-lg font-semibold mb-3">UPPK W</h3>
      <ul class="space-y-1 text-sm text-gray-300">
        <li>Pak Budi</li>
        <li>
          <a href="https://wa.me/6281234567890" class="hover:underline" target="_blank">
            081234567890
          </a>
        </li>
      </ul>
    </div>
    <div>
      <h3 class="text-lg font-semibold mb-3">UPPK P</h3>
      <ul class="space-y-1 text-sm text-gray-300">
        <li>Pak Arya</li>
        <li>
          <a href="https://wa.me/6281987654321" class="hover:underline" target="_blank">
            081987654321
          </a>
        </li>
      </ul>
    </div>
    <div>
      <h3 class="text-lg font-semibold mb-3">UPPK Q</h3>
      <ul class="space-y-1 text-sm text-gray-300">
        <li>Pak Vincent</li>
        <li>
          <a href="https://wa.me/628289203746" class="hover:underline" target="_blank">
            08289203746
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- SIGNUP -->
  @php $currentRoute = Route::currentRouteName(); @endphp
  @if (!Auth::check() && !in_array($currentRoute, ['login', 'register']))
    <div class="mt-10 text-center">
      <p class="text-sm mb-2">Register for free</p>
      <a href="{{ route('register') }}"
         class="inline-block border border-white px-6 py-2 rounded-full hover:bg-white hover:text-[#193048] transition text-sm">
        SIGN UP!
      </a>
    </div>
  @endif

  <!-- Garis dan Copyright -->
  <div class="border-t border-gray-600 mt-10 pt-6 text-center text-sm text-gray-400">
    © 2025 UPPK Petra Christian University. All Rights Reserved.
  </div>
</footer>
