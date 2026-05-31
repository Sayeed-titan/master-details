// LESSON: This file handles all dynamic Order Item operations on the show page.
// It talks to our REST API using Axios (no page reloads needed).
//
// Flow:
//   User clicks button → JS opens modal or confirms → Axios calls API → UI updates

// Wait for the DOM to be fully loaded before running any JS
document.addEventListener('DOMContentLoaded', function () {

    // ─── References to DOM elements ───────────────────────────────────────────
    const addItemBtn      = document.getElementById('addItemBtn');
    const saveItemBtn     = document.getElementById('saveItemBtn');
    const itemsTableBody  = document.getElementById('itemsTableBody');
    const itemModalEl     = document.getElementById('itemModal');
    const itemModalTitle  = document.getElementById('itemModalTitle');
    const itemModalError  = document.getElementById('itemModalError');

    // Modal inputs
    const itemIdInput     = document.getElementById('itemId');
    const productNameInput= document.getElementById('productName');
    const quantityInput   = document.getElementById('quantity');
    const unitPriceInput  = document.getElementById('unitPrice');

    // Bootstrap modal instance
    // LESSON: Bootstrap 5 modals are controlled via JS using bootstrap.Modal
    const itemModal = new bootstrap.Modal(itemModalEl);

    // ─── Helper: toggle loading state on Save button ──────────────────────────
    function setSaving(loading) {
        saveItemBtn.disabled = loading;
        saveItemBtn.textContent = loading ? 'Saving...' : 'Save Item';
    }

    // ─── Helper: show error inside modal ──────────────────────────────────────
    function showModalError(msg) {
        itemModalError.textContent = msg;
        itemModalError.classList.remove('d-none');
    }

    function clearModalError() {
        itemModalError.textContent = '';
        itemModalError.classList.add('d-none');
    }

    // ─── Helper: reset modal fields to blank (Add mode) ───────────────────────
    function resetModal() {
        itemIdInput.value      = '';
        productNameInput.value = '';
        quantityInput.value    = 1;
        unitPriceInput.value   = '0.00';
        itemModalTitle.textContent = 'Add Item';
        clearModalError();
    }

    // ─── Helper: update the order total shown on the page ─────────────────────
    // LESSON: After every add/edit/delete we re-fetch the order total from the API
    function refreshOrderTotal() {
        axios.get(`/api/orders/${ORDER_ID}`)
            .then(res => {
                document.getElementById('orderTotal').textContent =
                    '$' + parseFloat(res.data.data.total_amount).toFixed(2);
            });
    }

    // ─── Helper: build a table row HTML string from an item object ────────────
    function buildRow(item) {
        return `
        <tr id="item-row-${item.id}">
            <td>${item.product_name}</td>
            <td>${item.quantity}</td>
            <td>$${parseFloat(item.unit_price).toFixed(2)}</td>
            <td>$${parseFloat(item.subtotal).toFixed(2)}</td>
            <td>
                <button class="btn btn-sm btn-outline-secondary edit-item-btn"
                        data-id="${item.id}"
                        data-product="${item.product_name}"
                        data-qty="${item.quantity}"
                        data-price="${item.unit_price}">
                    Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-item-btn"
                        data-id="${item.id}">
                    Delete
                </button>
            </td>
        </tr>`;
    }

    // ─── Open modal in ADD mode ────────────────────────────────────────────────
    addItemBtn.addEventListener('click', function () {
        resetModal();
        itemModal.show();
    });

    // ─── Open modal in EDIT mode (event delegation) ───────────────────────────
    // LESSON: Event delegation — we listen on the table body instead of each button.
    // This works even for rows added dynamically after page load.
    itemsTableBody.addEventListener('click', function (e) {

        // EDIT button clicked
        if (e.target.classList.contains('edit-item-btn')) {
            const btn = e.target;
            resetModal();
            // Pre-fill modal with existing item data from data-* attributes
            itemIdInput.value      = btn.dataset.id;
            productNameInput.value = btn.dataset.product;
            quantityInput.value    = btn.dataset.qty;
            unitPriceInput.value   = btn.dataset.price;
            itemModalTitle.textContent = 'Edit Item';
            itemModal.show();
        }

        // DELETE button clicked
        if (e.target.classList.contains('delete-item-btn')) {
            const itemId = e.target.dataset.id;
            if (!confirm('Remove this item from the order?')) return;

            // LESSON: Axios DELETE request to our API
            axios.delete(`/api/orders/${ORDER_ID}/items/${itemId}`)
                .then(() => {
                    // Remove the row from the table without reloading
                    document.getElementById(`item-row-${itemId}`).remove();

                    // Show empty message if no rows left
                    if (itemsTableBody.querySelectorAll('tr').length === 0) {
                        itemsTableBody.innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="5" class="text-center text-muted py-3">No items yet. Add one above.</td>
                            </tr>`;
                    }

                    refreshOrderTotal();
                })
                .catch(() => alert('Failed to delete item.'));
        }
    });

    // ─── Save Item (Add or Edit) ───────────────────────────────────────────────
    saveItemBtn.addEventListener('click', function () {
        clearModalError();

        const itemId      = itemIdInput.value;
        const productName = productNameInput.value.trim();
        const quantity    = parseInt(quantityInput.value);
        const unitPrice   = parseFloat(unitPriceInput.value);

        // Basic client-side validation
        if (!productName)       return showModalError('Product name is required.');
        if (quantity < 1)       return showModalError('Quantity must be at least 1.');
        if (unitPrice < 0)      return showModalError('Unit price cannot be negative.');

        const payload = {
            product_name : productName,
            quantity     : quantity,
            unit_price   : unitPrice,
        };

        if (itemId) {
            // ── EDIT: PUT request ──────────────────────────────────────────────
            // LESSON: Axios PUT sends updated data to the API
            setSaving(true);
            axios.put(`/api/orders/${ORDER_ID}/items/${itemId}`, payload)
                .then(res => {
                    const item = res.data.data;
                    const row = document.getElementById(`item-row-${item.id}`);
                    row.outerHTML = buildRow(item);
                    itemModal.hide();
                    refreshOrderTotal();
                })
                .catch(err => {
                    const msg = err.response?.data?.message || 'Failed to update item.';
                    showModalError(msg);
                })
                .finally(() => setSaving(false));

        } else {
            // ── ADD: POST request ──────────────────────────────────────────────
            // LESSON: Axios POST sends new item data to the API
            setSaving(true);
            axios.post(`/api/orders/${ORDER_ID}/items`, payload)
                .then(res => {
                    const item = res.data.data;

                    // Remove the "No items yet" empty row if present
                    const emptyRow = document.getElementById('emptyRow');
                    if (emptyRow) emptyRow.remove();

                    // Append the new row to the table
                    itemsTableBody.insertAdjacentHTML('beforeend', buildRow(item));

                    itemModal.hide();
                    refreshOrderTotal();
                })
                .catch(err => {
                    const msg = err.response?.data?.message || 'Failed to add item.';
                    showModalError(msg);
                })
                .finally(() => setSaving(false));
        }
    });

});
