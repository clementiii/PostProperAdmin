document.addEventListener("DOMContentLoaded", function () {
   const fileInput = document.querySelector(".file-input");
   const imagePreviewContainer = document.getElementById("image-preview-container");

   // Check if the file input exists
   if (fileInput) {
       fileInput.addEventListener("change", function (event) {
           const files = Array.from(event.target.files);

           files.forEach((file) => {
               const reader = new FileReader();
               reader.onload = function (e) {
                   const imageContainer = document.createElement("div");
                   imageContainer.className = "image-preview position-relative me-2 mb-2";

                   imageContainer.innerHTML = `
                       <img src="${e.target.result}" class="img-fluid">
                       <button class="btn-close" aria-label="Remove image"></button>
                   `;

                   imagePreviewContainer.appendChild(imageContainer);

                   // Remove image on button click
                   imageContainer.querySelector(".btn-close").addEventListener("click", function () {
                       imageContainer.remove();
                   });
               };
               reader.readAsDataURL(file);
           });
       });
   }

   const publishButton = document.querySelector(".btn-save");
   const confirmPublishButton = document.getElementById("confirmPublish");

   // Trigger the modal when clicking "Post"
   if (publishButton) {
       publishButton.addEventListener("click", function () {
           const publishModal = new bootstrap.Modal(document.getElementById("publishModal"));
           publishModal.show();
       });
   }

   // Handle the "Yes, confirm" button inside the publish modal
   if (confirmPublishButton) {
       confirmPublishButton.addEventListener("click", function () {
           const form = document.querySelector("form[action='process_announcement.php']");
           if (form) {
               form.submit(); // Submit the form when "Yes, confirm" is clicked
           }

           // Close the modal after confirming
           const publishModal = bootstrap.Modal.getInstance(document.getElementById("publishModal"));
           publishModal.hide();
       });
   }
});
