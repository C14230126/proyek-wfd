@extends('layouts.app')


@section('content')
@if (session('success'))
<script>
	Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Oke'
        });
</script>
@endif

<div class="min-h-screen flex justify-center items-center px-4">
	<form action="{{ route('listpeminjaman.store') }}" method="POST"
		class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full max-w-5xl">
		@csrf
		<h2 class="text-2xl font-bold text-[#193048] mb-6">Form Peminjaman</h2>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
			<!-- Kiri -->
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Nama Acara</label>
					<input required type="text" name="nama_acara"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Acara</label>
					<input required type="text" name="lokasi_acara"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Peminjaman Awal</label>
					<input required type="date" name="tanggal_pinjam"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Peminjaman Akhir</label>
					<input required type="date" name="tanggal_kembali"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
			</div>

			<!-- Tengah -->
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Awal Jam Pinjam</label>
					<input required type="time" name="awal_jam_pinjem"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Akhir Jam Pinjam</label>
					<input required type="time" name="akhir_jam_pinjem"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
			</div>

			<!-- Kanan: Tabel Barang -->
			<div class="col-span-1 md:col-span-1">
				<label class="block text-sm font-semibold text-gray-700 mb-2">Barang yang ingin dipinjam</label>

				{{-- Scrollable container for the table --}}
				<div class="overflow-y-auto max-h-56 border border-gray-300 rounded">
					<table class="w-full text-sm"> 
						<thead>
							<tr class="bg-gray-200 sticky top-0 z-10"> {{-- Sticky header for scrolling --}}
								<th class="border px-2 py-1 text-left w-1/2">Nama Barang</th> 
								<th class="border px-2 py-1 text-left w-1/4">Jumlah</th> 
								<th class="border px-2 py-1 w-1/4">Aksi</th>
							</tr>
						</thead>
						<tbody id="barang-list">
							{{-- At least one row should be present initially --}}
							<tr>
								<td class="border p-1">
									<select name="barang_id[]" required
										class="w-full bg-white rounded px-2 py-1 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
										@foreach ($barangs as $barang)
										<option value="{{ $barang->id }}">{{ $barang->item }}</option>
										@endforeach
									</select>
								</td>
								<td class="border p-1">
									<input type="number" name="jumlah[]" required min="1" value="1"
										class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
								</td>
								<td class="border p-1 text-center">
									{{-- The initial row also gets a remove button --}}
									<button type="button" onclick="removeBarang(this)"
										class="text-red-600 hover:text-red-800 text-base font-semibold px-2 py-1 rounded-full leading-none">
										&times;
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<button type="button" onclick="tambahBarang()"
					class="mt-2 text-sm text-blue-600 hover:underline px-3 py-1 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors">
					+ Tambah Barang
				</button>
			</div>
		</div>

		<!-- Tombol -->
		<div class="flex justify-end gap-4 mt-4">
			<a href="{{ route('home') }}"
				class="px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">Batal</a>
			<button type="submit"
				class="px-6 py-2 bg-[#3B9BC8] text-white rounded-full hover:bg-[#338AB0] transition-colors">Simpan</button>
		</div>
	</form>
</div>

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
                <input type="number" name="jumlah[]" required min="1" value="1" class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </td>
            <td class="border p-1 text-center">
                <button type="button" onclick="removeBarang(this)" class="text-red-600 hover:text-red-800 text-base font-semibold px-2 py-1 rounded-full leading-none">
                    &times;
                </button>
            </td>
        `;
        barangList.appendChild(newRow); // Append the new row to the table body
    }

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
                // Optionally, clear the values of the last row instead of removing it,
                // or display a message that at least one item is required.
                // For this example, we just prevent removal of the last row.
                console.log("Cannot remove the last item. At least one item is required.");
                // alert("Minimal harus ada satu barang yang dipinjam."); // Use custom modal for alerts
            }
        }
    }

    // Apply consistent styling on DOMContentLoaded for existing inputs/selects
    document.addEventListener('DOMContentLoaded', function() {
        const inputsAndSelects = document.querySelectorAll('input, select');
        inputsAndSelects.forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
                if (!el.classList.contains('border')) { // Only add if not already present
                    el.classList.add('border', 'border-gray-300', 'focus:border-blue-500', 'focus:ring-1', 'focus:ring-blue-500');
                }
            }
        });
    });
</script>
@endsection