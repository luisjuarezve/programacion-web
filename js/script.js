document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.querySelector('.contenedor-menu');
  let ejerciciosActuales = [];
  let paginaActual = 0; // 0, 1, 2

  // Al cargar el menú, mostrar ejercicios directamente (sin niveles)
    // Recuperar respuestas guardadas del backend antes de mostrar ejercicios
    fetch('recuperar_respuestas.php')
      .then(res => res.json())
      .then(data => {
        window.respuestasUsuario = Array.isArray(data.respuestas) ? data.respuestas : [];
        mostrarEjercicios();
      });

function mostrarEjercicios(reiniciar = false, pagina = 0) {
  paginaActual = pagina;
  // Si no hay preguntas guardadas, generarlas y guardarlas en la base de datos
  let preguntasGuardadas = window.respuestasUsuario || [];
  if (preguntasGuardadas.length < 24 || reiniciar) {
    ejerciciosActuales = [];
    for (let i = 1; i <= 24; i++) {
      let a = Math.floor(Math.random() * 50) + 20;
      let b = Math.floor(Math.random() * 20);
      ejerciciosActuales.push({ a, b, resuelto: false, pregunta_id: i });
    }
    // Guardar todas las preguntas generadas en la base de datos como incorrectas inicialmente
    fetch('guardar_respuestas.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        respuestas: ejerciciosActuales.map(ej => ({
          pregunta_id: ej.pregunta_id,
          a: ej.a,
          b: ej.b,
          respuesta: '',
          correcta: false
        }))
      })
    }).then(() => {
      window.respuestasUsuario = ejerciciosActuales.map(ej => ({
        pregunta_id: ej.pregunta_id,
        a: ej.a,
        b: ej.b,
        respuesta: '',
        correcta: false
      }));
      renderGrid();
    });
    return;
  } else {
    // Mantener el orden de las preguntas por pregunta_id
    preguntasGuardadas.sort((a, b) => a.pregunta_id - b.pregunta_id);
    ejerciciosActuales = preguntasGuardadas.map(r => ({
      a: r.a,
      b: r.b,
      resuelto: !!r.correcta,
      pregunta_id: r.pregunta_id,
      incorrecta: r.respuesta !== '' && !r.correcta
    }));
  }
  renderGrid();
  function renderGrid() {
    // Mostrar solo los ejercicios de la página actual
    const start = paginaActual * 8;
    const end = start + 8;
    let ejerciciosHTML = '';
    ejerciciosActuales.slice(start, end).forEach(({ a, b, resuelto, incorrecta }, idx) => {
      const preguntaIdx = start + idx;
      const operacion = `${a} - ${b}`;
      let operacionHTML;
      if (resuelto) {
        const resp = window.respuestasUsuario.find(r => r.pregunta_id == preguntaIdx + 1 && r.correcta);
        operacionHTML = `
          <div class=\"resuelto-titulo\" style=\"font-size:1.1em;font-weight:bold;margin-bottom:6px;\">Resuelto</div>
          <div class=\"resta-grid\">\n              <div class=\"minuendo\">${a.toString().padStart(3, '0')}</div>\n              <div class=\"sustraendo-grid\">${b.toString().padStart(3, '0')}</div>\n              <div class=\"linea-grid\"></div>\n            </div>\n            <div style=\"margin-top:8px;font-size:1.2em;font-weight:bold;display:flex;align-items:center;gap:6px;justify-content:center;\"><span style=\"font-size:1.2em;\">✔</span> <span>${resp ? resp.respuesta : (a-b)}</span></div>
        `;
      } else if (incorrecta) {
        const resp = window.respuestasUsuario.find(r => r.pregunta_id == preguntaIdx + 1 && !r.correcta && r.respuesta !== '');
        operacionHTML = `
          <div class=\"incorrecto-titulo\" style=\"font-size:1.1em;font-weight:bold;margin-bottom:4px;color:#c62828;\">Incorrecto</div>
          <div class=\"resta-grid\">\n              <div class=\"minuendo\">${a.toString().padStart(3, '0')}</div>\n              <div class=\"sustraendo-grid\">${b.toString().padStart(3, '0')}</div>\n              <div class=\"linea-grid\"></div>\n            </div>\n            <div style=\"margin-top:8px;font-size:1.2em;font-weight:bold;display:flex;align-items:center;gap:6px;justify-content:center;color:#c62828;\"><span style=\"font-size:1.2em;\">❌</span> <span>${resp ? resp.respuesta : ''}</span></div>
        `;
      } else {
        operacionHTML = `
        <div class=\"resta-grid\">\n        <div class=\"minuendo\">${a.toString().padStart(3, '0')}</div>\n        <div class=\"sustraendo-grid\">${b.toString().padStart(3, '0')}</div>\n        <div class=\"linea-grid\"></div>\n      </div>`;
      }
      ejerciciosHTML += `
        <div class=\"ejercicio ${resuelto ? 'resuelto' : ''}\" 
             data-operacion=\"${operacion}\" 
             data-pregunta-idx=\"${preguntaIdx}\"
             style=\"${resuelto ? 'background-color:#4CAF50; color:white; pointer-events:none;' : ''}\">
          ${operacionHTML}
        </div>`;
    });
    let paginacionHTML = '<div style="margin-top: 40px;">';
    paginacionHTML += '<button class="btn-reiniciar">🔄 Reiniciar Ejercicios</button>';
    if (paginaActual > 0) paginacionHTML += '<button class="btn-anterior">anterior</button>';
    if (paginaActual < 2) paginacionHTML += '<button class="btn-siguiente">siguiente</button>';
    paginacionHTML += '</div>';
    contenedor.innerHTML = `
      <h2 class="titulo">Ejercicios de Resta</h2>
      <div class="grid-ejercicios">${ejerciciosHTML}</div>
      ${paginacionHTML}
    `;
      // ...

      document.querySelectorAll('.ejercicio').forEach(ej => {
        ej.addEventListener('click', e => {
          const operacion = e.currentTarget.dataset.operacion;
          mostrarPopup(operacion);
        });
      });

      document.querySelector('.btn-reiniciar').addEventListener('click', () => {
        // Eliminar todas las respuestas del usuario en el backend
        fetch('reiniciar_nivel.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({})
        }).then(() => {
          ejerciciosActuales.forEach(ej => ej.resuelto = false);
          window.respuestasUsuario = [];
          mostrarEjercicios(true, 0);
        });
      });

      const btnSiguiente = document.querySelector('.btn-siguiente');
      if (btnSiguiente) {
        btnSiguiente.addEventListener('click', () => {
          mostrarEjercicios(false, paginaActual + 1);
        });
      }
      const btnAnterior = document.querySelector('.btn-anterior');
      if (btnAnterior) {
        btnAnterior.addEventListener('click', () => {
          mostrarEjercicios(false, paginaActual - 1);
        });
      }
    }
}

