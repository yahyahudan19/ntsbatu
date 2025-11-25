// public/js/checkout.js

const PREORDER_DAYS = 7;
const MAX_STOCK_PER_DAY = 20;

let currentProduct = null;

// Alert helper (INLINE di halaman, pakai #alert-box)
function showAlert(type, message) {
    const alertBox = document.getElementById('alert-box');
    if (!alertBox) return;

    const base = 'border px-4 py-3 rounded text-sm ';
    let style = '';

    if (type === 'success') {
        style = 'bg-green-50 border-green-400 text-green-800';
    } else if (type === 'warning') {
        style = 'bg-yellow-50 border-yellow-400 text-yellow-800';
    } else {
        style = 'bg-red-50 border-red-400 text-red-800';
    }

    alertBox.className = 'alert-box mb-4 ' + base + style;
    alertBox.innerHTML = message;
}

// Alert khusus VALIDASI (SweetAlert)
function showValidationError(productName) {
    Swal.fire({
        icon: 'error',
        title: 'Wah Maaf',
        text: `Lengkapi data di bawah untuk menyelesaikan pre-order ${productName}`,
        confirmButtonColor: '#16a34a',
        confirmButtonText: 'Mengerti',
        width: 380,
    });
}

// Format rupiah
function formatRupiah(amount) {
    return `Rp ${amount.toLocaleString('id-ID')}`;
}

// === MULTI VARIAN ===

// Ambil qty per varian dari input variant-qty-{i}
function getVariantQuantities() {
    if (!currentProduct || !currentProduct.packages) return [];

    return currentProduct.packages.map((pkg, i) => {
        const input = document.getElementById(`variant-qty-${i}`);
        const qty = input ? parseInt(input.value || '0', 10) : 0;
        return isNaN(qty) ? 0 : qty;
    });
}

// Tombol +/-
function changeVariantQty(index, delta) {
    const input = document.getElementById(`variant-qty-${index}`);
    if (!input) return;

    let current = parseInt(input.value || '0', 10);
    if (isNaN(current)) current = 0;

    let next = current + delta;
    if (next < 0) next = 0;
    if (next > 99) next = 99;

    input.value = next;
    updateOrderSummary();
}

// Input manual
function onVariantQtyInput(index) {
    const input = document.getElementById(`variant-qty-${index}`);
    if (!input) return;

    let val = parseInt(input.value || '0', 10);
    if (isNaN(val) || val < 0) val = 0;
    if (val > 99) val = 99;

    input.value = val;
    updateOrderSummary();
}

// stok simulasi (kalau mau pakai nanti)
function getStockForDate(date, packageIndex) {
    return MAX_STOCK_PER_DAY;
}

// === RINGKASAN PESANAN ===
function updateOrderSummary() {
    if (!currentProduct) return;

    const deliveryDate = document.getElementById('delivery-date').value;
    const quantities   = getVariantQuantities();

    const summaryDiv   = document.getElementById('order-summary');
    const totalSection = document.getElementById('total-section');
    const subtotalEl   = document.getElementById('subtotal-price');
    const totalEl      = document.getElementById('total-price');

    if (!deliveryDate || !quantities.some(q => q > 0)) {
        summaryDiv.innerHTML =
            '<p class="text-gray-600 text-sm">Pilih tanggal dan isi jumlah minimal 1 pada salah satu paket.</p>';
        totalSection.classList.add('hidden');
        return;
    }

    let total = 0;
    let itemsHtml = '';

    quantities.forEach((qty, i) => {
        if (qty <= 0) return;
        const pkg = currentProduct.packages[i];
        const sub = pkg.price * qty;
        total += sub;

        itemsHtml += `
            <div class="flex justify-between text-sm mb-1">
                <div>
                    <div class="font-medium text-gray-900">${pkg.label}</div>
                    <div class="text-xs text-gray-600">${qty} pack x ${formatRupiah(pkg.price)}</div>
                </div>
                <div class="font-semibold text-gray-900">
                    ${formatRupiah(sub)}
                </div>
            </div>
        `;
    });

    summaryDiv.innerHTML = `
        <div class="mb-3">
            <div class="font-semibold text-gray-900">${currentProduct.name}</div>
            <div class="text-xs text-gray-500">Tanggal pengiriman: ${deliveryDate}</div>
        </div>
        <div class="space-y-2">
            ${itemsHtml}
        </div>
    `;

    subtotalEl.textContent = formatRupiah(total);
    totalEl.textContent    = formatRupiah(total);
    totalSection.classList.remove('hidden');
}

