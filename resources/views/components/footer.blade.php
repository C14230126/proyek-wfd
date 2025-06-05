<footer class="bg-[#193048] text-white px-4 py-5">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-8">
    <!-- FOOTER CONTENT (Image) -->
    <div class="space-y-4">
      <img src="{{ asset('UPPK.png') }}" alt="Logo UPPK" class="h-24 mx-auto md:mx-0">
    </div>

    <!-- LINKS -->
    @for ($i = 0; $i < 4; $i++)
      <div>
        <h3 class="text-lg font-semibold mb-3">LINKS</h3>
        <ul class="space-y-2 text-sm text-gray-300">
          <li><a href="#" class="hover:underline">Link 1</a></li>
          <li><a href="#" class="hover:underline">Link 2</a></li>
        </ul>
      </div>
    @endfor
  </div>

  <!-- SIGNUP SECTION -->
  <div class="border-t border-gray-600 mt-10 pt-6 text-center">
    @php
        $currentRoute = Route::currentRouteName();
    @endphp

    @if (!in_array($currentRoute, ['login', 'register']))
    <div class="flex justify-center items-center gap-4 mb-4">
        <p class="m-0 text-sm">Register for free</p>
        <a href="{{ route('register') }}" class="border border-white px-6 py-2 rounded-full hover:bg-white hover:text-[#193048] transition text-sm">
        SIGN UP!
        </a>
    </div>
    <div class="border-t border-gray-600 mt-10 pt-6 text-center">
    @endif

    <!-- Social Icons -->
    <div class="flex justify-center mt-2 space-x-4 text-gray-400">
      <a href="#"><i class="fab fa-whatsapp"></i></a>
      <a href="#"><i class="fab fa-google"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>

    <!-- Copyright -->
    <div class="text-sm text-gray-500 mt-6">
      © 2025 UPPK Petra Christian University. All Rights Reserved.
    </div>
  </div>
</footer>
