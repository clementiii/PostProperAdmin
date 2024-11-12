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
