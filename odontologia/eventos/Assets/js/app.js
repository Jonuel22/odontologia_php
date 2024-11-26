let calendarEl = document.getElementById('calendar');
let frm = document.getElementById('formulario');
let eliminar = document.getElementById('btnEliminar');
let myModal = new bootstrap.Modal(document.getElementById('myModal'));
const servicio = document.getElementById("servicio");

function ObtenerFecha(){
    var fechaActual = new Date();
    var dia = fechaActual.getDate().toString().padStart(2, "0");
    var mes = (fechaActual.getMonth() + 1).toString().padStart(2, "0");
    var anio = fechaActual.getFullYear();
    var fechaFormateada = anio + "-" + mes + "-" + dia;
return    fechaFormateada;
}

function AsignaHora(){
//MODULO INTELIGENTE
const radioSeleccionado = document.querySelector('[name=tanda]:checked');
const descr = document.getElementById('title');
// Obtener el valor del radiobutton seleccionado
const tanda = radioSeleccionado.value;
const url = base_url + 'Home/AsignarHora';
const http = new XMLHttpRequest();
const formDta = new FormData();
formDta.append('id_serv', servicio.value);
formDta.append('tanda', tanda);
http.open("POST", url, true);
http.send(formDta);
http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        res=JSON.parse(this.responseText);
        hora.value=res.hora;
        doctor.value=res.doctor;
    }
}
}

servicio.addEventListener("change",function (){
    if(servicio.value=="") {
    document.getElementById('hora').value = "";
    document.getElementById('doctor').value ="";
        return;
    };
    AsignaHora();
    var title=document.getElementById('title');
    
 //-------------------------
});
const radioButtons = document.querySelectorAll('input[name="tanda"]');
radioButtons.forEach(radio => {
  radio.addEventListener('change', function() {
    if(document.getElementById('servicio').value=="") return;
    AsignaHora();
  });
});
document.addEventListener('DOMContentLoaded', function () {
    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev next today',
            center: 'title',
            right: 'dayGridMonth timeGridWeek listWeek'
        },
        events: base_url + 'Home/listar',
        editable: true,
   
        dateClick: function (info) {
            document.getElementById('tandas').style.display="block";
            frm.reset();
            eliminar.classList.add('d-none');
            document.getElementById('start').value = info.dateStr;
            document.getElementById('id').value = '';
            document.getElementById('btnAccion').textContent = 'Registrar';
            document.getElementById('titulo').textContent = 'Registrar Cita';
            //console.log(info);
            // var fechaActual = new Date();
            // var dia = fechaActual.getDate().toString().padStart(2, "0");
            // var mes = (fechaActual.getMonth() + 1).toString().padStart(2, "0");
            // var anio = fechaActual.getFullYear();
            // var fechaFormateada = anio + "-" + mes + "-" + dia;
           if(ObtenerFecha()<=info.dateStr)
            myModal.show();
            else{
                Swal.fire(
                    'Elija Una Fecha Valida!',
                    '',
                    'error'
                )
            }
        },

        eventClick: function (info) {
           document.getElementById('tandas').style.display="none";
            document.getElementById('id').value = info.event.id;
            document.getElementById('title').value = info.event.title;
            document.getElementById('start').value = info.event.startStr;
            document.getElementById('hora').value =info.event.extendedProps.hora;
            document.getElementById('doctor').value =info.event.extendedProps.doctor;
            document.getElementById('servicio').value =info.event.extendedProps.servicio;
            document.getElementById('paciente').value =info.event.extendedProps.paciente;
            document.getElementById('color').value = info.event.backgroundColor;
            document.getElementById('btnAccion').textContent = 'Modificar';
            document.getElementById('titulo').textContent = 'Actualizar Evento';
            eliminar.classList.remove('d-none');
            myModal.show();
        },
        eventDrop: function (info) {
            const start = info.event.startStr;
            if(ObtenerFecha()<=start){

               
                const id = info.event.id;
                const url = base_url + 'Home/drag';
                const http = new XMLHttpRequest();
                const formDta = new FormData();
                formDta.append('start', start);
                formDta.append('id', id);
    
                http.open("POST", url, true);
                http.send(formDta);
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                         Swal.fire(
                             'Avisos!',
                             res.msg,
                             res.tipo
                         )
                        if (res.estado) {
                            myModal.hide();
                            calendar.refetchEvents();
                        }
                    }
                }
            }
            else
            {

                
                Swal.fire(
                    'Elija Una Fecha Valida!',
                    '',
                    'error'
                )
            }
            calendar.refetchEvents();
        }

    });
    calendar.render();
    frm.addEventListener('submit', function (e) {
       
        e.preventDefault();
        const title = document.getElementById('title').value;
        const start = document.getElementById('start').value;
       
        if (title == '' || start == '') {
             Swal.fire(
                 'Avisos!',
                 'Todos los campos son obligatorios',
                 'warning'
             )
        } else {
            const url = base_url + 'Home/registrar';
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                    
                     Swal.fire(
                         'Avisos!',
                         res.msg,
                         res.tipo
                     )
                    if (res.estado) {
                        myModal.hide();
                        calendar.refetchEvents();
                    }
                }
            }
        }
    });
    eliminar.addEventListener('click', function () {
        myModal.hide();
        Swal.fire({
            title: 'Advertencia!',
            text: "Esta seguro de eliminar!?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Borrar!'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = base_url + 'Home/eliminar/' + document.getElementById('id').value;
                const http = new XMLHttpRequest();
                http.open("GET", url, true);
                http.send();
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        Swal.fire(
                            'Avisos!',
                            res.msg,
                            res.tipo
                        )
                        if (res.estado) {
                            calendar.refetchEvents();
                        }
                    }
                }
            }
        })
    });
});