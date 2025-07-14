document.addEventListener("DOMContentLoaded", () => {
  // Mostrar toast de error
  const toastError = document.getElementById("toast-error");
  if (toastError) {
    toastError.classList.add("show");
    setTimeout(() => {
      toastError.classList.remove("show");
    }, 3000);

    // Limpiar campos de contraseña
    document.querySelector("input[name='contrasena']").value = "";
    document.querySelector("input[name='confirmar']").value = "";
  }

  // Mostrar toast normal y redirigir
  const toast = document.getElementById("toast");
  if (toast) {
    toast.classList.add("show");
    setTimeout(() => {
      toast.classList.remove("show");
      window.location.href = "menu.php";
    }, 1000);
  }
});
