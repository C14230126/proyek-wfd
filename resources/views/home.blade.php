@extends('layouts.app')
@section('content')
<div class=" mt-70 font-bold text-white text-center mb-6">
    <h5 class="text-4xl">Welcome to</h5>
    <h1 class="text-9xl">UPPK Petra </h1>
</div>

<div class="">
  <h2 class="text-center font-bold text-xl mt-10 w-full max-w-2xl mx-auto my-4 backdrop-blur-lg bg-white/50 rounded-lg shadow-lg p-5 text-[#193048]">
    UPPK @Petra Christian University
  </h2>

  <div class="w-full max-w-2xl mx-auto my-4 backdrop-blur-lg bg-white/30 rounded-lg shadow-lg p-6">
    <!-- Label Lokasi -->
    <p class="text-[#193048] font-semibold mb-4">Lokasi:</p>

    <!-- Item 1 -->
    <div class="border-b border-gray-300">
      <button onclick="toggleFaq(1)" class="w-full flex justify-between items-center py-4 text-left text-lg font-medium text-[#193048]">
        UPPK Gedung W
        <span id="icon-1" class="transition-transform">+</span>
      </button>
      <div id="faq-1" class="hidden pb-4 text-gray-700">
        Ruang W103, Gedung W, Universitas Kristen Petra.
      </div>
    </div>

    <!-- Item 2 -->
    <div class="border-b border-gray-300">
      <button onclick="toggleFaq(2)" class="w-full flex justify-between items-center py-4 text-left text-lg font-medium text-[#193048]">
        UPPK Gedung P
        <span id="icon-2" class="transition-transform">+</span>
      </button>
      <div id="faq-2" class="hidden pb-4 text-gray-700">
        Ruang P104, Gedung P, Universitas Kristen Petra.
      </div>
    </div>

    <!-- Item 3 -->
    <div class="border-b border-gray-300">
      <button onclick="toggleFaq(3)" class="w-full flex justify-between items-center py-4 text-left text-lg font-medium text-[#193048]">
        UPPK Gedung Q
        <span id="icon-3" class="transition-transform">+</span>
      </button>
      <div id="faq-3" class="hidden pb-4 text-gray-700">
        Ruang Q201, Gedung Q, Universitas Kristen Petra.
      </div>
    </div>
  </div>
</div>
</div>




</div>
<script>
  function toggleFaq(id) {
    const content = document.getElementById(`faq-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    const isHidden = content.classList.contains('hidden');

    // Tutup semua
    document.querySelectorAll('[id^="faq-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="icon-"]').forEach(el => el.textContent = '+');

    // Buka jika sebelumnya tersembunyi
    if (isHidden) {
      content.classList.remove('hidden');
      icon.textContent = '−';
    }
  }
</script>

@endsection
