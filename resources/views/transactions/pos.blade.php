@extends('layouts.app')

@section('title', 'Kasir POS')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kasir (POS)</h1>
        <p class="text-gray-500">Proses transaksi penjualan</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Selection -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search & Scan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex gap-3">
                    <input type="text" id="searchProduct" placeholder="Cari barang..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <button onclick="startBarcodeScan()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                        📷 Scan
                    </button>
                </div>
                <div id="scannerContainer" class="hidden mt-4">
                    <div id="posReader" class="rounded-lg overflow-hidden border border-gray-200 max-w-sm"></div>
                    <button onclick="stopBarcodeScan()" class="mt-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        Stop Scan
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Daftar Barang</h3>
                <div id="productsGrid" class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-96 overflow-y-auto">
                    @foreach($products as $product)
                    <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})"
                        class="p-3 border border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition text-left"
                        data-name="{{ strtolower($product->name) }}">
                        <p class="font-medium text-sm text-gray-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-xs {{ $product->stock <= 10 ? 'text-red-500' : 'text-green-600' }}">Stok: {{ $product->stock }}</p>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Cart & Checkout -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">🛒 Keranjang</h3>

            <div id="cartItems" class="space-y-3 max-h-80 overflow-y-auto mb-4">
                <p class="text-gray-400 text-sm text-center py-8">Keranjang kosong</p>
            </div>

            <div class="border-t pt-4 space-y-3">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total</span>
                    <span id="cartTotal" class="text-emerald-600">Rp 0</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="paymentMethod" onchange="togglePaymentFields()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="tunai">💵 Tunai</option>
                        <option value="non_tunai">💳 Non-Tunai (QRIS/Transfer)</option>
                    </select>
                </div>

                <div id="cashFields">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                    <input type="number" id="paidAmount" oninput="calculateChange()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                        placeholder="0">
                    <div id="changeDisplay" class="mt-2 text-sm hidden">
                        <span class="text-gray-600">Kembali: </span>
                        <span id="changeAmount" class="font-bold text-emerald-600">Rp 0</span>
                    </div>
                </div>

                <button onclick="processTransaction()" id="btnCheckout"
                    class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Bayar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 p-6">
        <div class="text-center mb-4">
            <h3 class="text-lg font-bold">🏪 POS Kelontong</h3>
            <p class="text-sm text-gray-500">Struk Pembayaran</p>
        </div>
        <div id="receiptContent" class="text-sm space-y-2 border-t border-b border-gray-200 py-4">
            <!-- Content via JS -->
        </div>
        <div class="mt-4 flex gap-2">
            <button onclick="closeReceipt()" class="flex-1 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Tutup
            </button>
            <button onclick="window.print()" class="flex-1 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                Print
            </button>
        </div>
    </div>
</div>

<script>

let cart = [];
let html5QrCode = null;

// Search functionality
document.getElementById('searchProduct').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#productsGrid button').forEach(btn => {
        const name = btn.getAttribute('data-name');
        btn.style.display = name.includes(query) ? 'block' : 'none';
    });
});

window.addToCart = function(id, name, price, stock) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            alert('Stok tidak mencukupi!');
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, stock });
    }
    renderCart();
};

window.updateQty = function(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const newQty = item.qty + delta;
    if (newQty <= 0) {
        cart = cart.filter(i => i.id !== id);
    } else if (newQty > item.stock) {
        alert('Stok tidak mencukupi!');
        return;
    } else {
        item.qty = newQty;
    }
    renderCart();
};

window.removeFromCart = function(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
};

