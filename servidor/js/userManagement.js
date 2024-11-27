

async function enseñarUsuarios() {
    try {
        const response = await fetch('read.php?action=users');

        let usuarios = await response.json();
        console.log(usuarios);
        //igual se neceista pasar a json
        let listaUsuarios = document.getElementById("listaUsuarios");
        listaUsuarios.innerHTML = "";
        usuarios.forEach(user => {
            let conjuntoUsuario = document.createElement('ul');

            let nombreUsuario = document.createElement('li');
            nombreUsuario.innerText = "Usuario: " + user["username"];

            let mailUsuario = document.createElement('li');
            mailUsuario.innerText = "Email: " + user["mail"];

            let passwordUsuario = document.createElement('li');
            passwordUsuario.innerText = "Contraseña: " + user["password"];

            let roleUsuario = document.createElement('li');
            roleUsuario.innerText = "Rol: " + user["role"];

            let emotionUsuario = document.createElement('li');
            emotionUsuario.innerText = "Emocion: " + (user["emotion"] != '' ? user["emotion"] : "Ningun estado de animo seleccionado");

            conjuntoUsuario.appendChild(nombreUsuario);
            conjuntoUsuario.appendChild(mailUsuario);
            conjuntoUsuario.appendChild(passwordUsuario);
            conjuntoUsuario.appendChild(roleUsuario);
            conjuntoUsuario.appendChild(emotionUsuario);

            let conjuntoBotones = document.createElement("div");

            let botonEditar = document.createElement("button");
            botonEditar.innerText = "Editar";
            botonEditar.style.backgroundColor = "gold";
            botonEditar.addEventListener("click", () => {
                document.getElementById("username").value = user["username"];
                document.getElementById("mail").value = user["mail"];
                document.getElementById("role").value = user["role"];
                document.getElementById("emotion").value = user["emotion"];
                document.getElementById("form-container").classList.remove("hidden");

                document.getElementById("form").addEventListener("submit", async (event)=>{
                    event.preventDefault();
/*                     console.log({
                        'id': user['id'],
                        'username': document.getElementById("username").value,
                        'password': user['password'],
                        'mail': document.getElementById("mail").value,
                        'role': document.getElementById("role").value,
                        'emotion': document.getElementById("emotion").value
                    }); */ // se llega hasta aqui
                    await fetch('update.php', {
                        method: 'PUT',
                        body: JSON.stringify({
                            'id': user['id'],
                            'username': document.getElementById("username").value,
                            'password': user['password'],
                            'mail': document.getElementById("mail").value,
                            'role': document.getElementById("role").value,
                            'emotion': document.getElementById("emotion").value
                        }),
                        headers: { 'Content-Type': 'application/json' }
                    })
                    document.getElementById("form-container").classList.add("hidden");
                    enseñarUsuarios();
                },false);


            }, false);

            let botonBorrar = document.createElement("button");
            botonBorrar.innerText = "Eliminar";
            botonBorrar.style.backgroundColor = "red";
            botonBorrar.addEventListener("click", async () => {
                const response = await fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Formato adecuado para enviar datos
                    },
                    body: new URLSearchParams({
                        id: user["id"], // Pasando el `id` a través del cuerpo de la solicitud
                    })
                });
                enseñarUsuarios();
            }, false);

            conjuntoBotones.appendChild(botonEditar);
            conjuntoBotones.appendChild(botonBorrar);

            conjuntoUsuario.appendChild(conjuntoBotones);
            listaUsuarios.appendChild(conjuntoUsuario);
            if (usuarios.indexOf(user) % 2 == 0) {
                conjuntoUsuario.style.backgroundColor = "#E6E6FA";
            }
        });
    } catch (error) {
        console.error('Error al obtener Usuarios:', error);
        alert('Error al obtener Usuarios: ' + error.message);
    }
}
function showEditUserForm() {

}
enseñarUsuarios();