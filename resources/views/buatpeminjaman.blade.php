@extends('layouts.app')

@section('content')

<div class="min-h-screen flex justify-center items-center px-4">
    <form action="{{ route('listpeminjaman.store') }}" method="POST"
        class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full max-w-5xl">
        @csrf
        <h2 class="text-2xl font-bold text-[#193048] mb-6">Form Peminjaman</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Kiri -->
            <div class="space-y-4">
                <div>
                    <label for="nama_acara" class="block text-sm font-semibold text-gray-700 mb-1">Nama Acara</label>
                    <input required type="text" id="nama_acara" name="nama_acara"
                        class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label for="lokasi_acara" class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Acara</label>
                    <input required type="text" id="lokasi_acara" name="lokasi_acara"
                        class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label for="tanggal_pinjam" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Peminjaman Awal</label>
                    <input required type="date" id="tanggal_pinjam" name="tanggal_pinjam"
                        class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label for="tanggal_kembali" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Peminjaman Akhir</label>
                    <input required type="date" id="tanggal_kembali" name="tanggal_kembali"
                        class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <!-- Tengah -->
            <div class="space-y-4">
                <!-- Removed global jam pinjam inputs as they will be per-day -->
                <div class="space-y-4" id="daily-schedule-container">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jadwal Peminjaman Per Hari</label>
                    <div id="daily-time-inputs">
                        {{-- Dynamic daily time inputs will be rendered here by JavaScript --}}
                        <p class="text-gray-500 text-sm">Pilih rentang tanggal untuk mengatur jam pinjam per hari.</p>
                    </div>
                </div>
            </div>

            <!-- Kanan: Tabel Barang -->
            <div class="col-span-1 md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Barang yang ingin dipinjam</label>

                {{-- Scrollable container for the table --}}
                <div class="overflow-y-auto max-h-56 border border-gray-300 rounded">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-200 sticky top-0 z-10">
                                <th class="border px-2 py-1 text-left w-1/2">Nama Barang</th>
                                <th class="border px-2 py-1 text-left w-1/4">Jumlah</th>
                                <th class="border px-2 py-1 w-1/4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-list">
                            {{-- At least one row should be present initially --}}
                            <tr>
                                <td class="border p-1">
                                    <select name="barang_id[]" required class="w-full bg-white rounded px-2 py-1 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <input type="number" name="jumlah[]" required min="1" max="5" value="1" class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    {{-- <input type="number" name="jumlah[]" required min="1" value="1" class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"> --}}
                                </td>
                                <td class="border p-1 text-center">
                                    {{-- The initial row also gets a remove button --}}
                                    <button type="button" onclick="removeBarang(this)" class="text-red-600 hover:text-red-800 text-base font-semibold px-2 py-1 rounded-full leading-none">
                                        &times;
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" onclick="tambahBarang()" class="mt-2 text-sm text-blue-600 hover:underline px-3 py-1 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors">
                    + Tambah Barang
                </button>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-4 mt-4">
            <a href="{{ route('home') }}" class="px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2 bg-[#3B9BC8] text-white rounded-full hover:bg-[#338AB0] transition-colors">Simpan</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Function to add a new barang row
