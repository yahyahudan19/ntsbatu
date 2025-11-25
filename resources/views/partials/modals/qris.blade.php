<div id="qris-modal" class="modal">
    <div class="modal-content">
      <div class="qris-container">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Pembayaran QRIS</h3>
        <div id="qris-loading" class="qris-code">
          <div class="spinner"></div>
        </div>
        <div id="qris-content" style="display: none;">
          <div class="qris-code">
            <svg width="240" height="240" viewbox="0 0 240 240"><!-- Demo QR Code Pattern -->
              <rect width="240" height="240" fill="white" />
              <g fill="black">
                <rect x="20" y="20" width="60" height="60" />
                <rect x="160" y="20" width="60" height="60" />
                <rect x="20" y="160" width="60" height="60" />
                <rect x="40" y="40" width="20" height="20" fill="white" />
                <rect x="180" y="40" width="20" height="20" fill="white" />
                <rect x="40" y="180" width="20" height="20" fill="white" />
                <rect x="100" y="30" width="10" height="10" />
                <rect x="120" y="30" width="10" height="10" />
                <rect x="140" y="30" width="10" height="10" />
                <rect x="100" y="50" width="10" height="10" />
                <rect x="120" y="50" width="10" height="10" />
                <rect x="140" y="50" width="10" height="10" />
                <rect x="100" y="70" width="10" height="10" />
                <rect x="120" y="70" width="10" height="10" />
                <rect x="140" y="70" width="10" height="10" />
                <rect x="100" y="100" width="10" height="10" />
                <rect x="120" y="120" width="10" height="10" />
                <rect x="140" y="140" width="10" height="10" />
                <rect x="160" y="100" width="10" height="10" />
                <rect x="180" y="120" width="10" height="10" />
                <rect x="200" y="140" width="10" height="10" />
                <rect x="30" y="100" width="10" height="10" />
                <rect x="50" y="120" width="10" height="10" />
                <rect x="70" y="140" width="10" height="10" />
                <rect x="100" y="160" width="10" height="10" />
                <rect x="120" y="180" width="10" height="10" />
                <rect x="140" y="200" width="10" height="10" />
                <rect x="160" y="160" width="10" height="10" />
                <rect x="180" y="180" width="10" height="10" />
                <rect x="200" y="200" width="10" height="10" />
              </g>
            </svg>
          </div>
          <div class="payment-status status-pending" id="payment-status">
            ⏳ Menunggu Pembayaran...
          </div>
          <div class="text-center mb-4">
            <p class="text-gray-700 font-semibold text-lg mb-2">Total Pembayaran</p>
            <p class="text-3xl font-bold text-gray-900" id="qris-amount">Rp 0</p>
          </div>
          <div class="bg-blue-50 p-4 rounded-lg mb-4 text-left">
            <p class="text-sm text-gray-700 mb-2"><strong>Cara Pembayaran:</strong></p>
            <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
              <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
              <li>Pilih menu Scan QR atau QRIS</li>
              <li>Scan kode QR di atas</li>
              <li>Konfirmasi pembayaran</li>
              <li>Simpan bukti transaksi</li>
            </ol>
          </div>
          <div class="text-xs text-gray-500 mb-4">
            <p>Order ID: <span id="order-id" class="font-mono">-</span></p>
            <p>Berlaku hingga: <span id="qris-expiry">-</span></p>
          </div>
        </div><button onclick="closeQrisModal()"
          class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-lg font-semibold transition-colors"> Tutup
        </button>
      </div>
    </div>
  </div>