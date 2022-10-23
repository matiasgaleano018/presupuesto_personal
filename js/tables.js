const checkCat = document.getElementById("selecTodoCat");
const checkTabla = document.getElementById("flexCheckDefault");
const tabla = document.querySelectorAll("#datatablesSimple input[type=checkbox]");
const btn_eliminar = document.getElementById("btn-eliminar");
const moneda = document.getElementById("moneda");
const moneda1 = document.getElementById("moneda1");
const moneda2 = document.getElementById("moneda2");


document.getElementById("enviar").addEventListener("click", (e)=>{
    console.log(contarCheck());
    if(contarCheck() > 0){
        
    }else{
        e.preventDefault();
        alert("Debe seleccionar al menos un registro");
    }
});

checkCat.addEventListener("change", e =>{
    if(e.target.checked){
        checkAll();
    }else{
        uncheckAll();
    }
});

function checkAll() {
    document.querySelectorAll('#datatablesSimple input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });
}

function uncheckAll() {
    document.querySelectorAll('#datatablesSimple input[type=checkbox]').forEach(function(uncheckElement) {
        uncheckElement.checked = false;
    });
}

function contarCheck(){
    $contador = 0;
    document.querySelectorAll('#datatablesSimple input[type=checkbox]').forEach(function(e) {
        if(e.checked == true){
            contador++;
        }
    });

    return contador;
}

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
moneda2.addEventListener("keyup", (e)=>{
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
        alert('Solo se permiten numeros');
        input.value = input.value.replace(/[^\d\.]*/g,'');
    }
}