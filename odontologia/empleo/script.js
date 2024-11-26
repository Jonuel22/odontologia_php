function mostrarFormulario(formulario) {
    document.getElementById('formDatosPersonales').style.display = 'none';
    document.getElementById('formInfoAdicional').style.display = 'none';

    document.getElementById(formulario).style.display = 'block';
}

function cancelarFormulario() {
    // Puedes redirigir a otra página o realizar alguna acción adicional aquí
    alert("Formulario cancelado");
}

