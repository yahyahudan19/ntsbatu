document.addEventListener('DOMContentLoaded', () => {
    const tableElement = document.getElementById('ordersTable');
    if (!tableElement) return;

    // 0) Init flatpickr untuk filter tanggal kirim
    const deliveryInput = document.getElementById('filterDeliveryDate');
    if (deliveryInput && typeof flatpickr !== 'undefined') {
        flatpickr(deliveryInput, {
            enableTime: true,              // datetime picker (ada jam)
            dateFormat: 'Y-m-d H:i',       // value yang dikirim: 2025-11-24 09:30
            altInput: true,                // tampilkan input cantik untuk user
            altFormat: 'd M Y H:i',        // tampilan: 24 Nov 2025 09:30
            time_24hr: true,
            allowInput: true
        });
    }

    // Helper format tanggal untuk kolom Tanggal
    function formatDate(dateString, withTime = false) {
        if (!dateString) return '';
        const d = new Date(dateString);
        if (Number.isNaN(d.getTime())) return '';

        const options = withTime
            ? { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }
            : { day: '2-digit', month: 'short', year: 'numeric' };

        return d.toLocaleDateString('id-ID', options).replace('.', '');
    }

    // 1) Isi kolom Tanggal (last col) pakai data-* + formatDate()
    const rows = tableElement.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const createdAt    = row.dataset.createdAt;       // ISO string
        const deliveryDate = row.dataset.deliveryDate;    // YYYY-MM-DD
        const deliverySlot = row.dataset.deliverySlot;    // text

        let displayText = '';

        if (deliveryDate) {
            const formatted = formatDate(deliveryDate, false);
            displayText = formatted;

            if (deliverySlot) {
                displayText += ` (${deliverySlot})`;
            }
        } else if (createdAt) {
            const formatted = formatDate(createdAt, true);
            displayText = formatted;
        }

        const dateCell = row.querySelector('.order-date-cell');
        if (dateCell) {
            dateCell.textContent = displayText || '-';
        }
    });

    // 2) Inisialisasi DataTables
    let dataTable = null;

    if (typeof DataTable !== 'undefined') {
        dataTable = new DataTable('#ordersTable', {
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: false, // kita pakai filter custom di atas
        });
    } else {
        console.error('DataTable (DataTables 2) belum ter-load.');
    }

    // 3) Filter custom (search, status, tanggal)
    const searchInput  = document.querySelector('input[name="search"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const filterForm   = document.querySelector('.filter-form');

    if (filterForm) {
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            applyFilters();
        });
    }

    const resetBtn = Array.from(document.querySelectorAll('.btn-outline'))
        .find(btn => btn.textContent.trim().toLowerCase() === 'reset');

    if (resetBtn) {
        resetBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (searchInput) searchInput.value = '';
            if (statusSelect) statusSelect.value = 'all';
            if (deliveryInput) deliveryInput.value = '';
            applyFilters();
        });
    }

    function applyFilters() {
        const searchVal   = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const statusVal   = statusSelect ? statusSelect.value : 'all';
        const deliveryVal = deliveryInput ? deliveryInput.value : ''; // contoh: 2025-11-24 09:30

        const allRows = tableElement.querySelectorAll('tbody tr');

        allRows.forEach(row => {
            const cells = row.querySelectorAll('td');

            const kode      = cells[0]?.innerText.toLowerCase() || '';
            const customer  = cells[1]?.innerText.toLowerCase() || '';
            const statusTxt = (cells[6]?.innerText || '').toLowerCase();

            let show = true;

            // Search kode/nama/phone
            if (searchVal) {
                const combined = `${kode} ${customer}`;
                if (!combined.includes(searchVal)) {
                    show = false;
                }
            }

            // Filter status
            if (statusVal && statusVal !== 'all') {
                if (!statusTxt.includes(statusVal.toLowerCase())) {
                    show = false;
                }
            }

            // Filter tanggal kirim: bandingkan HANYA bagian tanggal
            if (deliveryVal) {
                const filterDateOnly = deliveryVal.slice(0, 10);  // 'Y-m-d'
                const rowDeliveryDate = row.dataset.deliveryDate || '';

                if (rowDeliveryDate !== filterDateOnly) {
                    show = false;
                }
            }

            row.style.display = show ? '' : 'none';
        });

        if (dataTable) {
            dataTable.draw();
        }
    }
});
