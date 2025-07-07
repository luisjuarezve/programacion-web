const container = document.getElementById("ejercicios-container");
const reiniciarBtn = document.getElementById("reiniciar-btn");

let ejercicios = [];

function generarEjercicios() {
  container.innerHTML = "";
  ejercicios = [];

  for (let i = 0; i < 8; i++) {
    const a = Math.floor(Math.random() * 50);
    const b = Math.floor(Math.random() * 50);
    const resultado = a + b;

    ejercicios.push({ a, b, resultado, resuelto: false });

    const div = document.createElement("div");
    div.className = "ejercicio";
    div.innerHTML = `<strong>${a} + ${b} = ?</strong>`;
    div.addEventListener("click", () => expandirEjercicio(div, i));
    container.appendChild(div);
  }
}

function expandirEjercicio(div, index) {
  if (ejercicios[index].resuelto) return;

  const { a, b, resultado } = ejercicios[index];

  div.classList.add("expandido");
  container.querySelectorAll(".ejercicio").forEach(e => {
    if (e !== div) e.style.display = "none";
  });

  div.innerHTML = `
    <p><strong>${a} + ${b} = ?</strong></p>
    <input type="number" id="respuesta" />
    <button onclick="verificar(${index})">Responder</button>
    <button onclick="cerrar()">Volver</button>
  `;
}

function verificar(index) {
  const input = document.getElementById("respuesta");
  const div = container.children[index];
  const valor = parseInt(input.value);

  if (valor === ejercicios[index].resultado) {
    ejercicios[index].resuelto = true;
    div.classList.remove("expandido");
    div.classList.add("correcto");
    div.innerHTML = `✅ ${ejercicios[index].a} + ${ejercicios[index].b} = ${valor}`;
  } else {
    div.classList.remove("expandido");
    div.classList.add("incorrecto");
    div.innerHTML = `❌ Intenta de nuevo`;
  }

  container.querySelectorAll(".ejercicio").forEach(e => e.style.display = "block");
}

function cerrar() {
  container.querySelectorAll(".ejercicio").forEach(e => e.style.display = "block");
  document.querySelector(".expandido")?.classList.remove("expandido");
}

reiniciarBtn.addEventListener("click", generarEjercicios);
generarEjercicios();
