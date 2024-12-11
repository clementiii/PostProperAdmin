document.addEventListener('DOMContentLoaded', function() {
    // Table sorting functionality
    const table = document.querySelector(".table");
    const headers = table.querySelectorAll("th[data-sort]");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    let sortDirection = {};

    headers.forEach((header, index) => {
        const type = header.getAttribute("data-sort");
        sortDirection[type] = 1;

        header.addEventListener("click", () => {
            let sortedRows;

            if (type === "status") {
                const statusOrderAsc = { "pending": 1, "approved": 2, "rejected": 3, "cancelled": 4 };
                const statusOrderDesc = { "cancelled": 1, "rejected": 2, "approved": 3, "pending": 4 };
                const currentOrder = sortDirection[type] === 1 ? statusOrderAsc : statusOrderDesc;
                sortedRows = rows.sort((a, b) => 
                    currentOrder[a.cells[index].innerText.toLowerCase()] - 
                    currentOrder[b.cells[index].innerText.toLowerCase()]
                );
                sortDirection[type] *= -1;
            } else if (type === "number") {
                sortedRows = rows.sort((a, b) => 
                    (parseFloat(a.cells[index].innerText.replace(/[^0-9.-]+/g,"")) - 
                    parseFloat(b.cells[index].innerText.replace(/[^0-9.-]+/g,""))) * 
                    sortDirection[type]
                );
                sortDirection[type] *= -1;
            } else if (type === "string") {
                sortedRows = rows.sort((a, b) => 
                    a.cells[index].innerText.localeCompare(b.cells[index].innerText) * 
                    sortDirection[type]
                );
                sortDirection[type] *= -1;
            } else if (type === "date") {
                sortedRows = rows.sort((a, b) => 
                    (new Date(b.cells[index].innerText) - 
                    new Date(a.cells[index].innerText)) * 
                    sortDirection[type]
                );
                sortDirection[type] *= -1;
            }

            const tbody = table.querySelector("tbody");
            tbody.innerHTML = "";
            sortedRows.forEach(row => tbody.appendChild(row));
        });
    });

    // Pickup status toggle functionality
    document.querySelectorAll('.pickup-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const requestId = this.dataset.requestId;
            const newStatus = this.checked ? 'picked_up' : 'pending';
            const label = this.nextElementSibling;

            fetch('documents.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=updatePickupStatus&requestId=${requestId}&newStatus=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    label.textContent = this.checked ? 'Picked Up' : 'Not Picked Up';
                } else {
                    // Revert the toggle if update failed
                    this.checked = !this.checked;
                    alert('Failed to update pickup status. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
                alert('Failed to update pickup status. Please try again.');
            });
        });
    });

    // Modal functionality
    const modal = document.getElementById('cancellationModal');
    const modalInstance = new bootstrap.Modal(modal);

    // Function to show cancellation reason in modal
    window.showCancellationReason = function(reason) {
        document.getElementById('cancellationReason').textContent = reason;
        modalInstance.show();
    }

    // Clear modal content when hidden
    modal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('cancellationReason').textContent = '';
    });});