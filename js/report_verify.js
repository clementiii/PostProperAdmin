
document.querySelectorAll(".zoomable-image").forEach((img) => {
   img.addEventListener("click", function () {
      const modalImage = document.getElementById("modalImage");
      modalImage.src = this.src;
      new bootstrap.Modal(document.getElementById("imageModal")).show();
   });
});
