const fileInput = document.querySelector(".file-input");
const imagePreviewContainer = document.getElementById(
   "image-preview-container"
);

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

         imageContainer
            .querySelector(".btn-close")
            .addEventListener("click", function () {
               imageContainer.remove();
            });
      };
      reader.readAsDataURL(file);
   });
});

const editModal = document.getElementById("editModal");

editModal.addEventListener("show.bs.modal", function (event) {
   const button = event.relatedTarget;
   const postId = button.getAttribute("data-id");
   const postTitle = button.getAttribute("data-title");
   const postDescription = button.getAttribute("data-description");

   document.getElementById("editPostId").value = postId;
   document.getElementById("editTitle").value = postTitle;
   document.getElementById("editDescription").value = postDescription;

   // Clear previous previews
   document.getElementById("edit-image-preview-container").innerHTML = "";

   // Fetch and display current images if available
   // Use AJAX here to fetch images for the post
});

document.addEventListener("DOMContentLoaded", function () {
   // Find the publish button in the form
   const publishButton = document.querySelector(".btn-save");
   const confirmPublishButton = document.getElementById("confirmPublish");

   // Trigger the modal when clicking "Post"
   publishButton.addEventListener("click", function () {
      // This opens the modal to confirm the publish
      const publishModal = new bootstrap.Modal(document.getElementById("publishModal"));
      publishModal.show();
   });

   // Handle the "Yes, confirm" button inside the publish modal
   confirmPublishButton.addEventListener("click", function () {
      // Find the form and submit it
      const form = document.querySelector("form[action='process_announcement.php']");
      if (form) {
         form.submit();
      }
   });
});

document.addEventListener("DOMContentLoaded", function () {
   document.querySelectorAll(".btn-edit").forEach(function(button) {
       button.addEventListener("click", function() {
           const postId = this.getAttribute("data-id");

           // AJAX request to get post data including images
           fetch(`get_post_data.php?id=${postId}`)
               .then(response => response.json())
               .then(data => {
                   document.getElementById("editPostId").value = data.id;
                   document.getElementById("editTitle").value = data.title;
                   document.getElementById("editDescription").value = data.description;

                   // Populate current images
                   const currentImagesContainer = document.getElementById("current-images-container");
                   currentImagesContainer.innerHTML = ""; // Clear existing images

                   data.images.forEach(image => {
                       const imgWrapper = document.createElement("div");
                       imgWrapper.classList.add("image-preview");

                       const img = document.createElement("img");
                       img.src = image;
                       imgWrapper.appendChild(img);

                       const deleteBtn = document.createElement("button");
                       deleteBtn.classList.add("btn-close");
                       deleteBtn.innerHTML = "&times;";
                       deleteBtn.onclick = function () {
                           imgWrapper.remove();
                           // You can add the image to a hidden field to track deletions
                           const hiddenInput = document.createElement("input");
                           hiddenInput.type = "hidden";
                           hiddenInput.name = "remove_images[]";
                           hiddenInput.value = image;
                           document.getElementById("editModal form").appendChild(hiddenInput);
                       };
                       imgWrapper.appendChild(deleteBtn);

                       currentImagesContainer.appendChild(imgWrapper);
                   });
               })
               .catch(error => console.error("Error fetching post data:", error));
       });
   });
});

document.addEventListener("DOMContentLoaded", function () {
   // Find the save changes button in the edit modal form
   const saveChangesButton = document.querySelector("#editModal form button[type='submit']");
   saveChangesButton.addEventListener("click", function (event) {
       event.preventDefault(); // Prevent default form submission

       // Collect the form data (title, description, images, etc.)
       const form = document.querySelector("#editModal form");
       const formData = new FormData(form);

       // Optionally, if you need to include the images selected in the modal, add them here
       const images = document.querySelectorAll("#edit-image-preview-container .image-preview img");
       images.forEach(function (image) {
           // You can append images to the FormData if necessary
           // formData.append('images[]', image.src); // Example if you want to send the image src
       });

       // Now submit the form using AJAX (or you can directly submit if you're using form action)
       fetch('edit_announcement.php', {
           method: 'POST',
           body: formData
       })
       .then(response => response.json())
       .then(data => {
           if (data.success) {
               // Show success message or redirect
               window.location.href = 'announcement.php';
           } else {
               // Show error message
               alert("An error occurred while updating the post.");
           }
       })
       .catch(error => {
           console.error('Error:', error);
           alert('An error occurred');
       });
   });
});
   
