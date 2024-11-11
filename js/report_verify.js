
document.getElementById("resolvedBtn").onclick = function () {
   alert("Marked as Resolved");
};

document.getElementById("pendingBtn").onclick = function () {
   alert("Marked as Pending");
};

document.querySelectorAll(".zoomable-image").forEach((img) => {
   img.addEventListener("click", function () {
      const modalImage = document.getElementById("modalImage");
      modalImage.src = this.src;
      new bootstrap.Modal(document.getElementById("imageModal")).show();
   });
});
