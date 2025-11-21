let URLpatname = window.location.pathname,
URLhref = window.location.href;

const log = console.log,
URL_BASE_API = URLhref.split(URLpatname)[0] + "/api",
URL_BASE_HOST = URLhref.split(URLpatname)[0],
HTTP_OK = 200,
HTTP_NOT_FOUND = 404,
preload = `
<!-- Growing Color spinnersr -->
<div class="spinner-grow text-primary" role="status">
<span class="visually-hidden">Loading...</span>
</div>
<div class="spinner-grow text-secondary" role="status">
<span class="visually-hidden">Loading...</span>
</div>
<div class="spinner-grow text-success" role="status">
<span class="visually-hidden">Loading...</span>
</div>
`;

const darFormatoDeNumero = (numero) => {
    return  new Intl.NumberFormat("de-DE", {
      maximumFractionDigits: 2,
      minimumFractionDigits: 2
    }).format(numero)
    // return  new Intl.NumberFormat("de-DE", {
    //   maximumFractionDigits: 2,
    // }).format(numero)
};

const formatoUSD = (numero) => {
    return  new Intl.NumberFormat("en-US", {
      maximumFractionDigits: 2,
    }).format(numero)
};

const quitarFormato = (numeroString) =>{
    let arraysinformato = '',
    arregloDeNumeros = numeroString.split('');
    
    if(numeroString.includes(',')){
        arraysinformato = arregloDeNumeros.map(item => {
            if(item == ".") return "";
            if(item == ",") return ".";
            return item;
        });
    }else{
        arraysinformato = arregloDeNumeros;
    }
    return parseFloat(arraysinformato.join(''));
};

const quitarComaAlFinal = async (input) => {
    input.value = input.value.slice(0, input.value.length - 1);
};  