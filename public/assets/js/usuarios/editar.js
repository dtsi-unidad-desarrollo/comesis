let inputCedula = document.getElementById('inputCedula'),
BtnActivarEdicionDeCedula = document.getElementById('activarEdicionDeCedula'),
counter = 1;

BtnActivarEdicionDeCedula.addEventListener('click', (e)=>{
    if(counter%2){
        if(e.target.localName == "i"){
            e.target.parentElement.classList.remove('btn', 'btn-warning');
            e.target.parentElement.classList.add('btn', 'btn-primary');
            e.target.classList.remove('bi', 'bi-pencil');
            e.target.classList.add('bi', 'bi-file-lock2');
    
        }
        if(e.target.localName == "button"){
            e.target.classList.remove('btn', 'btn-warning');
            e.target.classList.add('btn', 'btn-primary');
            e.target.firstElementChild.classList.remove('bi', 'bi-pencil');
            e.target.firstElementChild.classList.add('bi', 'bi-file-lock2');
        }
        inputCedula.disabled = false;
        inputCedula.readOnly = false;
    }else{
        if(e.target.localName == "i"){
            e.target.parentElement.classList.remove('btn', 'btn-primary');
            e.target.parentElement.classList.add('btn', 'btn-warning');
            e.target.classList.remove('bi', 'bi-file-lock2');
            e.target.classList.add('bi', 'bi-pencil');
        }
        if(e.target.localName == "button"){
            e.target.classList.remove('btn', 'btn-primary');
            e.target.classList.add('btn', 'btn-warning');
            e.target.firstElementChild.classList.remove('bi', 'bi-file-lock2');
            e.target.firstElementChild.classList.add('bi', 'bi-pencil');
        }
        inputCedula.disabled = true;
        inputCedula.readOnly = true;
    }

    counter++;
   
});