function mostrarPopup(operacion) {
  const [a, b] = operacion.split(' - ').map(Number);
  const resultadoCorrecto = a - b;

  const overlay = document.createElement('div');
  overlay.className = 'popup-overlay popup-abrir';
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
  // No quitar la clase 'popup-abrir', dejar que la animación ocurra normalmente

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
          // Eliminar cualquier respuesta previa para esa pregunta
          window.respuestasUsuario = window.respuestasUsuario.filter(r => r.pregunta_id !== ej.pregunta_id);
          // Guardar la respuesta correcta
          fetch('guardar_respuestas.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              respuestas: [{
                pregunta_id: ej.pregunta_id,
                a: ej.a,
                b: ej.b,
                respuesta: respuestaUsuario,
                correcta: true
              }]
            })
          });
          window.respuestasUsuario.push({
            pregunta_id: ej.pregunta_id,
            a: ej.a,
            b: ej.b,
            respuesta: respuestaUsuario,
            correcta: true
          });
          mostrarEjercicios(false, paginaActual);
          // Animación de cierre
          overlay.classList.add('popup-cerrar');
          setTimeout(() => {
            overlay.remove();
          }, 350);
        }
      });
  // Agregar estilos para la animación si no existen
  if (!document.getElementById('popup-cerrar-style')) {
    const style = document.createElement('style');
    style.id = 'popup-cerrar-style';
    style.textContent = `
      .popup-abrir {
        animation: popupFadeIn 0.35s;
      }
      .popup-cerrar {
        animation: popupFadeOut 0.35s forwards;
      }
      @keyframes popupFadeIn {
        0% { opacity: 0; transform: scale(0.85); }
        100% { opacity: 1; transform: scale(1); }
      }
      @keyframes popupFadeOut {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.85); }
      }
    `;
    document.head.appendChild(style);
  }
    } else {
      // Guardar respuesta incorrecta
      ejerciciosActuales.forEach(ej => {
        if (`${ej.a} - ${ej.b}` === operacion) {
          window.respuestasUsuario = window.respuestasUsuario.filter(r => r.pregunta_id !== ej.pregunta_id);
          fetch('guardar_respuestas.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              respuestas: [{
                pregunta_id: ej.pregunta_id,
                a: ej.a,
                b: ej.b,
                respuesta: respuestaUsuario,
                correcta: false
              }]
            })
          });
          window.respuestasUsuario.push({
            pregunta_id: ej.pregunta_id,
            a: ej.a,
            b: ej.b,
            respuesta: respuestaUsuario,
            correcta: false
          });
          mostrarEjercicios(false, paginaActual);
        }
      });
      feedback.textContent = '❌ Incorrecto. Intenta de nuevo.';
      feedback.style.color = 'red';
    }
  });

  overlay.addEventListener('click', e => {
    if (e.target === overlay || e.target.classList.contains('btn-volver')) {
      overlay.remove();
    }
  });

  // Guardar respuestas antes de cerrar sesión
  const logoutForm = document.getElementById('logoutForm');
  if (logoutForm) {
    logoutForm.addEventListener('submit', function (e) {
      document.getElementById('respuestasInput').value = JSON.stringify(window.respuestasUsuario || []);
    });
  }

  // Variable global para guardar respuestas
  // window.respuestasUsuario = window.respuestasUsuario || [];
}
});
