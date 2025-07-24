
document.addEventListener("DOMContentLoaded", () => {
  // Mostrar modal de error
  const toastError = document.getElementById("toast-error");
  if (toastError) {
    mostrarVentanaMensaje(toastError.textContent, true);
    document.querySelector("input[name='contrasena']").value = "";
    document.querySelector("input[name='confirmar']").value = "";
  }

  // Mostrar modal normal y redirigir
  const toast = document.getElementById("toast");
  if (toast) {
    mostrarVentanaMensaje(toast.textContent, false, () => {
      window.location.href = "menu.php";
    });
  }
});

function mostrarVentanaMensaje(mensaje, esError = false, callback = null) {
  const overlay = document.createElement('div');
  overlay.className = 'popup-overlay popup-abrir';
  overlay.innerHTML = `
    <div class="popup-content" style="max-width:340px;padding:32px 24px;">
      <div style="font-size:1.25em;font-weight:bold;margin-bottom:12px;${esError ? 'color:#d32f2f;' : 'color:#333;'}">
        ${esError ? 'Error' : 'Mensaje'}
      </div>
      <div style="font-size:1.1em;margin-bottom:18px;">${mensaje}</div>
      <button class="btn-cerrar-popup" style="background:#1976d2;color:#fff;border:none;border-radius:8px;padding:8px 24px;font-size:1em;cursor:pointer;">Aceptar</button>
    </div>
  `;
  document.body.appendChild(overlay);
  overlay.querySelector('.btn-cerrar-popup').addEventListener('click', () => {
    overlay.classList.add('popup-cerrar');
    setTimeout(() => {
      overlay.remove();
      if (callback) callback();
    }, 350);
  });
}

