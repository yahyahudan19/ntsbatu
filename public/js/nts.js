const defaultConfig = {
    store_name: "NTS Batu",
    hero_title: "Buah Segar Berkualitas Premium",
    hero_subtitle: "Nikmati kesegaran strawberry segar, strawberry frozen, dan murbei pilihan langsung dari kebun kami di Batu, Malang",
    strawberry_title: "Strawberry Segar",
    murbei_title: "Murbei Segar",
    strawberry_frozen_title: "Strawberry Frozen",
    checkout_title: "Checkout Pesanan",
    form_name_label: "Nama Lengkap",
    form_whatsapp_label: "Nomor WhatsApp",
    form_address_label: "Alamat Pengiriman (Kota Batu)",
    submit_button_text: "Proses Pesanan",
    background_color: "#f8f9fa",
    card_color: "#ffffff",
    text_color: "#212529",
    primary_button_color: "#495057",
    accent_color: "#6c757d",
    font_family: "Inter",
    font_size: 16
};

let currentProduct = null;
let paymentCheckInterval = null;
let currentOrderId = null;

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const hamburger = document.getElementById('hamburger');

    mobileMenu.classList.toggle('active');
    overlay.classList.toggle('active');
    hamburger.classList.toggle('active');

    if (mobileMenu.classList.contains('active')) {
    document.body.style.overflow = 'hidden';
    } else {
    document.body.style.overflow = '';
    }
}

function closeMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const hamburger = document.getElementById('hamburger');

    mobileMenu.classList.remove('active');
    overlay.classList.remove('active');
    hamburger.classList.remove('active');
    document.body.style.overflow = '';
}

