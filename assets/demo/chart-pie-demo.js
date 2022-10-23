// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

let enlacePie = window.location.href;
enlacePie = enlacePie.slice(0, -21);

window.addEventListener('load', ()=>{
  traerIngresos();
  traerEgresos();
})



/*Grafico de ingresos*/
function traerIngresos(){
  fetch(enlacePie + "assets/demo/ingresos_json.php")
    .then(response => response.json())
    .then(data => mostrarIngresos(data))

}

let nombresIn = [];
let valoresIn = [];

let montosIn = [];
let colorIn = [];
function mostrarIngresos(datos){
  
  nombresIn = Object.keys(datos);
  valoresIn = Object.values(datos);

  for(let i = 0; i < nombresIn.length; i++){
    montosIn.push(valoresIn[i].total);
    colorIn.push(valoresIn[i].color);
  }
  // Pie Chart Example
  var ctx = document.getElementById("ingreso_graf");
  var ingreso_graf = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: nombresIn,
      datasets: [{
        data: montosIn,
        backgroundColor: colorIn,
      }],
    },
  });
}


function traerEgresos(){
  fetch(enlacePie + "assets/demo/egresos_json.php")
    .then(response => response.json())
    .then(data => mostrarEgresos(data))

}

let nombresEg = [];
let valoresEg = [];

let montosEg = [];
let colorEg = [];
function mostrarEgresos(datos){
  
  nombresEg = Object.keys(datos);
  valoresEg = Object.values(datos);

  for(let i = 0; i < nombresIn.length; i++){
    montosEg.push(valoresEg[i].total);
    colorEg.push(valoresEg[i].color);
  }
 
  var ctx = document.getElementById("egreso_graf");
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: nombresEg,
      datasets: [{
        data: montosEg,
        backgroundColor: colorEg,
      }],
    },
  });
}
