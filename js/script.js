document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.querySelector('.contenedor-menu');
  let ejerciciosActuales = [];
  let nivelSeleccionado = null;

  // Recuperar progreso y respuestas del usuario
  fetch('recuperar_respuestas.php')
    .then(res => res.json())
    .then(data => {
      if (data.nivel_desbloqueado) {
        window.nivelDesbloqueado = data.nivel_desbloqueado;
      }
      if (Array.isArray(data.respuestas)) {
        window.respuestasUsuario = data.respuestas;
      }
    });

  // Delegación de eventos para niveles
  contenedor.addEventListener('click', function (e) {
    if (e.target.classList.contains('nivel') && !e.target.classList.contains('bloqueado')) {
      e.preventDefault();
      nivelSeleccionado = e.target.dataset.nivel;
      mostrarEjercicios(nivelSeleccionado);
    }
  });

  function mostrarEjercicios(nivel, reiniciar = false) {
    nivelSeleccionado = nivel;
    window.nivelDesbloqueado = Math.max(window.nivelDesbloqueado || 1, parseInt(nivel));

    // Si no hay preguntas guardadas para este nivel, generarlas y guardarlas en la base de datos
    let preguntasNivel = window.respuestasUsuario ? window.respuestasUsuario.filter(r => r.nivel == nivel) : [];
    if (preguntasNivel.length < 8 || reiniciar) {
      ejerciciosActuales = [];
      for (let i = 1; i <= 8; i++) {
        let a = Math.floor(Math.random() * 50) + 20;
        let b = Math.floor(Math.random() * 20);
        ejerciciosActuales.push({ a, b, resuelto: false, pregunta_id: i });
      }
      // Guardar todas las preguntas generadas en la base de datos como incorrectas inicialmente
      fetch('guardar_respuestas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          nivel: parseInt(nivel),
          respuestas: ejerciciosActuales.map(ej => ({
            nivel: parseInt(nivel),
            pregunta_id: ej.pregunta_id,
            a: ej.a,
            b: ej.b,
            respuesta: '',
            correcta: false
          }))
        })
      }).then(() => {
        // Actualizar window.respuestasUsuario para reflejar las preguntas generadas
        window.respuestasUsuario = window.respuestasUsuario.filter(r => r.nivel != nivel);
        window.respuestasUsuario = window.respuestasUsuario.concat(ejerciciosActuales.map(ej => ({
          nivel: parseInt(nivel),
          pregunta_id: ej.pregunta_id,
          a: ej.a,
          b: ej.b,
          respuesta: '',
          correcta: false
        })));
        renderGrid();
      });
      return;
    } else {
      // Mantener el orden de las preguntas por pregunta_id
      preguntasNivel.sort((a, b) => a.pregunta_id - b.pregunta_id);
      ejerciciosActuales = preguntasNivel.map(r => ({
        a: r.a,
        b: r.b,
        resuelto: !!r.correcta,
        pregunta_id: r.pregunta_id,
        incorrecta: r.respuesta !== '' && !r.correcta
      }));
    }
    renderGrid();

    function renderGrid() {
      let ejerciciosHTML = '';
      ejerciciosActuales.forEach(({ a, b, resuelto, incorrecta }, idx) => {
        const operacion = `${a} - ${b}`;
        let mensaje = '';
        let operacionHTML;
        if (resuelto) {
          // Carta verde, título Resuelto, deshabilitada
          const resp = window.respuestasUsuario.find(r => r.nivel == nivel && r.pregunta_id == idx + 1 && r.correcta);
          operacionHTML = `
            <div class=\"resuelto-titulo\" style=\"font-size:1.1em;font-weight:bold;margin-bottom:6px;\">Resuelto</div>
            <div class=\"resta-grid\">
              <div class=\"minuendo\">${a.toString().padStart(3, '0')}</div>
              <div class=\"sustraendo-grid\">${b.toString().padStart(3, '0')}</div>
              <div class=\"linea-grid\"></div>
            </div>
            <div style=\"margin-top:8px;font-size:1.2em;font-weight:bold;display:flex;align-items:center;gap:6px;justify-content:center;\"><span style=\"font-size:1.2em;\">✔</span> <span>${resp ? resp.respuesta : (a-b)}</span></div>
          `;
        } else if (incorrecta) {
          // Incorrecto: solo una operación, formato igual a correcto pero en rojo
          const resp = window.respuestasUsuario.find(r => r.nivel == nivel && r.pregunta_id == idx + 1 && !r.correcta && r.respuesta !== '');
          operacionHTML = `
            <div class=\"incorrecto-titulo\" style=\"font-size:1.1em;font-weight:bold;margin-bottom:4px;color:#c62828;\">Incorrecto</div>
            <div class=\"resta-grid\">
              <div class=\"minuendo\">${a.toString().padStart(3, '0')}</div>
              <div class=\"sustraendo-grid\">${b.toString().padStart(3, '0')}</div>
              <div class=\"linea-grid\"></div>
            </div>
            <div style=\"margin-top:8px;font-size:1.2em;font-weight:bold;display:flex;align-items:center;gap:6px;justify-content:center;color:#c62828;\"><span style=\"font-size:1.2em;\">❌</span> <span>${resp ? resp.respuesta : ''}</span></div>
          `;
        } else {
          operacionHTML = `
          <div class=\"resta-grid\">\n        <div class=\"minuendo\">${a.toString().padStart(3, '0')}</div>\n        <div class=\"sustraendo-grid\">${b.toString().padStart(3, '0')}</div>\n        <div class=\"linea-grid\"></div>\n      </div>`;
        }
        ejerciciosHTML += `
          <div class=\"ejercicio ${resuelto ? 'resuelto' : ''}\" 
               data-operacion=\"${operacion}\" 
               style=\"${resuelto ? 'background-color:#4CAF50; color:white; pointer-events:none;' : ''}\">
            ${operacionHTML}
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
        // Eliminar respuestas guardadas de ese nivel en el frontend
        if (window.respuestasUsuario) {
          window.respuestasUsuario = window.respuestasUsuario.filter(r => r.nivel != nivelActual);
        }
        // Eliminar respuestas guardadas de ese nivel en el backend
        fetch('reiniciar_nivel.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ nivel: nivelActual })
        });
        mostrarEjercicios(nivelActual, true);
      });
    }
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
            // Guardar respuesta correcta
            // Eliminar cualquier respuesta previa para ese nivel y pregunta
            window.respuestasUsuario = window.respuestasUsuario.filter(r => !(r.nivel == nivelSeleccionado && r.pregunta_id == ej.pregunta_id));
            // Sobrescribir la respuesta en la base de datos
            fetch('guardar_respuestas.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                nivel: parseInt(nivelSeleccionado),
                respuestas: [{
                  nivel: parseInt(nivelSeleccionado),
                  pregunta_id: ej.pregunta_id,
                  a: ej.a,
                  b: ej.b,
                  respuesta: respuestaUsuario,
                  correcta: true
                }]
              })
            });
            // Copiar el objeto para evitar referencias compartidas
            window.respuestasUsuario.push({
              nivel: nivelSeleccionado,
              pregunta_id: ej.pregunta_id,
              a: ej.a,
              b: ej.b,
              respuesta: respuestaUsuario,
              correcta: true
            });
            // Actualizar el grid para mostrar el cambio
            mostrarEjercicios(nivelSeleccionado);
          }
        });

        setTimeout(() => {
          // Si todas las preguntas están resueltas, desbloquear el siguiente nivel
          const todasResueltas = ejerciciosActuales.length === 8 && ejerciciosActuales.every(ej => ej.resuelto);
          if (todasResueltas) {
            const nivelActual = parseInt(nivelSeleccionado);
            if (window.nivelDesbloqueado < nivelActual + 1) {
              window.nivelDesbloqueado = nivelActual + 1;
              // Guardar el progreso en el backend
              fetch('actualizar_progreso.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nivel_desbloqueado: window.nivelDesbloqueado })
              }).then(() => {
                // Refrescar menú para mostrar el nuevo nivel desbloqueado
                setTimeout(() => location.reload(), 800);
              });
            }
          }
        }, 1000);
      } else {
        // Guardar respuesta incorrecta
        ejerciciosActuales.forEach(ej => {
          if (`${ej.a} - ${ej.b}` === operacion) {
            // Eliminar cualquier respuesta previa para ese nivel y pregunta
            window.respuestasUsuario = window.respuestasUsuario.filter(r => !(r.nivel == nivelSeleccionado && r.pregunta_id == ej.pregunta_id));
            fetch('guardar_respuestas.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                nivel: parseInt(nivelSeleccionado),
                respuestas: [{
                  nivel: parseInt(nivelSeleccionado),
                  pregunta_id: ej.pregunta_id,
                  a: ej.a,
                  b: ej.b,
                  respuesta: respuestaUsuario,
                  correcta: false
                }]
              })
            });
            window.respuestasUsuario.push({
              nivel: nivelSeleccionado,
              pregunta_id: ej.pregunta_id,
              a: ej.a,
              b: ej.b,
              respuesta: respuestaUsuario,
              correcta: false
            });
            // Actualizar el grid para mostrar el mensaje de error
            mostrarEjercicios(nivelSeleccionado);
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
  }

  // Guardar respuestas y progreso antes de cerrar sesión
  const logoutForm = document.getElementById('logoutForm');
  if (logoutForm) {
    logoutForm.addEventListener('submit', function (e) {
      // Guardar el nivel desbloqueado
      document.getElementById('nivelDesbloqueadoInput').value = window.nivelDesbloqueado || 1;
      // Guardar TODAS las respuestas del usuario SIEMPRE
      document.getElementById('respuestasInput').value = JSON.stringify(window.respuestasUsuario || []);
    });
  }

  // Variables globales para guardar progreso y respuestas
  window.nivelDesbloqueado = window.nivelDesbloqueado || 1;
  window.respuestasUsuario = window.respuestasUsuario || [];

});
