@extends('layouts.app')

@section('content')
<div class="min-h-screen flex justify-center items-center px-4">
	<form action="#" method="POST" class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full max-w-5xl">
		@csrf
		<h2 class="text-2xl font-bold text-[#193048] mb-6">Form Peminjaman</h2>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
			<!-- Kiri -->
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Nama Acara</label>
					<input type="text" name="nama_acara"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Nama Peminjam</label>
					<input type="text" name="nama_peminjam"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">NRP</label>
					<input type="text" name="nrp" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
			</div>

			<!-- Tengah -->
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pinjam</label>
					<input type="date" name="tanggal_pinjam"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-1">Waktu Pengembalian</label>
					<input type="date" name="tanggal_kembali"
						class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
				</div>
			</div>

			<!-- Kanan: Tabel Barang -->
			<div class="overflow-y-auto max-h-56 border border-gray-300 rounded">
				<label class="block text-sm font-semibold text-gray-700 mb-2">Barang yang ingin dipinjam</label>
				<table class="w-full text-sm"> {{-- Removed border from table, moved to wrapper --}}
					<thead>
						<tr class="bg-gray-200 sticky top-0 z-10"> {{-- Sticky header for scrolling --}}
							<th class="border px-2 py-1 text-left w-1/2">Nama Barang</th> {{-- Adjusted width for better
							fit --}}
							<th class="border px-2 py-1 text-left w-1/4">Jumlah</th> {{-- Adjusted width for better fit
							--}}
							<th class="border px-2 py-1 w-1/4">Aksi</th> {{-- Adjusted width for better fit --}}
						</tr>
					</thead>
					<tbody id="barang-list">
						<tr>
							<td class="border p-1">
								<select name="barang_id[]"
									class="w-full bg-white rounded px-2 py-1 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
									@foreach ($barangs as $barang)
									<option value="{{ $barang->id }}">{{ $barang->item }}</option>
									@endforeach
								</select>
							</td>
							<td class="border p-1">
								<input type="number" name="jumlah[]" min="1" value="1"
									class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
							</td>
							<td class="border p-1 text-center">
								<button type="button" onclick="removeBarang(this)"
									class="text-red-600 hover:text-red-800 text-base font-semibold px-2 py-1 rounded-full leading-none">
									&times;
								</button>
							</td>
						</tr>
					</tbody>
				</table>

				<button type="button" onclick="tambahBarang()" class="mt-2 text-sm text-blue-600 hover:underline px-3 py-1 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors">
                    + Tambah Barang
                </button>
			</div>

			<!-- Tombol -->
			<div class="flex justify-end gap-4 mt-4">
				<a href="{{ route('home') }}"
					class="px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600">Batal</a>
				<button type="submit"
					class="px-6 py-2 bg-[#3B9BC8] text-white rounded-full hover:bg-[#338AB0]">Simpan</button>
			</div>
	</form>
</div>

<script>
	// Function to add a new barang row
    function tambahBarang() {
        const barangList = document.getElementById('barang-list');
        const newRow = document.createElement('tr'); // Create a new table row element

        // Populate the inner HTML of the new row
        newRow.innerHTML = `
            <td class="border p-1">
                <select name="barang_id[]" class="w-full bg-white rounded px-2 py-1 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->item }}</option>
                    @endforeach
                </select>
            </td>
            <td class="border p-1">
                <input type="number" name="jumlah[]" min="1" value="1" class="w-full px-2 py-1 rounded bg-gray-100 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
            rowToRemove.remove(); // Remove the row from the DOM
        }
    }

    // Minor style enhancements for existing inputs (ensure consistency if needed)
    document.addEventListener('DOMContentLoaded', function() {
        // This part is less critical now as common styles are applied directly in the HTML.
        // It's good practice to ensure all inputs have a consistent look.
        // Example for additional consistency:
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