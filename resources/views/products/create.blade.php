@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Baru</h1>
        <p class="text-gray-500">Tambahkan barang dengan form atau scan barcode</p>
    </div>

    <!-- Barcode Scanner Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">📷 Scan Barcode</h2>
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <div id="reader" class="rounded-lg overflow-hidden border border-gray-200"></div>
                <div class="mt-3 flex gap-2">
                    <button type="button" id="startScan" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                        Mulai Scan
                    </button>
                    <button type="button" id="stopScan" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition hidden">
                        Stop Scan
                    </button>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-600 mb-3">Atau masukkan barcode manual:</p>
                <div class="flex gap-2">
                    <input type="text" id="manualBarcode" placeholder="Masukkan barcode..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <button type="button" onclick="checkBarcode()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                        Cek
                    </button>
                </div>
                <p id="scanResult" class="mt-3 text-sm"></p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">📝 Form Barang</h2>
        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang *</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                        placeholder="Contoh: Beras Merah 5kg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                    <input type="text" name="barcode" id="barcodeInput"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none font-mono"
                        placeholder="Scan atau ketik barcode">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                    <input type="number" name="price" required min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                        placeholder="15000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                    <input type="number" name="stock" required min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                        placeholder="100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan *</label>
                    <select name="unit" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="pcs">pcs</option>
                        <option value="pack">pack</option>
                        <option value="botol">botol</option>
                        <option value="karung">karung</option>
                        <option value="kaleng">kaleng</option>
                        <option value="kotak">kotak</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('products.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let html5QrCode = null;
const startBtn = document.getElementById('startScan');
const stopBtn = document.getElementById('stopScan');
const barcodeInput = document.getElementById('barcodeInput');
const scanResult = document.getElementById('scanResult');

startBtn.addEventListener('click', async () => {
    try {
        html5QrCode = new Html5Qrcode('reader');
        await html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                barcodeInput.value = decodedText;
                scanResult.innerHTML = `<span class="text-emerald-600">✓ Barcode terdeteksi: ${decodedText}</span>`;
                stopScan();
            },
            () => {}
        );
        startBtn.classList.add('hidden');
        stopBtn.classList.remove('hidden');
    } catch (err) {
        scanResult.innerHTML = `<span class="text-red-600">✗ Gagal mengakses kamera: ${err.message}</span>`;
    }
});

stopBtn.addEventListener('click', stopScan);

async function stopScan() {
    if (html5QrCode) {
        await html5QrCode.stop();
        html5QrCode = null;
    }
    startBtn.classList.remove('hidden');
    stopBtn.classList.add('hidden');
}

async function checkBarcode() {
    const barcode = document.getElementById('manualBarcode').value;
    if (!barcode) {
        scanResult.innerHTML = `<span class="text-red-600">Masukkan barcode terlebih dahulu</span>`;
        return;
    }

    try {
        const response = await fetch('{{ route("products.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ barcode })
        });

        if (response.ok) {
            const data = await response.json();
            scanResult.innerHTML = `<span class="text-red-600">⚠ Barcode sudah terdaftar: ${data.product.name}</span>`;
        } else {
            barcodeInput.value = barcode;
            scanResult.innerHTML = `<span class="text-emerald-600">✓ Barcode tersedia, silakan lengkapi form</span>`;
        }
    } catch (err) {
        scanResult.innerHTML = `<span class="text-red-600">Terjadi kesalahan</span>`;
    }
}
</script>
@endsection

