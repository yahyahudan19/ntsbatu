// Init DataTable
$(document).ready(function () {
    $("#productsTable").DataTable({
        pageLength: 25,
        order: [[4, "desc"]],
    });
});

// Modal handling
const productModal = document.getElementById("productModal");
const btnOpenModal = document.getElementById("btnOpenCreateModal");
const closeButtons = document.querySelectorAll("[data-close-modal]");

function openModal() {
    productModal.classList.add("is-open");
}

function closeModal() {
    productModal.classList.remove("is-open");
}

if (btnOpenModal) {
    btnOpenModal.addEventListener("click", openModal);
}

closeButtons.forEach((btn) => {
    btn.addEventListener("click", closeModal);
});

productModal.addEventListener("click", function (e) {
    if (e.target === productModal) {
        closeModal();
    }
});

// Dynamic variant rows
let variantIndex = 1;

const btnAddVariant = document.getElementById("btnAddVariant");
const variantTableBody = document.querySelector("#variantTable tbody");

function addVariantRow() {
    const idx = variantIndex++;

    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td>
            <input
                type="text"
                name="variants[${idx}][name]"
                class="form-input"
                placeholder="Contoh: Pack besar"
                required
            >
        </td>
        <td>
            <input
                type="number"
                name="variants[${idx}][price]"
                class="form-input"
                min="0"
                step="1000"
                placeholder="65000"
                required
            >
        </td>
        <td>
            <input
                type="number"
                name="variants[${idx}][qty_per_pack]"
                class="form-input"
                min="1"
                placeholder="Misal: 1"
            >
        </td>
        <td>
            <select name="variants[${idx}][is_active]" class="form-select">
                <option value="1" selected>Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </td>
        <td style="text-align:center;">
            <button type="button" class="variant-remove-btn">Hapus</button>
        </td>
    `;

    tr.querySelector(".variant-remove-btn").addEventListener("click", () => {
        tr.remove();
    });

    variantTableBody.appendChild(tr);
}

if (btnAddVariant) {
    btnAddVariant.addEventListener("click", addVariantRow);
}

// --- Modal helper (berlaku untuk semua modal-backdrop) ---
function openBackdrop(backdrop) {
    if (backdrop) backdrop.classList.add("is-open");
}

function closeBackdrop(backdrop) {
    if (backdrop) backdrop.classList.remove("is-open");
}

// Create Product modal
const createModal = document.getElementById("productModal");
const btnOpenCreate = document.getElementById("btnOpenCreateModal");

if (btnOpenCreate) {
    btnOpenCreate.addEventListener("click", () => openBackdrop(createModal));
}

// Close buttons untuk semua modal
document.querySelectorAll("[data-close-modal]").forEach((btn) => {
    btn.addEventListener("click", () => {
        const backdrop = btn.closest(".modal-backdrop");
        closeBackdrop(backdrop);
    });
});

// Klik di luar panel menutup modal
document.querySelectorAll(".modal-backdrop").forEach((backdrop) => {
    backdrop.addEventListener("click", (e) => {
        if (e.target === backdrop) {
            closeBackdrop(backdrop);
        }
    });
});

// --- Edit Product modal ---
const editModal = document.getElementById("productEditModal");
const editForm = document.getElementById("productEditForm");
const editButtons = document.querySelectorAll(".btn-edit-product");
const editNameInput = document.getElementById("edit_name");
const editSlugInput = document.getElementById("edit_slug");
const editDescInput = document.getElementById("edit_description");
const editStatusSelect = document.getElementById("edit_is_active");
const editImagePreview = document.getElementById("editImagePreview");
const editImageInfo = document.getElementById("editImageInfo");

const baseImagePath = "{{ asset('images/products') }}"; // tanpa slash di akhir

editButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        const name = btn.dataset.name || "";
        const slug = btn.dataset.slug || "";
        const description = btn.dataset.description || "";
        const isActive = btn.dataset.isActive || "1";
        const image = btn.dataset.image || "";
        const updateUrl = btn.dataset.updateUrl;

        // Set action form ke URL update produk ini
        if (updateUrl && editForm) {
            editForm.action = updateUrl;
        }

        // Isi field
        if (editNameInput) editNameInput.value = name;
        if (editSlugInput) editSlugInput.value = slug;
        if (editDescInput) editDescInput.value = description;
        if (editStatusSelect) editStatusSelect.value = isActive;

        // Preview gambar (kalau ada)
        if (image && editImagePreview) {
            editImagePreview.src = baseImagePath + "/" + image;
            editImagePreview.style.display = "inline-block";
            if (editImageInfo) {
                editImageInfo.textContent = image;
            }
        } else {
            if (editImagePreview) {
                editImagePreview.src = "";
                editImagePreview.style.display = "none";
            }
            if (editImageInfo) {
                editImageInfo.textContent = "Belum ada gambar";
            }
        }

        openBackdrop(editModal);
    });
});

const priceModal = document.getElementById("priceEditModal");
const priceForm = document.getElementById("priceEditForm");
const priceTableBody = document.querySelector("#priceVariantTable tbody");
const priceEditInfo = document.getElementById("priceEditProductInfo");
const priceButtons = document.querySelectorAll(".btn-edit-price");

priceButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
        const updateUrl = btn.dataset.updateUrl;
        const variants = JSON.parse(btn.dataset.variants || "[]");

        if (!updateUrl || !variants.length) {
            alert("Tidak ada varian untuk produk ini.");
            return;
        }

        // Set action form ke route updatePrices
        priceForm.action = updateUrl;

        // (Optional) kalau mau tampilkan informasi produk di atas tabel
        const row = btn.closest("tr");
        const nameCell = row?.querySelector(".product-name");
        if (nameCell && priceEditInfo) {
            priceEditInfo.textContent =
                "Produk: " + nameCell.textContent.trim();
        }

        // Clear isi tbody dulu
        priceTableBody.innerHTML = "";

        // Isi baris per varian
        variants.forEach((v, index) => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>
                    <span class="variant-label">${v.name}</span>
                    <input type="hidden" name="variants[${index}][id]" value="${
                v.id
            }">
                </td>
                <td>
                    <input
                        type="number"
                        name="variants[${index}][price]"
                        class="form-input"
                        min="0"
                        step="1000"
                        value="${v.price ?? 0}"
                        required
                    >
                </td>
                <td>
                    <select name="variants[${index}][is_active]" class="form-select">
                        <option value="1"${
                            v.is_active ? " selected" : ""
                        }>Aktif</option>
                        <option value="0"${
                            !v.is_active ? " selected" : ""
                        }>Nonaktif</option>
                    </select>
                </td>
            `;

            priceTableBody.appendChild(tr);
        });

        openBackdrop(priceModal);
    });
});