function showSweetModal() {
    document.getElementById('sweet-modal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSweetModal() {
    document.getElementById('sweet-modal').classList.remove('active');
    document.body.style.overflow = '';
}

const products = {
    strawberry: {
    name: "Strawberry Segar",
    packages: [
        { name: "Pack Kecil (250g)", price: 35000, maxStock: 20 },
        { name: "Pack Besar (500g)", price: 65000, maxStock: 20 },
        { name: "Paket Hemat (2 Pack Besar)", price: 110000, maxStock: 20 }
    ]
    },
    murbei: {
    name: "Murbei Segar",
    packages: [
        { name: "Pack 250g", price: 17000, maxStock: 20 }
    ]
    },
    strawberry_frozen: {
    name: "Strawberry Frozen",
    packages: [
        { name: "1 Pack (1kg)", price: 30000, maxStock: 20 },
        { name: "2kg", price: 55000, maxStock: 20 },
        { name: "3kg", price: 85000, maxStock: 20 }
    ]
    }
};

// Pre-order settings
const PREORDER_DAYS = 3;
const OPEN_HOUR = 9;
const CLOSE_HOUR = 20;

// Mock stock data
let stockData = {};

function initializeStockData() {
    const dates = getAvailableDates();
    dates.forEach(date => {
    stockData[date] = {
        strawberry: [20, 20, 20],
        murbei: [20],
        strawberry_frozen: [20, 20, 20]
    };
    });
}

function isWithinOperationalHours() {
    const now = new Date();
    const currentHour = now.getHours();
    return currentHour >= OPEN_HOUR && currentHour < CLOSE_HOUR;
}

function getAvailableDates() {
    const dates = [];
    const today = new Date();

    for (let i = 1; i <= PREORDER_DAYS; i++) {
    const date = new Date(today);
    date.setDate(date.getDate() + i);
    dates.push(formatDate(date));
    }

    return dates;
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatDateIndonesian(dateStr) {
    const date = new Date(dateStr + 'T00:00:00');
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

function getStockForDate(date, productType, packageIndex) {
    if (!stockData[date]) return 0;
    return stockData[date][productType][packageIndex];
}

function selectProduct(productType) {
    if (!isWithinOperationalHours()) {
    showSweetModal();
    return;
    }

    currentProduct = productType;

    // Hide landing page
    const landingPage = document.getElementById('landing-page');
    if (landingPage) {
    landingPage.style.display = 'none';
    }

    // Show checkout page
    const checkoutPage = document.getElementById('checkout-page');
    if (checkoutPage) {
    checkoutPage.style.display = 'block';
    }

    // Initialize data
    initializeStockData();
    populateDateOptions();
    hideAlert();

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function populateDateOptions() {
    const dateSelect = document.getElementById('delivery-date');
    dateSelect.innerHTML = '<option value="">-- Pilih Tanggal --</option>';

    const dates = getAvailableDates();
    dates.forEach(date => {
    const option = document.createElement('option');
    option.value = date;
    option.textContent = formatDateIndonesian(date);
    dateSelect.appendChild(option);
    });
}

function updateStockInfo() {
    const selectedDate = document.getElementById('delivery-date').value;

    if (!selectedDate) {
    document.getElementById('package-selection').innerHTML = '';
    document.getElementById('order-summary').innerHTML = '<p class="text-gray-600 text-center py-8">Pilih tanggal dan paket terlebih dahulu</p>';
    document.getElementById('total-section').style.display = 'none';
    return;
    }

    const product = products[currentProduct];
    const packageSelectionHtml = `
    <label class="block text-gray-900 font-bold mb-3">Pilih Paket</label>
    <div class="space-y-3">
        ${product.packages.map((pkg, index) => {
    const stock = getStockForDate(selectedDate, currentProduct, index);
    const stockClass = stock > 10 ? 'stock-available' : stock > 0 ? 'stock-low' : 'stock-out';
    const isDisabled = stock === 0;

    return `
            <div class="p-4 border-2 ${isDisabled ? 'border-gray-200 bg-gray-50 opacity-60' : 'border-gray-300 bg-white'} rounded-lg">
            <div class="flex items-center justify-between mb-2">
                <label class="flex items-center gap-3 flex-1 cursor-pointer">
                <input 
                    type="radio" 
                    name="package" 
                    value="${index}"
                    ${isDisabled ? 'disabled' : ''}
                    ${index === 0 && !isDisabled ? 'checked' : ''}
                    onchange="updateOrderSummary()"
                    class="w-5 h-5 text-gray-800">
                <div class="flex-1">
                    <div class="font-bold text-gray-900">${pkg.name}</div>
                    <div class="text-lg font-semibold text-gray-900">Rp ${pkg.price.toLocaleString('id-ID')}</div>
                </div>
                </label>
                <span class="stock-badge ${stockClass}">
                ${stock > 0 ? `Stok: ${stock}` : 'Habis'}
                </span>
            </div>
            </div>
        `;
    }).join('')}
    </div>

    <div class="mt-6">
        <label class="block text-gray-700 font-medium mb-3">Jumlah Pesanan</label>
        <div class="flex items-center justify-center gap-4">
        <button type="button" onclick="decreaseQuantity()" class="w-12 h-12 bg-gray-200 rounded-lg font-bold text-xl hover:bg-gray-300 transition-colors">−</button>
        <input type="number" id="quantity" value="1" min="1" max="99" onchange="updateOrderSummary()" class="w-24 text-center border-2 border-gray-300 rounded-lg py-3 font-bold text-xl">
        <button type="button" onclick="increaseQuantity()" class="w-12 h-12 bg-gray-200 rounded-lg font-bold text-xl hover:bg-gray-300 transition-colors">+</button>
        </div>
    </div>
    `;

    document.getElementById('package-selection').innerHTML = packageSelectionHtml;
    updateOrderSummary();
}

function showAlert(type, message) {
    const alertBox = document.getElementById('alert-box');
    alertBox.className = `alert-box alert-${type}`;
    alertBox.textContent = message;
    alertBox.style.display = 'block';
}

function hideAlert() {
    const alertBox = document.getElementById('alert-box');
    alertBox.style.display = 'none';
}

function updateOrderSummary() {
    const selectedDate = document.getElementById('delivery-date').value;
    const selectedPackage = document.querySelector('input[name="package"]:checked');
    const quantityInput = document.getElementById('quantity');

    if (!selectedDate || !selectedPackage || !quantityInput) {
    document.getElementById('order-summary').innerHTML = '<p class="text-gray-600 text-center py-8">Pilih tanggal dan paket terlebih dahulu</p>';
    document.getElementById('total-section').style.display = 'none';
    return;
    }

    const packageIndex = parseInt(selectedPackage.value);
    const quantity = parseInt(quantityInput.value) || 1;
    const product = products[currentProduct];
    const selectedPkg = product.packages[packageIndex];
    const availableStock = getStockForDate(selectedDate, currentProduct, packageIndex);

    if (quantity > availableStock) {
    showAlert('error', `⚠️ Stok tidak mencukupi! Hanya tersedia ${availableStock} pack untuk tanggal ini.`);
    quantityInput.value = availableStock;
    return;
    } else {
    hideAlert();
    }

    const total = selectedPkg.price * quantity;

    const summaryHtml = `
    <div class="space-y-3">
        <div class="pb-3 border-b">
        <div class="font-semibold text-gray-900 mb-1">${product.name}</div>
        <div class="text-sm text-gray-600">${selectedPkg.name}</div>
        </div>
        <div class="flex justify-between text-sm">
        <span class="text-gray-600">Tanggal Pengiriman</span>
        <span class="font-medium text-gray-900">${formatDateIndonesian(selectedDate).split(',')[0]}</span>
        </div>
        <div class="flex justify-between text-sm">
        <span class="text-gray-600">Harga Satuan</span>
        <span class="font-medium text-gray-900">Rp ${selectedPkg.price.toLocaleString('id-ID')}</span>
        </div>
        <div class="flex justify-between text-sm">
        <span class="text-gray-600">Jumlah</span>
        <span class="font-medium text-gray-900">${quantity} pack</span>
        </div>
    </div>
    `;

    document.getElementById('order-summary').innerHTML = summaryHtml;
    document.getElementById('subtotal-price').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('total-price').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('total-section').style.display = 'block';
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    const selectedDate = document.getElementById('delivery-date').value;
    const selectedPackage = document.querySelector('input[name="package"]:checked');

    if (selectedDate && selectedPackage) {
    const packageIndex = parseInt(selectedPackage.value);
    const availableStock = getStockForDate(selectedDate, currentProduct, packageIndex);

    if (current < availableStock && current < 99) {
        input.value = current + 1;
        updateOrderSummary();
    } else {
        showAlert('warning', `⚠️ Maksimal pemesanan untuk paket ini adalah ${availableStock} pack.`);
    }
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
    input.value = current - 1;
    updateOrderSummary();
    hideAlert();
    }
}

function backToLanding() {
    document.getElementById('landing-page').style.display = 'block';
    document.getElementById('checkout-page').style.display = 'none';
    currentProduct = null;
    hideAlert();
    window.scrollTo(0, 0);
}

function handleCheckout(event) {
    event.preventDefault();

    const selectedDate = document.getElementById('delivery-date').value;
    const selectedPackage = document.querySelector('input[name="package"]:checked');
    const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;

    if (!selectedDate) {
    showAlert('error', '⚠️ Silakan pilih tanggal pengiriman terlebih dahulu.');
    return;
    }

    if (!selectedPackage) {
    showAlert('error', '⚠️ Silakan pilih paket yang diinginkan.');
    return;
    }

    const name = document.getElementById('full-name').value;
    const whatsapp = document.getElementById('whatsapp').value;
    const email = document.getElementById('email').value;
    const address = document.getElementById('address').value;

    const packageIndex = parseInt(selectedPackage.value);
    const quantity = parseInt(document.getElementById('quantity').value) || 1;

    const product = products[currentProduct];
    const selectedPkg = product.packages[packageIndex];
    const total = selectedPkg.price * quantity;
    const deliveryDate = formatDateIndonesian(selectedDate);

    if (paymentMethod === 'qris') {
    processQrisPayment({
        name,
        email,
        whatsapp,
        address,
        product: product.name,
        package: selectedPkg.name,
        quantity,
        total,
        deliveryDate: selectedDate
    });
    } else {
    processCodOrder({
        name,
        whatsapp,
        address,
        product: product.name,
        package: selectedPkg.name,
        quantity,
        total,
        deliveryDate
    });
    }

    stockData[selectedDate][currentProduct][packageIndex] -= quantity;
}

function processCodOrder(orderData) {
    const message = `Halo, saya ingin pre-order (COD/Cash):%0A%0A` +
    `*Produk:* ${orderData.product}%0A` +
    `*Paket:* ${orderData.package}%0A` +
    `*Jumlah:* ${orderData.quantity} pack%0A` +
    `*Tanggal Pengiriman:* ${orderData.deliveryDate}%0A` +
    `*Total:* Rp ${orderData.total.toLocaleString('id-ID')}%0A` +
    `*Metode Pembayaran:* COD/Cash%0A%0A` +
    `*Nama:* ${orderData.name}%0A` +
    `*WhatsApp:* ${orderData.whatsapp}%0A` +
    `*Alamat:* ${orderData.address}`;

    const whatsappNumber = '6281234567890';
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;

    window.open(whatsappUrl, '_blank', 'noopener,noreferrer');

    showAlert('success', '✅ Pesanan COD Anda telah dikirim ke WhatsApp. Kami akan segera menghubungi Anda!');
}

async function processQrisPayment(orderData) {
    showQrisModal();

    currentOrderId = 'ORD' + Date.now();

    setTimeout(() => {
    document.getElementById('qris-loading').style.display = 'none';
    document.getElementById('qris-content').style.display = 'block';
    document.getElementById('qris-amount').textContent = `Rp ${orderData.total.toLocaleString('id-ID')}`;
    document.getElementById('order-id').textContent = currentOrderId;

    const expiryTime = new Date(Date.now() + 15 * 60 * 1000);
    document.getElementById('qris-expiry').textContent = expiryTime.toLocaleTimeString('id-ID');

    startPaymentStatusCheck(orderData);
    }, 2000);
}

function showQrisModal() {
    document.getElementById('qris-modal').classList.add('active');
    document.getElementById('qris-loading').style.display = 'flex';
    document.getElementById('qris-content').style.display = 'none';
}

function closeQrisModal() {
    document.getElementById('qris-modal').classList.remove('active');
    if (paymentCheckInterval) {
    clearInterval(paymentCheckInterval);
    paymentCheckInterval = null;
    }
}

function startPaymentStatusCheck(orderData) {
    let checkCount = 0;
    const maxChecks = 30;

    paymentCheckInterval = setInterval(async () => {
    checkCount++;

    const paymentSuccess = Math.random() > 0.95;

    if (paymentSuccess) {
        clearInterval(paymentCheckInterval);
        handlePaymentSuccess(orderData);
    } else if (checkCount >= maxChecks) {
        clearInterval(paymentCheckInterval);
        handlePaymentTimeout();
    }
    }, 5000);
}

function handlePaymentSuccess(orderData) {
    document.getElementById('payment-status').className = 'payment-status status-success';
    document.getElementById('payment-status').textContent = '✅ Pembayaran Berhasil!';

    setTimeout(() => {
    const message = `Pembayaran QRIS Berhasil!%0A%0A` +
        `*Order ID:* ${currentOrderId}%0A` +
        `*Produk:* ${orderData.product}%0A` +
        `*Paket:* ${orderData.package}%0A` +
        `*Jumlah:* ${orderData.quantity} pack%0A` +
        `*Total:* Rp ${orderData.total.toLocaleString('id-ID')}%0A` +
        `*Tanggal Pengiriman:* ${formatDateIndonesian(orderData.deliveryDate)}%0A%0A` +
        `*Nama:* ${orderData.name}%0A` +
        `*Email:* ${orderData.email}%0A` +
        `*WhatsApp:* ${orderData.whatsapp}%0A` +
        `*Alamat:* ${orderData.address}`;

    const whatsappNumber = '6281234567890';
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;

    window.open(whatsappUrl, '_blank', 'noopener,noreferrer');

    closeQrisModal();
    showAlert('success', '✅ Pembayaran berhasil! Detail pesanan telah dikirim ke WhatsApp.');
    }, 2000);
}

function handlePaymentTimeout() {
    document.getElementById('payment-status').className = 'payment-status status-pending';
    document.getElementById('payment-status').innerHTML = '⏳ Menunggu pembayaran...<br><small>Refresh otomatis setiap 5 detik</small>';
}

async function onConfigChange(config) {
    const customFont = config.font_family || defaultConfig.font_family;
    const baseFontStack = '-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
    const baseSize = config.font_size || defaultConfig.font_size;

    document.body.style.fontFamily = `${customFont}, ${baseFontStack}`;
    document.body.style.backgroundColor = config.background_color || defaultConfig.background_color;

    // Header
    const storeName = document.getElementById('store-name');
    storeName.textContent = config.store_name || defaultConfig.store_name;
    storeName.style.fontSize = `${baseSize * 1.5}px`;
    storeName.style.color = config.text_color || defaultConfig.text_color;

    // Hero Section - Text berwarna putih untuk kontras dengan background
    const heroTitle = document.getElementById('hero-title');
    heroTitle.textContent = config.hero_title || defaultConfig.hero_title;
    heroTitle.style.fontSize = `${baseSize * 3.75}px`;
    heroTitle.style.color = '#ffffff';

    const heroSubtitle = document.getElementById('hero-subtitle');
    heroSubtitle.textContent = config.hero_subtitle || defaultConfig.hero_subtitle;
    heroSubtitle.style.fontSize = `${baseSize * 1.5}px`;
    heroSubtitle.style.color = '#ffffff';

    // Product Titles
    const strawberryTitle = document.getElementById('strawberry-title');
    strawberryTitle.textContent = config.strawberry_title || defaultConfig.strawberry_title;
    strawberryTitle.style.fontSize = `${baseSize * 1.5}px`;
    strawberryTitle.style.color = config.text_color || defaultConfig.text_color;

    const murbeiTitle = document.getElementById('murbei-title');
    murbeiTitle.textContent = config.murbei_title || defaultConfig.murbei_title;
    murbeiTitle.style.fontSize = `${baseSize * 1.5}px`;
    murbeiTitle.style.color = config.text_color || defaultConfig.text_color;

    const strawberryFrozenTitle = document.getElementById('strawberry-frozen-title');
    strawberryFrozenTitle.textContent = config.strawberry_frozen_title || defaultConfig.strawberry_frozen_title;
    strawberryFrozenTitle.style.fontSize = `${baseSize * 1.5}px`;
    strawberryFrozenTitle.style.color = config.text_color || defaultConfig.text_color;

    // Checkout Page
    const checkoutTitle = document.getElementById('checkout-title');
    checkoutTitle.textContent = config.checkout_title || defaultConfig.checkout_title;
    checkoutTitle.style.fontSize = `${baseSize * 1.875}px`;
    checkoutTitle.style.color = config.text_color || defaultConfig.text_color;

    // Form Labels
    document.getElementById('form-name-label').textContent = config.form_name_label || defaultConfig.form_name_label;
    document.getElementById('form-whatsapp-label').textContent = config.form_whatsapp_label || defaultConfig.form_whatsapp_label;
    document.getElementById('form-address-label').textContent = config.form_address_label || defaultConfig.form_address_label;

    // Submit Button
    const submitButton = document.getElementById('submit-button');
    submitButton.textContent = config.submit_button_text || defaultConfig.submit_button_text;
    submitButton.style.fontSize = `${baseSize}px`;

    // Apply colors
    const allCards = document.querySelectorAll('.product-card, .order-summary, .testimonial-card');
    allCards.forEach(card => {
    card.style.backgroundColor = config.card_color || defaultConfig.card_color;
    });

    const allButtons = document.querySelectorAll('.btn-primary');
    allButtons.forEach(btn => {
    btn.style.backgroundColor = config.primary_button_color || defaultConfig.primary_button_color;
    });

    // Section headings
    const sectionHeadings = document.querySelectorAll('h3, h4');
    sectionHeadings.forEach(heading => {
    if (!heading.closest('.modal-content') && !heading.id.includes('title')) {
        heading.style.color = config.text_color || defaultConfig.text_color;
    }
    });

    // Body text color
    const bodyTexts = document.querySelectorAll('p, label, span, div');
    bodyTexts.forEach(text => {
    if (!text.closest('.hero-gradient') && !text.classList.contains('text-white')) {
        const currentColor = window.getComputedStyle(text).color;
        if (currentColor.includes('rgb(107, 114, 128)') || currentColor.includes('rgb(75, 85, 99)')) {
        text.style.color = config.accent_color || defaultConfig.accent_color;
        }
    }
    });
}

if (window.elementSdk) {
    window.elementSdk.init({
    defaultConfig: defaultConfig,
    onConfigChange: onConfigChange,
    mapToCapabilities: (config) => ({
        recolorables: [
        {
            get: () => config.background_color || defaultConfig.background_color,
            set: (value) => {
            config.background_color = value;
            window.elementSdk.setConfig({ background_color: value });
            }
        },
        {
            get: () => config.card_color || defaultConfig.card_color,
            set: (value) => {
            config.card_color = value;
            window.elementSdk.setConfig({ card_color: value });
            }
        },
        {
            get: () => config.text_color || defaultConfig.text_color,
            set: (value) => {
            config.text_color = value;
            window.elementSdk.setConfig({ text_color: value });
            }
        },
        {
            get: () => config.primary_button_color || defaultConfig.primary_button_color,
            set: (value) => {
            config.primary_button_color = value;
            window.elementSdk.setConfig({ primary_button_color: value });
            }
        },
        {
            get: () => config.accent_color || defaultConfig.accent_color,
            set: (value) => {
            config.accent_color = value;
            window.elementSdk.setConfig({ accent_color: value });
            }
        }
        ],
        borderables: [],
        fontEditable: {
        get: () => config.font_family || defaultConfig.font_family,
        set: (value) => {
            config.font_family = value;
            window.elementSdk.setConfig({ font_family: value });
        }
        },
        fontSizeable: {
        get: () => config.font_size || defaultConfig.font_size,
        set: (value) => {
            config.font_size = value;
            window.elementSdk.setConfig({ font_size: value });
        }
        }
    }),
    mapToEditPanelValues: (config) => new Map([
        ["store_name", config.store_name || defaultConfig.store_name],
        ["hero_title", config.hero_title || defaultConfig.hero_title],
        ["hero_subtitle", config.hero_subtitle || defaultConfig.hero_subtitle],
        ["strawberry_title", config.strawberry_title || defaultConfig.strawberry_title],
        ["murbei_title", config.murbei_title || defaultConfig.murbei_title],
        ["strawberry_frozen_title", config.strawberry_frozen_title || defaultConfig.strawberry_frozen_title],
        ["checkout_title", config.checkout_title || defaultConfig.checkout_title],
        ["form_name_label", config.form_name_label || defaultConfig.form_name_label],
        ["form_whatsapp_label", config.form_whatsapp_label || defaultConfig.form_whatsapp_label],
        ["form_address_label", config.form_address_label || defaultConfig.form_address_label],
        ["submit_button_text", config.submit_button_text || defaultConfig.submit_button_text]
    ])
    });
}