// === QRIS MODAL (simulasi) ===
function openQrisModal() {
    const modal = document.getElementById('qris-modal');
    if (!modal) return;
    modal.classList.add('active');
}

function closeQrisModal() {
    const modal = document.getElementById('qris-modal');
    if (!modal) return;
    modal.classList.remove('active');
}

function simulateQrisPaid() {
    closeQrisModal();
    showAlert('success', '✅ Simulasi: pembayaran QRIS berhasil. Nanti diganti callback gateway.');
}

// === SUBMIT CHECKOUT ===
function handleCheckoutSubmit() {
    if (!currentProduct) return;

    const deliveryDate  = document.getElementById('delivery-date').value;
    const quantities    = getVariantQuantities();
    const name          = document.getElementById('customer-name').value.trim();
    const whatsapp      = document.getElementById('customer-whatsapp').value.trim();
    const address       = document.getElementById('customer-address').value.trim();
    const paymentMethod = document.getElementById('payment-method').value;

    // VALIDASI KOSONG
    if (!deliveryDate || !name || !whatsapp || !address || !quantities.some(q => q > 0)) {
        showValidationError(currentProduct.name);
        return;
    }

    let total = 0;
    let itemLines = '';

    quantities.forEach((qty, i) => {
        if (qty <= 0) return;
        const pkg = currentProduct.packages[i];
        const sub = pkg.price * qty;
        total += sub;
        itemLines += `- ${pkg.label}: ${qty} pack x ${formatRupiah(pkg.price)} = ${formatRupiah(sub)}\n`;
    });

    const header =
        `Halo kak, saya ingin pre-order:\n\n` +
        `*Produk*: ${currentProduct.name}\n` +
        `*Detail Paket:*\n${itemLines}\n` +
        `*Tanggal Pengiriman*: ${deliveryDate}\n` +
        `*Total*: ${formatRupiah(total)}\n\n` +
        `*Nama*: ${name}\n` +
        `*WhatsApp*: ${whatsapp}\n` +
        `*Alamat*: ${address}\n\n` +
        `Metode bayar: ${paymentMethod === 'cod' ? 'COD / Cash' : 'QRIS (simulasi)'} .`;

    if (paymentMethod === 'cod') {
        const waNumber = '6281234567890'; // TODO: ganti dengan nomor kamu
        const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(header)}`;
        window.open(waUrl, '_blank', 'noopener,noreferrer');
        showAlert('success', '✅ Pesanan COD Anda telah dikirim ke WhatsApp.');
    } else {
        window.__lastOrderForQris = {
            text: header,
            total,
        };
        openQrisModal();
    }
}

// === INIT FLATPICKR + PRODUCT ===
function initFlatpickr() {
    const today = new Date();

    const minDate = new Date();
    minDate.setDate(today.getDate() + 1);

    const maxDate = new Date();
    maxDate.setDate(today.getDate() + 7);

    flatpickr("#delivery-date", {
        locale: "id",
        dateFormat: "Y-m-d",
        minDate: minDate,
        maxDate: maxDate,
        disableMobile: true,
        defaultDate: minDate,
        onChange: updateOrderSummary
    });
}

function initProduct() {
    const el = document.getElementById('product-data');
    if (!el) return;

    try {
        currentProduct = JSON.parse(el.value);
    } catch (e) {
        console.error('Gagal parse product-data', e);
    }
}

// DOM READY
document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('[data-page="checkout"]');
    if (!page) return;

    initProduct();
    initFlatpickr();
    updateOrderSummary();
});
