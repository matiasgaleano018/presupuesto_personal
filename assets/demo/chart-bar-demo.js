// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

let enlace = window.location.href;
enlace = enlace.slice(0, -21);

window.addEventListener('load', ()=>{
  traerIngresosM();
  traerEgresosM();
})

/*Grafico de ingresos*/
function traerIngresosM(){
  fetch(enlace + "assets/demo/ingresos_mes_json.php")
    .then(response => response.json())
    .then(data => mostrarIngresosM(data))

}


/*Grafico de ingresos*/
function traerEgresosM(){
  fetch(enlace + "assets/demo/egresos_mes_json.php")
    .then(response => response.json())
    .then(data => mostrarEgresosM(data))

}

let mesesIn = [];
let montosInBar = [];

function mostrarIngresosM(datos){

  mesesIn  = Object.keys(datos);
  montosInBar = Object.values(datos);

  let mayorMon = Math.max(...montosInBar);
  // Bar Chart Example
  var ctx = document.getElementById("ingre_mes");
  var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: mesesIn,
      datasets: [{
        label: "Capital",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: montosInBar,
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'mes'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 6
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            max: mayorMon,
            maxTicksLimit: 5
          },
          gridLines: {
            display: true
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });
}


let mesesEg = [];
let montosEgBar = [];

function mostrarEgresosM(datos){

  mesesEg  = Object.keys(datos);
  montosEgBar = Object.values(datos);

  let mayorMonE = Math.max(...montosEgBar);
  // Bar Chart Example
  var ctx1 = document.getElementById("egre_mes");
  var myLineChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: mesesEg,
      datasets: [{
        label: "Monto",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: montosEgBar,
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'mes'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 6
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            max: mayorMonE,
            maxTicksLimit: 5
          },
          gridLines: {
            display: true
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });
}