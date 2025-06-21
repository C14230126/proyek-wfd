@extends('layouts.app')

@section('content')

<div class="min-h-screen flex justify-center items-center px-4">
    <form action="{{ route('listpeminjaman.store') }}" method="POST"
        class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full max-w-5xl">
        @csrf
        <h2 class="text-2xl font-bold text-[#193048] mb-6">Form Peminjaman</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

            <div class="space-y-4">
                <div class="space-y-4" id="daily-schedule-container">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jadwal Peminjaman Per Hari</label>
                    <div id="daily-time-inputs">
                        <p class="text-gray-500 text-sm">Pilih rentang tanggal untuk mengatur jam pinjam per hari.</p>
                    </div>
                </div>
            </div>

            <div class="col-span-1 md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Barang yang ingin dipinjam</label>

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
                                </td>
                                <td class="border p-1 text-center">
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

        <div class="flex justify-end gap-4 mt-4">
            <a href="{{ route('home') }}" class="px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2 bg-[#3B9BC8] text-white rounded-full hover:bg-[#338AB0] transition-colors">Simpan</button>
        </div>
    </form>
</div>

<script>
    function tambahBarang() {
        const barangList = document.getElementById('barang-list');
        const newRow = document.createElement('tr');

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
        barangList.appendChild(newRow);
    }

    function removeBarang(buttonElement) {
        const rowToRemove = buttonElement.closest('tr');
        if (rowToRemove) {
            const barangList = document.getElementById('barang-list');
            if (barangList.children.length > 1) {
                rowToRemove.remove();
            } else {
                console.log("Tidak bisa menghapus item terakhir. Minimal satu item diperlukan.");
            }
        }
    }

    const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
    const tanggalKembaliInput = document.getElementById('tanggal_kembali');
    const dailyTimeInputsContainer = document.getElementById('daily-time-inputs');
    const form = document.querySelector('form'); 

    function validateTimeInputs(startTimeInput, endTimeInput, errorElement) {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (startTime && endTime) {
            if (startTime >= endTime) {
                errorElement.textContent = 'Jam akhir harus setelah jam awal.';
                errorElement.classList.remove('hidden');
                return false;
            } else {
                errorElement.classList.add('hidden');
                return true;
            }
        }
        errorElement.classList.add('hidden'); 
        return true;
    }

    function generateDailySchedule() {
        const startDate = tanggalPinjamInput.value;
        const endDate = tanggalKembaliInput.value;

        dailyTimeInputsContainer.innerHTML = ''; 

        if (!startDate || !endDate) {
            dailyTimeInputsContainer.innerHTML = '<p class="text-gray-500 text-sm">Pilih rentang tanggal untuk mengatur jam pinjam per hari.</p>';
            return;
        }

        const start = new Date(startDate);
        const end = new Date(endDate);
        
        start.setMinutes(start.getMinutes() + start.getTimezoneOffset());
        end.setMinutes(end.getMinutes() + end.getTimezoneOffset());

        if (start > end) {
            dailyTimeInputsContainer.innerHTML = '<p class="text-red-500 text-sm">Tanggal awal tidak boleh setelah tanggal akhir.</p>';
            return;
        }

        let currentDate = new Date(start);
        let htmlContent = '';
        let hasValidDays = false;

        while (currentDate <= end) {
            if (currentDate.getDay() !== 0) {
                hasValidDays = true;
                const dateString = currentDate.toISOString().split('T')[0]; 
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
                        <p id="time-error-${dateString}" class="text-red-500 text-xs italic mt-1 hidden"></p>
                    </div>
                `;
            }
            currentDate.setDate(currentDate.getDate() + 1); 
        }

        if (!hasValidDays) {
            dailyTimeInputsContainer.innerHTML = '<p class="text-yellow-600 text-sm">Tidak ada hari yang valid untuk peminjaman dalam rentang yang dipilih (tidak termasuk hari Minggu).</p>';
        } else {
            dailyTimeInputsContainer.innerHTML = htmlContent;
            let tempDate = new Date(start);
            while (tempDate <= end) {
                 if (tempDate.getDay() !== 0) { 
                    const dateString = tempDate.toISOString().split('T')[0];
                    const startTimeInput = document.getElementById(`awal_jam_${dateString}`);
                    const endTimeInput = document.getElementById(`akhir_jam_${dateString}`);
                    const errorElement = document.getElementById(`time-error-${dateString}`);

                    if (startTimeInput && endTimeInput && errorElement) {
                        const validate = () => validateTimeInputs(startTimeInput, endTimeInput, errorElement);
                        startTimeInput.addEventListener('change', validate);
                        endTimeInput.addEventListener('change', validate);
                        validate();
                    }
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
        }
    }

    tanggalPinjamInput.addEventListener('change', generateDailySchedule);
    tanggalKembaliInput.addEventListener('change', generateDailySchedule);

    form.addEventListener('submit', function(event) {
        let allTimesValid = true;
        const timeErrorElements = dailyTimeInputsContainer.querySelectorAll('[id^="time-error-"]');
        timeErrorElements.forEach(errorEl => {
            if (!errorEl.classList.contains('hidden')) {
                allTimesValid = false;
            }
        });

        let tempDate = new Date(tanggalPinjamInput.value);
        const endDate = new Date(tanggalKembaliInput.value);
        tempDate.setMinutes(tempDate.getMinutes() + tempDate.getTimezoneOffset());
        endDate.setMinutes(endDate.getMinutes() + endDate.getTimezoneOffset());
        
        while (tempDate <= endDate) {
            if (tempDate.getDay() !== 0) {
                const dateString = tempDate.toISOString().split('T')[0];
                const startTimeInput = document.getElementById(`awal_jam_${dateString}`);
                const endTimeInput = document.getElementById(`akhir_jam_${dateString}`);
                const errorElement = document.getElementById(`time-error-${dateString}`);
                if (startTimeInput && endTimeInput && errorElement) {
                    if (!validateTimeInputs(startTimeInput, endTimeInput, errorElement)) {
                        allTimesValid = false;
                    }
                }
            }
            tempDate.setDate(tempDate.getDate() + 1);
        }

        if (!allTimesValid) {
            event.preventDefault(); 
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                text: 'Periksa kembali jam pinjam Anda. Jam akhir harus setelah jam awal untuk setiap hari.',
                confirmButtonText: 'Oke'
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        generateDailySchedule(); 

        const inputsAndSelects = document.querySelectorAll('input:not([type="date"]):not([type="time"]), select');
        inputsAndSelects.forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
                if (!el.classList.contains('border')) {
                    el.classList.add('border', 'border-gray-300', 'focus:border-blue-500', 'focus:ring-1', 'focus:ring-blue-500');
                }
            }
        });
        document.querySelectorAll('input[type="date"], input[type="time"]').forEach(input => {
            input.classList.add('border', 'border-gray-300', 'focus:border-blue-500', 'focus:ring-1', 'focus:ring-blue-500');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tanggalPinjam = document.getElementById('tanggal_pinjam');
        const tanggalKembali = document.getElementById('tanggal_kembali'); 
        if (!tanggalPinjam || !tanggalKembali) return;

        const today = new Date();
        const todayString = today.toISOString().split('T')[0];

        tanggalPinjam.min = todayString;

        const handleDateChange = (event) => {
            const input = event.target;
            const selectedDate = new Date(input.value);
            selectedDate.setMinutes(selectedDate.getMinutes() + selectedDate.getTimezoneOffset());
            
            if (selectedDate.getDay() === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peminjaman Tidak Tersedia',
                    text: 'Maaf, peminjaman tidak dapat dilakukan pada hari Minggu.',
                    confirmButtonText: 'Mengerti'
                }).then(() => {
                    input.value = ''; 
                    generateDailySchedule();
                });
                return; 
            }

            if (input.id === 'tanggal_pinjam') {
                const todayReset = new Date();
                todayReset.setHours(0, 0, 0, 0);
                
                if (selectedDate < todayReset) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tanggal tidak valid',
                        text: 'Anda tidak bisa memilih tanggal yang sudah lewat',
                        confirmButtonText: 'Mengerti'
                    }).then(() => {
                        this.value = todayString;
                        generateDailySchedule();
                    });
                }
                tanggalKembali.min = input.value;
                if (tanggalKembali.value && new Date(tanggalKembali.value) < selectedDate) {
                    tanggalKembali.value = input.value;
                }
            } else { 
                const selectedPinjamDate = new Date(tanggalPinjam.value);
                if (selectedDate < selectedPinjamDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tanggal tidak valid',
                        text: 'Tanggal akhir tidak bisa sebelum tanggal awal.',
                        confirmButtonText: 'Mengerti'
                    }).then(() => {
                        input.value = tanggalPinjam.value; 
                        generateDailySchedule();
                    });
                }
            }
            generateDailySchedule();
        };

        tanggalPinjam.addEventListener('change', handleDateChange);
        tanggalKembali.addEventListener('change', handleDateChange);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = '{{ session('success') }}';
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                confirmButtonText: 'Oke'
            });
        }

        const errorMessage = '{{ session('error') }}';
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: errorMessage,
                confirmButtonText: 'Oke'
            });
        }

        const stockErrorMessage = '{{ session('stock_error') }}';
        if (stockErrorMessage) {
            Swal.fire({
                icon: 'warning', 
                title: 'Stok Tidak Cukup!',
                text: stockErrorMessage,
                confirmButtonText: 'Mengerti'
            });
        }
    });
</script>
@endsection