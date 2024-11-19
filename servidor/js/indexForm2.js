const crearSesionLink = document.getElementById('crear-sesion-link');
const iniciarSesionLink = document.getElementById('iniciar-sesion-link');
const formContainer = document.getElementById('form-container');
const cancelarButton = document.getElementById('cancelar');
//const formEmociones = document.getElementById('emociones');

function crearFormulario(tipo) {
    // formulario ideado por mi en html pasado a chatgpt para pasarlo a creacion desde js por vagancia
    // Limpiar el contenedor del formulario
    formContainer.innerHTML = '';

    // Crear el elemento del formulario
    const form = document.createElement('form');
    form.id = 'form';
    form.method = 'POST';

    // Título del formulario
    const title = document.createElement('h2');
    title.id = 'form-title';
    title.textContent = tipo === 'crear' ? 'Crear sesión' : 'Iniciar sesión';
    form.appendChild(title);

    // Campo de usuario
    const labelUsername = document.createElement('label');
    labelUsername.setAttribute('for', 'username');
    labelUsername.textContent = 'Usuario:';
    form.appendChild(labelUsername);
    
    const inputUsername = document.createElement('input');
    inputUsername.type = 'text';
    inputUsername.id = 'username';
    inputUsername.name = 'username';
    inputUsername.required = true;
    form.appendChild(inputUsername);

    // Campo de contraseña
    const labelPassword = document.createElement('label');
    labelPassword.setAttribute('for', 'password');
    labelPassword.textContent = 'Contraseña:';
    form.appendChild(labelPassword);
    
    const inputPassword = document.createElement('input');
    inputPassword.type = 'password';
    inputPassword.id = 'password';
    inputPassword.name = 'password';
    inputPassword.required = true;
    form.appendChild(inputPassword);

    // Si se está creando una sesión, agregar campos adicionales
    if (tipo === 'crear') {
        const labelConfirmPassword = document.createElement('label');
        labelConfirmPassword.setAttribute('for', 'confirm-password');
        labelConfirmPassword.textContent = 'Confirmar Contraseña:';
        form.appendChild(labelConfirmPassword);
        
        const inputConfirmPassword = document.createElement('input');
        inputConfirmPassword.type = 'password';
        inputConfirmPassword.id = 'confirm-password';
        inputConfirmPassword.name = 'confirm-password';
        inputConfirmPassword.required = true;
        form.appendChild(inputConfirmPassword);

        const labelEmail = document.createElement('label');
        labelEmail.setAttribute('for', 'email');
        labelEmail.textContent = 'Correo Electrónico:';
        form.appendChild(labelEmail);
        
        const inputEmail = document.createElement('input');
        inputEmail.type = 'email';
        inputEmail.id = 'email';
        inputEmail.name = 'email';
        inputEmail.required = true;
        form.appendChild(inputEmail);
    }

    // Establecer la acción del formulario
    form.action = tipo === 'crear' ? 'register.php' : 'login.php';

    // Botón de enviar
    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.textContent = 'Enviar';
    form.appendChild(submitButton);

    // Botón de cancelar
    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.id = 'cancelar';
    cancelButton.textContent = 'Cancelar';
    form.appendChild(cancelButton);

    // Agregar el formulario al contenedor
    formContainer.appendChild(form);

    // Manejar el evento de cancelar
    cancelButton.addEventListener('click', () => {
        formContainer.classList.add('hidden');
    });
}

if(crearSesionLink){
    crearSesionLink.addEventListener('click', () => {
        formContainer.classList.remove('hidden');
        crearFormulario('crear');
    });
}

if(iniciarSesionLink){
    iniciarSesionLink.addEventListener('click', () => {
        formContainer.classList.remove('hidden');
        crearFormulario('iniciar');
    });
}