function tambahBarang() {
    const barangList = document.getElementById('barang-list');
    const newRow = document.createElement('tr');

    // Populate the inner HTML of the new row
    newRow.innerHTML = `
        <td class="border p-1">
            <select name="barang_id[]" required class="w-full bg-white rounded px-2 py-1 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->item }}</option>
                @endforeach
            </select>
        </td>
        <td class="border p-1">
            <input type="number" name="jumlah[]" required min="1" max="5" value="1" class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        </td>
        <td class="border p-1 text-center">
            <button type="button" onclick="removeBarang(this)" class="text-red-600 hover:text-red-800 text-base font-semibold px-2 py-1 rounded-full leading-none">
                &times;
            </button>
        </td>
    `;
    barangList.appendChild(newRow); // Append the new row to the table body
}
    // Function to add a new barang row
    // function tambahBarang() {
    //     const barangList = document.getElementById('barang-list');
    //     const newRow = document.createElement('tr');

    //     // Populate the inner HTML of the new row
    //     newRow.innerHTML = `
    //         <td class="border p-1">
    //             <select name="barang_id[]" required class="w-full bg-white rounded px-2 py-1 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
    //                 @foreach ($barangs as $barang)
    //                     <option value="{{ $barang->id }}">{{ $barang->item }}</option>
    //                 @endforeach
    //             </select>
    //         </td>
    //         <td class="border p-1">
    //             <input type="number" name="jumlah[]" required min="1" value="1" class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
    //         </td>
    //         <td class="border p-1 text-center">
    //             <button type="button" onclick="removeBarang(this)" class="text-red-600 hover:text-red-800 text-base font-semibold px-2 py-1 rounded-full leading-none">
    //                 &times;
    //             </button>
    //         </td>
    //     `;
    //     barangList.appendChild(newRow); // Append the new row to the table body
    // }

    // Function to remove a barang row
    function removeBarang(buttonElement) {
        // Get the parent <tr> element of the clicked button
        const rowToRemove = buttonElement.closest('tr');
        if (rowToRemove) {
            // Ensure there's always at least one row, or handle empty table gracefully
            const barangList = document.getElementById('barang-list');
            if (barangList.children.length > 1) { // Only remove if more than one row exists
                rowToRemove.remove();
            } else {
                console.log("Cannot remove the last item. At least one item is required.");
            }
        }
    }

    // --- Start: New JavaScript for Daily Schedule Generation ---
    const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
    const tanggalKembaliInput = document.getElementById('tanggal_kembali');
    const dailyTimeInputsContainer = document.getElementById('daily-time-inputs');

    function generateDailySchedule() {
        const startDate = tanggalPinjamInput.value;
        const endDate = tanggalKembaliInput.value;

        dailyTimeInputsContainer.innerHTML = ''; // Clear previous inputs

        if (!startDate || !endDate) {
            dailyTimeInputsContainer.innerHTML = '<p class="text-gray-500 text-sm">Pilih rentang tanggal untuk mengatur jam pinjam per hari.</p>';
            return;
        }

        const start = new Date(startDate);
        const end = new Date(endDate);

        if (start > end) {
            dailyTimeInputsContainer.innerHTML = '<p class="text-red-500 text-sm">Tanggal awal tidak boleh setelah tanggal akhir.</p>';
            return;
        }

        let currentDate = new Date(start);
        let htmlContent = '';

        while (currentDate <= end) {
            const dateString = currentDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
            const readableDate = currentDate.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            htmlContent += `
                <div class="bg-gray-50 p-3 rounded-md border border-gray-200 shadow-sm mb-3">
                    <p class="text-sm font-semibold text-gray-800 mb-2">${readableDate}</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="awal_jam_${dateString}" class="block text-xs font-medium text-gray-600 mb-1">Awal Jam Pinjam</label>
                            <input type="time" id="awal_jam_${dateString}" name="daily_times[${dateString}][start_time]" required
                                class="w-full px-3 py-1 rounded bg-white border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="akhir_jam_${dateString}" class="block text-xs font-medium text-gray-600 mb-1">Akhir Jam Pinjam</label>
                            <input type="time" id="akhir_jam_${dateString}" name="daily_times[${dateString}][end_time]" required
                                class="w-full px-3 py-1 rounded bg-white border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            `;
            currentDate.setDate(currentDate.getDate() + 1); // Move to the next day
        }
        dailyTimeInputsContainer.innerHTML = htmlContent;
    }

    // Event listeners for date changes
    tanggalPinjamInput.addEventListener('change', generateDailySchedule);
    tanggalKembaliInput.addEventListener('change', generateDailySchedule);

    // Initial call to generate schedule if dates are pre-filled (e.g., old() values)
    document.addEventListener('DOMContentLoaded', function() {
        generateDailySchedule(); // Generate schedule on page load

        // Apply consistent styling for existing inputs/selects (moved here for better DOMContentLoaded handling)
        const inputsAndSelects = document.querySelectorAll('input:not([type="date"]):not([type="time"]), select');
        inputsAndSelects.forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
                if (!el.classList.contains('border')) {
                    el.classList.add('border', 'border-gray-300', 'focus:border-blue-500', 'focus:ring-1', 'focus:ring-blue-500');
                }
            }
        });
        // Specific styling for date/time inputs
        document.querySelectorAll('input[type="date"], input[type="time"]').forEach(input => {
            input.classList.add('border', 'border-gray-300', 'focus:border-blue-500', 'focus:ring-1', 'focus:ring-blue-500');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Pastikan elemen ada
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    if (!tanggalPinjam) return;

    const today = new Date();
    const todayString = today.toISOString().split('T')[0];
    
    // Set min date
    tanggalPinjam.min = todayString;
    
    tanggalPinjam.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            // Gunakan Swal.fire bukan SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Tanggal tidak valid',
                text: 'Anda tidak bisa memilih tanggal yang sudah lewat',
                confirmButtonText: 'Mengerti'
            }).then(() => {
                // Reset nilai setelah alert ditutup
                this.value = todayString;
            });
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
        // Cek jika ada pesan 'success' dari session
        const successMessage = '{{ session('success') }}';
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                confirmButtonText: 'Oke'
            });
        }

        // Cek jika ada pesan 'error' dari session
        const errorMessage = '{{ session('error') }}';
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: errorMessage,
                confirmButtonText: 'Oke'
            });
        }

        // Cek jika ada pesan 'stock_error' dari session
        const stockErrorMessage = '{{ session('stock_error') }}';
        if (stockErrorMessage) {
            Swal.fire({
                icon: 'warning', // Atau 'error', 'info'
                title: 'Stok Tidak Cukup!',
                text: stockErrorMessage,
                confirmButtonText: 'Mengerti'
            });
        }

        // ... (kode JavaScript lainnya seperti validasi tanggal pinjam) ...
    });
</script>
@endsection
