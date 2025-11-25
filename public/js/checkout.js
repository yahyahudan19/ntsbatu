// public/js/checkout.js

let currentProduct = null;

// Alert khusus VALIDASI (SweetAlert)
function showValidationError(productName) {
    if (typeof Swal === 'undefined') {
        alert('Lengkapi data checkout terlebih dahulu.'); // fallback kalau SweetAlert belum load
        return;
    }

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

// Tombol +/- varian
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

// Input manual varian
function onVariantQtyInput(index) {
    const input = document.getElementById(`variant-qty-${index}`);
    if (!input) return;

    let val = parseInt(input.value || '0', 10);
    if (isNaN(val) || val < 0) val = 0;
    if (val > 99) val = 99;

    input.value = val;
    updateOrderSummary();
}

// === RINGKASAN PESANAN ===
function updateOrderSummary() {
    if (!currentProduct) return;

    const deliveryDate = document.getElementById('delivery-date')?.value;
    const quantities   = getVariantQuantities();

    const summaryDiv   = document.getElementById('order-summary');
    const totalSection = document.getElementById('total-section');
    const subtotalEl   = document.getElementById('subtotal-price');
    const totalEl      = document.getElementById('total-price');

    if (!summaryDiv || !totalSection || !subtotalEl || !totalEl) return;

    // Kalau belum pilih tanggal atau belum ada qty
    if (!deliveryDate || !quantities.some(q => q > 0)) {
        summaryDiv.innerHTML =
            '<p class="text-gray-500 text-sm">Pilih tanggal dan isi jumlah minimal 1 pada salah satu paket.</p>';
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
            <div class="flex justify-between text-sm mb-2">
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

    const deliveryText = deliveryDate
        ? `<div class="text-xs text-gray-500 mt-1">Tanggal pengiriman: <span class="font-medium text-gray-800">${deliveryDate}</span></div>`
        : '';

    summaryDiv.innerHTML = `
        <div class="mb-3">
            <div class="font-semibold text-gray-900">${currentProduct.name}</div>
            ${deliveryText}
        </div>
        <div class="space-y-1">
            ${itemsHtml}
        </div>
    `;

    subtotalEl.textContent = formatRupiah(total);
    totalEl.textContent    = formatRupiah(total);
    totalSection.classList.remove('hidden');
}

// === INIT FLATPICKR + PRODUCT ===
function initFlatpickr() {
    if (typeof flatpickr === 'undefined') return;

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
        currentProduct = null;
    }
}

// === VALIDASI SEBELUM SUBMIT (tanpa ganggu proses backend) ===
function handleCheckoutSubmit(event) {
    if (!currentProduct) return;

    const deliveryDate  = document.getElementById('delivery-date')?.value?.trim();
    const quantities    = getVariantQuantities();
    const name          = document.getElementById('customer-name')?.value?.trim();
    const whatsapp      = document.getElementById('customer-whatsapp')?.value?.trim();
    const address       = document.getElementById('customer-address')?.value?.trim();
    const paymentMethod = document.getElementById('payment-method')?.value;

    const hasQuantity = quantities.some(q => q > 0);

    if (!deliveryDate || !name || !whatsapp || !address || !paymentMethod || !hasQuantity) {
        event.preventDefault();
        showValidationError(currentProduct.name || 'produk');
        return;
    }

    // kalau semua OK, biarkan form tetap submit ke backend
}

// DOM READY
document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('[data-page="checkout"]');
    if (!page) return;

    initProduct();
    initFlatpickr();
    updateOrderSummary();

    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', handleCheckoutSubmit);
    }
});
