document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.querySelector('.contenedor-menu');
  let ejerciciosActuales = [];

  // Delegación de eventos para niveles
  contenedor.addEventListener('click', function (e) {
    if (e.target.classList.contains('nivel') && !e.target.classList.contains('bloqueado')) {
      e.preventDefault();
      const nivelSeleccionado = e.target.dataset.nivel;
      mostrarEjercicios(nivelSeleccionado);
    }
  });

  function mostrarEjercicios(nivel, reiniciar = false) {
    if (!reiniciar || ejerciciosActuales.length === 0) {
      // Generar nuevos ejercicios solo si no estamos reiniciando
      ejerciciosActuales = [];
      for (let i = 1; i <= 8; i++) {
        const a = Math.floor(Math.random() * 50) + 20;
        const b = Math.floor(Math.random() * 20);
        ejerciciosActuales.push({ a, b, resuelto: false });
      }
    }

    let ejerciciosHTML = '';
    ejerciciosActuales.forEach(({ a, b, resuelto }) => {
      const operacion = `${a} - ${b}`;
      ejerciciosHTML += `
      <div class="ejercicio ${resuelto ? 'resuelto' : ''}" data-operacion="${operacion}" style="${resuelto ? 'background-color:#4CAF50; pointer-events:none;' : ''}">
        ${resuelto ? '✔ Resuelto' : operacion}
      </div>`;
    });

    contenedor.innerHTML = `
    <h2 class="titulo">Nivel ${nivel} - Resta</h2>
    <div class="grid-ejercicios">${ejerciciosHTML}</div>
    <div style="margin-top: 40px;">
      <button class="btn-volver-menu">⬅ Volver al Menú</button>
      <button class="btn-reiniciar-nivel" data-nivel="${nivel}">🔄 Reiniciar Nivel</button>
    </div>
  `;

    document.querySelectorAll('.ejercicio').forEach(ej => {
      ej.addEventListener('click', e => {
        const operacion = e.currentTarget.dataset.operacion;
        mostrarPopup(operacion);
      });
    });

    document.querySelector('.btn-volver-menu').addEventListener('click', () => {
      location.reload();
    });

    document.querySelector('.btn-reiniciar-nivel').addEventListener('click', e => {
      const nivelActual = e.target.dataset.nivel;
      // Reiniciar sin generar nuevos ejercicios
      ejerciciosActuales.forEach(ej => ej.resuelto = false);
      mostrarEjercicios(nivelActual, true);
    });
  }


  function mostrarPopup(operacion) {
    const [a, b] = operacion.split(' - ').map(Number);
    const resultadoCorrecto = a - b;

    const overlay = document.createElement('div');
    overlay.className = 'popup-overlay';
    overlay.innerHTML = `
    <div class="popup-content">
      <h3>Resuelve la resta:</h3>
      <div class="resta-vertical">
        <div>${a.toString().padStart(3, '0')}</div>
        <div class="sustraendo">${b.toString().padStart(3, '0')}</div>
        <div class="linea"></div>
        <input type="number" id="respuesta" placeholder="Resultado">
      </div>
      <div style="margin-top: 20px;">
        <button class="btn-validar">Validar</button>
        <button class="btn-volver">Volver</button>
      </div>
      <div id="feedback" style="margin-top: 15px; font-weight: bold;"></div>
    </div>
  `;

    document.body.appendChild(overlay);

    const input = overlay.querySelector('#respuesta');
    const feedback = overlay.querySelector('#feedback');

    overlay.querySelector('.btn-validar').addEventListener('click', () => {
      const respuestaUsuario = parseInt(input.value);
      if (respuestaUsuario === resultadoCorrecto) {
        feedback.textContent = '✅ ¡Correcto!';
        feedback.style.color = 'green';

        ejerciciosActuales.forEach(ej => {
          if (`${ej.a} - ${ej.b}` === operacion) {
            ej.resuelto = true;
          }
        });

        const ejercicios = document.querySelectorAll('.ejercicio');
        ejercicios.forEach(ej => {
          if (ej.dataset.operacion === operacion) {
            ej.innerHTML = '✔ Resuelto';
            ej.style.backgroundColor = '#4CAF50';
            ej.style.pointerEvents = 'none';
          }
        });

        setTimeout(() => overlay.remove(), 1000);
      }
      else {
        feedback.textContent = '❌ Incorrecto. Intenta de nuevo.';
        feedback.style.color = 'red';
      }
    });

    overlay.addEventListener('click', e => {
      if (e.target === overlay || e.target.classList.contains('btn-volver')) {
        overlay.remove();
      }
    });
  }

});
