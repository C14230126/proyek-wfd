
<div class="w-[1200px] h-[120px] rounded-full absolute top-20 left-1/2 -translate-x-1/2  bg-[#193048] z-0">
    <a href="/">
        <img src="{{ asset('head.png') }}" alt="Logo" class="w-150 h-25 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 align-center">
    </a>
        <!-- BURGER BUTTON -->
    <div class="relative">
        <button onclick="toggleMenu()" class="text-white text-3xl focus:outline-none absolute top-10 left-15 ">
        ☰
        </button>
        <!-- DROPDOWN MENU -->
        <div id="burgerMenu" class="hidden absolute top-20 left-15 bg-white rounded-lg shadow-md text-black w-48">
        <a href="/list" class="block px-4 py-2 hover:bg-gray-100">List Peminjaman</a>
        <a href="/buat" class="block px-4 py-2 hover:bg-gray-100">Buat Peminjaman</a>
        </div>
    </div>
    


</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById("burgerMenu");
        menu.classList.toggle("hidden");
    }
</script>