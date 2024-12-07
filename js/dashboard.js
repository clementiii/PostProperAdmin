document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
    const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
    
    // Make the viewDetails function globally available
    window.viewDetails = function(requestData) {
        const modalBody = document.querySelector('#detailsModal .modal-body');
        modalBody.innerHTML = `
            <p><strong>Transaction ID:</strong> <span>TXN-${requestData.Id}</span></p>
            <p><strong>Name:</strong> <span>${requestData.Name}</span></p>
            <p><strong>Document Type:</strong> <span>${requestData.DocumentType}</span></p>
            <p><strong>Quantity:</strong> <span>${requestData.Quantity}</span></p>
            <p><strong>Price:</strong> <span>₱${(requestData.Quantity * 50).toFixed(2)}</span></p>
            <p><strong>Date Requested:</strong> <span>${formatDate(requestData.DateRequested)}</span></p>
            <p><strong>Status:</strong> <span class="status-${requestData.Status.toLowerCase()}">${requestData.Status}</span></p>
            ${requestData.Address ? `<p><strong>Address:</strong> <span>${requestData.Address}</span></p>` : ''}
            ${requestData.Gender ? `<p><strong>Gender:</strong> <span>${requestData.Gender}</span></p>` : ''}
            ${requestData.CivilStatus ? `<p><strong>Civil Status:</strong> <span>${requestData.CivilStatus}</span></p>` : ''}
            ${requestData.TIN_No ? `<p><strong>TIN No:</strong> <span>${requestData.TIN_No}</span></p>` : ''}
            ${requestData.CTC_No ? `<p><strong>CTC No:</strong> <span>${requestData.CTC_No}</span></p>` : ''}
        `;
        
        detailsModal.show();
    };
    
    // Helper function to format dates
    function formatDate(dateString) {
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }
});