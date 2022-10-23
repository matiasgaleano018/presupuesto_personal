//const moneda = document.querySelectorAll('.monto');
const moneda  = document.getElementById("moneda");
const moneda1 = document.getElementById("moneda1");

moneda.addEventListener("keyup", (e)=>{
    if(e.key != ","){
        numero_for = formato(e.target.value);
        moneda.value = numero_for;
    }
});

moneda1.addEventListener("keyup", (e)=>{
    if(e.key != ","){
        numero_for = formato(e.target.value);
        moneda1.value = numero_for;
    }
});

function formato(input){
    var numero = input.split(',');
    var num = numero[0].replace(/\./g,'');
    if(!isNaN(num)){
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        if(numero[1]){
            return num + "," + numero[1];
        }else{
            return num;
        }
        
    }else{ 
        return "";
    }
}