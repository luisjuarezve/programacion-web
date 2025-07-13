document.addEventListener("DOMContentLoaded", () => {
  // Toast de error
  const toastError = document.getElementById("toast-error");
  if (toastError) {
    toastError.classList.add("show");
    setTimeout(() => {
      toastError.classList.remove("show");
    }, 3000);
  }

  // Otro toast y redirección
  const toast = document.getElementById("toast");
  if (toast) {
    toast.classList.add("show");
    setTimeout(() => {
      toast.classList.remove("show");
      window.location.href = "menu.php";
    }, 1000);
  }
});