function renderCart() {
    const container = document.getElementById('cartItems');
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-sm text-center py-8">Keranjang kosong</p>';
        document.getElementById('cartTotal').textContent = 'Rp 0';
        document.getElementById('btnCheckout').disabled = true;
        return;
    }

    let html = '';
    let total = 0;
    cart.forEach(item => {
        const subtotal = item.price * item.qty;
        total += subtotal;
        html += `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm truncate">${item.name}</p>
                    <p class="text-xs text-gray-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                </div>
                <div class="flex items-center gap-2 ml-3">
                    <button onclick="updateQty(${item.id}, -1)" class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-sm">-</button>
                    <span class="text-sm font-medium w-6 text-center">${item.qty}</span>
                    <button onclick="updateQty(${item.id}, 1)" class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-sm">+</button>
                    <button onclick="removeFromCart(${item.id})" class="ml-2 text-red-500 hover:text-red-700 text-sm">🗑</button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    document.getElementById('cartTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('btnCheckout').disabled = false;
    calculateChange();
}

window.togglePaymentFields = function() {
    const method = document.getElementById('paymentMethod').value;
    const cashFields = document.getElementById('cashFields');
    if (method === 'tunai') {
        cashFields.classList.remove('hidden');
    } else {
        cashFields.classList.add('hidden');
    }
};

window.calculateChange = function() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const change = paid - total;

    const display = document.getElementById('changeDisplay');
    if (paid > 0) {
        display.classList.remove('hidden');
        document.getElementById('changeAmount').textContent = 'Rp ' + Math.max(0, change).toLocaleString('id-ID');
    } else {
        display.classList.add('hidden');
    }
};

window.processTransaction = async function() {
    if (cart.length === 0) return;

    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const paymentMethod = document.getElementById('paymentMethod').value;
    const paidAmount = parseFloat(document.getElementById('paidAmount').value) || total;

    if (paymentMethod === 'tunai' && paidAmount < total) {
        alert('Jumlah bayar kurang dari total!');
        return;
    }

    const items = cart.map(item => ({
        product_id: item.id,
        quantity: item.qty
    }));

    try {
        const response = await fetch('{{ route("transactions.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items,
                payment_method: paymentMethod,
                paid_amount: paidAmount
            })
        });

        const data = await response.json();

        if (data.success) {
            showReceipt(data.transaction);
            cart = [];
            renderCart();
            document.getElementById('paidAmount').value = '';
        } else {
            alert(data.message || 'Transaksi gagal');
        }
    } catch (err) {
        alert('Terjadi kesalahan: ' + err.message);
    }
};

function showReceipt(transaction) {
    let itemsHtml = '';
    transaction.items.forEach(item => {
        itemsHtml += `
            <div class="flex justify-between">
                <span>${item.product.name} × ${item.quantity}</span>
                <span>Rp ${Number(item.subtotal).toLocaleString('id-ID')}</span>
            </div>
        `;
    });

    document.getElementById('receiptContent').innerHTML = `
        <div class="text-center text-xs text-gray-500 mb-2">
            ${transaction.transaction_code}<br>
            ${new Date(transaction.created_at).toLocaleString('id-ID')}
        </div>
        ${itemsHtml}
        <div class="border-t pt-2 mt-2">
            <div class="flex justify-between font-bold">
                <span>Total</span>
                <span>Rp ${Number(transaction.total_amount).toLocaleString('id-ID')}</span>
            </div>
            <div class="flex justify-between">
                <span>Bayar</span>
                <span>Rp ${Number(transaction.paid_amount).toLocaleString('id-ID')}</span>
            </div>
            ${transaction.payment_method === 'tunai' ? `
            <div class="flex justify-between">
                <span>Kembali</span>
                <span>Rp ${Number(transaction.change_amount).toLocaleString('id-ID')}</span>
            </div>
            ` : ''}
            <div class="text-center mt-2 text-xs">
                Metode: ${transaction.payment_method === 'tunai' ? 'Tunai' : 'Non-Tunai'}
            </div>
        </div>
    `;

    document.getElementById('receiptModal').classList.remove('hidden');
    document.getElementById('receiptModal').classList.add('flex');
}

window.closeReceipt = function() {
    document.getElementById('receiptModal').classList.add('hidden');
    document.getElementById('receiptModal').classList.remove('flex');
};

// Barcode scan for POS
window.startBarcodeScan = async function() {
    const container = document.getElementById('scannerContainer');
    container.classList.remove('hidden');

    try {
        html5QrCode = new Html5Qrcode('posReader');
        await html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 200, height: 200 } },
            async (decodedText) => {
                stopBarcodeScan();
                // Find product by barcode
                const response = await fetch('{{ route("products.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ barcode: decodedText })
                });

                if (response.ok) {
                    const data = await response.json();
                    const p = data.product;
                    addToCart(p.id, p.name, parseFloat(p.price), p.stock);
                } else {
                    alert('Barang dengan barcode ' + decodedText + ' tidak ditemukan!');
                }
            },
            () => {}
        );
    } catch (err) {
        alert('Gagal mengakses kamera: ' + err.message);
    }
};

window.stopBarcodeScan = async function() {
    if (html5QrCode) {
        await html5QrCode.stop();
        html5QrCode = null;
    }
    document.getElementById('scannerContainer').classList.add('hidden');
};

// Initialize
renderCart();
</script>
@endsection

