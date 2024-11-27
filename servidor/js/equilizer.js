//Some howler variables
let songVolume = 0.5;
let howler;

let maxDuration;
let duration;


let playList = [];
let currentTrack = 0;
let isSecondsActive = false;
//Equalizer
//Get the audio context for the analyzer and get the number of samples
let analyser;
let bufferLength;
let dataArray;

let gainNode;
let bassFilter;
let trebleFilter;
//Get the canvas and the context to use the equalizer
let canvas = document.getElementById('equalizer');
let ctx = canvas.getContext('2d');



async function enseñarCanciones(emotion) {

    try {
        let response = await fetch(`http://localhost/practica5/servidor/php/read.php?emotion=${encodeURIComponent(emotion)}&action=songs`); // le doy una emocion y una accion para que el crud filtre la solicitud

        // Verificar si la respuesta fue exitosa (status HTTP 200)
        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }

        // Intentar convertir la respuesta en JSON
        playList = await response.json();

        // Verificar si la respuesta contiene un error
        if (playList.error) {
            console.error(playList.error);
            // Aquí puedes manejar el caso de error, como mostrar un mensaje al usuario
        } else {
            let songList = document.getElementById("songList");

            playList.forEach(song => {
                let songDiv = document.createElement("img");
                songDiv.innerText = song["image"];
                songDiv.src = song["image"];
                songDiv.setAttribute('data-index', playList.indexOf(song))
                songDiv.addEventListener("click", () => {
                    //pone la foto
                    ponerFoto(song["image"]);
                    // carga la cancion
                    loadSongs(song["src"], song["image"]);
                    // crea los inputs
                    crearInputs(song["title"], song["artist"]);
                    // cambio el numero de la track
                    currentTrack = event.currentTarget.getAttribute('data-index');
                    console.log(currentTrack);
        
                    howler.play();
        
                }, false)
                songList.appendChild(songDiv);
            });
            let shuffleBtn = document.createElement("button");
            shuffleBtn.id = "shuffleBtn";
            shuffleBtn.innerText = "Mezcla";
            shuffleBtn.addEventListener("click", () => {
                
                //su no funciona es porque he tocado esto
                playList = shuffle(playList);
                //reorganizar
                reorganizar(playList);
            }, false)
        
            document.getElementById("sideFooter").appendChild(shuffleBtn);
        }
    } catch (error) {
        console.error("Error al obtener canciones: ", error);
        // Puedes mostrar un mensaje de error en la interfaz de usuario si ocurre algún problema
    }
    
}
//reorganizar pistas
function reorganizar(pPlaylist) {
    let songList = document.getElementById("songList");
    songList.innerHTML = "";

    pPlaylist.forEach(song => {
        let songDiv = document.createElement("img");
        songDiv.innerText = song["image"];
        songDiv.src = song["image"];
        songDiv.setAttribute('data-index', playList.indexOf(song))
        songDiv.addEventListener("click", () => {
            //pone la foto
            ponerFoto(song["image"]);
            // carga la cancion
            loadSongs(song["src"], song["image"]);
            // crea los inputs
            crearInputs(song["title"], song["artist"]);
            // cambio el numero de la track
            currentTrack = event.currentTarget.getAttribute('data-index');
            console.log(currentTrack);

            howler.play();

        }, false)
        songList.appendChild(songDiv);
    });
}
//Loading Songs
const loadSongs = async (pSrc, image) => {
    Howler.unload();// lo destruimos (no podemos usar howler sino Howler) para que no se vayan sumando instancias
    howler = new Howl({
        src: pSrc,
        volume: songVolume,
        onplay: function () {
            gainNode = Howler.ctx.createGain();

            //creo el filtro de los bajos
            bassFilter = Howler.ctx.createBiquadFilter();
            bassFilter.type = 'lowshelf';
            bassFilter.frequency.value = 300; // lo digo que sean los bajos, y que la frecuencia es de 300

            //filtro para agudos
            trebleFilter = Howler.ctx.createBiquadFilter();
            trebleFilter.type = 'highshelf';
            trebleFilter.frequency.value = 3000;

            //lo tengo que conectar
            howler._sounds[0]._node.connect(bassFilter);
            bassFilter.connect(trebleFilter);
            trebleFilter.connect(gainNode); //lo que no se es por que se tiene que hacer un trenecito

            gainNode.connect(Howler.ctx.destination);
        },
        onload: function () {
            maxDuration = howler._duration;
        },
        onend: function () {
            currentTrack++;
            if (currentTrack >= playList.length) {
                currentTrack = 0;
            }
            cambiarPista(currentTrack);
        }
    });

    //Falta el tratamiento de las propiedades de la canción y toda la creación de la radio. Falta la creación y gestión de la lista de reproducción

    //Equilizer
    let color = await getColor(image);
    analyser = Howler.ctx.createAnalyser();    //Proporciona acceso a la frecuencia y los datos de tiempo del audio que está siendo reproducido. 
    bufferLength = analyser.frequencyBinCount; //Indica el número de muestras de datos que se obtendrán del audio.
    dataArray = new Uint8Array(bufferLength);





    loadEqualizer();
    animateEqualizer(color);
}
// radios

//datos json radio hardcodeadas

function loadEqualizer() {
    // Conexion del masterGain (el volumen maestro de Howler.js) con el analyzer, permitiendo que el ecualizador recoja datos del audio que se está reproduciendo.
    Howler.masterGain.connect(analyser);

    // Conecta analyzer en el destino de audio. El audio sigue reproduciéndose en los altavoces o auriculares mientras se analiza
    analyser.connect(Howler.ctx.destination);

    // Coloca la frecuencia de muestreo. Obtiene un equilibrio entre la resolución temporal y la precisión de la frecuencia.
    analyser.fftSize = 2048;

    // Se utiliza para obtener los datos de forma de onda del audio en tiempo real, lo que se conoce como datos de dominio temporal. Devuelve la representación de la señal de audio en el dominio del tiempo, es decir, cómo varía la amplitud del sonido a lo largo del tiempo.
    analyser.getByteTimeDomainData(dataArray);
    console.log("cargado");
}


function animateEqualizer(color) {
    /* console.log(dataArray); */
    // Limpia el lienzo del canvas para pintar de nuevo
    ctx.clearRect(0, 0, canvas.offsetWidth, canvas.height);

    // Obtiene los datos de frecuencia del audio
    analyser.getByteFrequencyData(dataArray);

    const maxBarHeight = canvas.offsetHeight * 0.25; // Altura máxima deseada
    const radius = Math.min(canvas.offsetWidth, canvas.offsetHeight) * 0.25; // Radio del círculo
    const centerX = canvas.offsetWidth / 2; // Centro del canvas
    const centerY = canvas.offsetHeight / 2; // Centro del canvas

    let total = 0;
    let maximoTotal = 0;
    // Recorre el array de datos de frecuencia y dibuja las líneas radiales
    for (let i = 0; i < bufferLength * 0.5; i++) {// adaptado ya que nos e muestran todsa las frecuencias
        // Normalizar y escalar el valor
        const barHeight = (dataArray[i] / 255) * maxBarHeight; // Normaliza el valor entre 0 y maxBarHeight
        const angle = (i / (bufferLength * 0.5)) * (Math.PI * 2) - (Math.PI / 2); // Calcula el ángulo correspondiente// adaptado ya que nos e muestran todsa las frecuencias de debe multiplicar por 1/ lo reducido
        const x = centerX + Math.cos(angle) * (radius + barHeight); // Coordenada x //el destino se mueve segun el angulo correspondiente
        const y = centerY + Math.sin(angle) * (radius + barHeight); // Coordenada y
        ctx.fillStyle = color; // Cambia el color de las barras según tu preferencia
        ctx.beginPath();
        ctx.moveTo(centerX, centerY); // Mueve el cursor al centro // lo dibuja desde el centro 
        ctx.lineTo(x, y); // Dibuja la línea hacia el borde // hacia el destino
        ctx.lineWidth = 2; // Grosor de la línea
        ctx.strokeStyle = color; // Cambia el color del trazo
        ctx.stroke(); // Dibuja la línea // el fill Style no sirve para estas barras.

        total += barHeight;
        if (barHeight > maximoTotal) {
            maximoTotal = barHeight;
        }
    }
    if (songVolume > 0) {
        total = 1 + (Math.round(total / (bufferLength * 0.5) / 3) / 100);
    }
    else {
        total = 1;
    }

    document.getElementById("imagen").style.scale = total;

    duration = Math.floor(howler.seek());
    if (document.getElementById("seconds")) {
        if (duration > 0 && !isSecondsActive) {
            document.getElementById("seconds").value = (duration * 100) / maxDuration;
            /*  console.log(document.getElementById("seconds").value); */
        }
    }


    // Repite la animación
    animationFrame = requestAnimationFrame(animateEqualizer);
}



/* function playSong(track) {
    console.log(track);
} */
function ponerFoto(image) {
    let canvasContainer = document.getElementById("imgContainer");
    let imagen = document.getElementById("imagen");//si existe una imagen previa
    if (imagen) {
        imagen.remove();//la borramos
    }

    imagen = document.createElement('img');// y creamos la nueva
    imagen.id = "imagen";
    imagen.src = image;

    canvasContainer.appendChild(imagen);
    mostrar(document.getElementById("optionsContainer"));
}

function ponerTitulo(titulo, artista) {

}

function getColor(src) {//chat gpt 
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.crossOrigin = "Anonymous"; // Necesario para imágenes cargadas desde dominios diferentes
        img.src = src;
        img.onload = function () {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            let r = 0, g = 0, b = 0;

            for (let i = 0; i < data.length; i += 4) {
                r += data[i];
                g += data[i + 1];
                b += data[i + 2];
            }

            const pixelCount = data.length / 4;
            r = Math.floor(r / pixelCount);
            g = Math.floor(g / pixelCount);
            b = Math.floor(b / pixelCount);

            resolve(`rgb(${r}, ${g}, ${b})`);
        };
        img.onerror = function () {
            reject(new Error('No se pudo cargar la imagen.'));
        };
    });
}
function crearInputs(title, artist) {
    let padre = document.getElementById("optionsContainer");
    padre.innerHTML = "";

    let barrasContainer = document.createElement("div");
    barrasContainer.className = "flex-container";
    barrasContainer.id = "posBtns";

    const volumeContainer = document.createElement("div");
    volumeContainer.id = "volumeContainer";
    // creo el input de volumen
    const volumeInput = document.createElement("input");
    volumeInput.type = "range";
    volumeInput.id = "volumen";
    volumeInput.min = "0";
    volumeInput.max = "100";
    volumeInput.step = "0.01";
    volumeContainer.appendChild(volumeInput);
    // la etiqueta del volumen
    const volumeImg = document.createElement("img");
    volumeImg.id = "volumeImg";
    volumeImg.src = "../imgs/alto-volumen.png";
    volumeContainer.appendChild(volumeImg);

    padre.appendChild(volumeContainer);

    //agudos y graves
    const pitchInput = document.createElement("input");
    pitchInput.type = "range";
    pitchInput.id = "pitch";
    pitchInput.min = "0.5";
    pitchInput.max = "1.5";
    pitchInput.step = "0.01";
    pitchInput.value = 1;
    const pitchP = document.createElement("p");
    pitchP.innerText = "pitch";
    //
    const bajoInput = document.createElement("input");
    bajoInput.type = "range";
    bajoInput.id = "bajo";
    bajoInput.min = "-1";
    bajoInput.max = "1";
    bajoInput.step = "0.01";
    bajoInput.value = 0;
    const bajoP = document.createElement("p");
    bajoP.innerText = "bajos";

    const altoInput = document.createElement("input");
    altoInput.type = "range";
    altoInput.id = "alto";
    altoInput.min = "-1";
    altoInput.max = "1";
    altoInput.step = "0.01";
    altoInput.value = 0;
    const altoP = document.createElement("p");
    altoP.innerText = "altos";


    // el titulo
    const titulo = document.createElement("h2");
    titulo.innerText = title;

    const artista = document.createElement("p");
    artista.innerText = artist;

    barrasContainer.appendChild(titulo);
    barrasContainer.appendChild(artista);

    // la posicion
    const secondsInput = document.createElement("input");
    secondsInput.type = "range";
    secondsInput.id = "seconds";
    secondsInput.value = "0";
    secondsInput.min = "0";
    secondsInput.max = "100";
    secondsInput.step = "1";
    barrasContainer.appendChild(secondsInput);
    //

    // opciones de control
    const buttonContainer = document.createElement("div");
    buttonContainer.className = "flex-container";

    // Crear botones
    //atras
    const backButton = document.createElement("img");
    backButton.id = "atras";
    backButton.src = "../imgs/backwards.png";
    backButton.alt = "Atrás";
    buttonContainer.appendChild(backButton);
    //pausa
    const pauseButton = document.createElement("img");
    pauseButton.id = "pausa";
    pauseButton.classList = "pausa";
    pauseButton.alt = "Pausa";
    buttonContainer.appendChild(pauseButton);
    //adelante
    const forwardButton = document.createElement("img");
    forwardButton.id = "adelante";
    forwardButton.src = "../imgs/backwards.png";
    forwardButton.alt = "Adelante";
    buttonContainer.appendChild(forwardButton);
    barrasContainer.appendChild(buttonContainer);
    barrasContainer.appendChild(pitchInput);
    barrasContainer.appendChild(pitchP);
    barrasContainer.appendChild(bajoInput);
    barrasContainer.appendChild(bajoP);
    barrasContainer.appendChild(altoInput);
    barrasContainer.appendChild(altoP);
    padre.appendChild(barrasContainer);
    document.getElementById("volumen").addEventListener("change", () => {

        changeVolume(document.getElementById("volumen").value / 100)
    })
    document.getElementById("seconds").addEventListener("change", () => {
        let multiplier = document.getElementById("seconds").value / 100

        duration = maxDuration * multiplier;
        changeTime(duration);
    })
    document.getElementById("pitch").addEventListener("change", () => {
        changePitch(event.currentTarget.value);
    })
    document.getElementById("bajo").addEventListener("change", () => {
        changeBajos(event.currentTarget.value);
    })
    document.getElementById("alto").addEventListener("change", () => {
        changeAltos(event.currentTarget.value);
    })

    // debo hacer estos eventos para permitir cambiar el tiempo de manera correcta, sino lo cambia a medida que quiero cambiarlo yo 
    document.getElementById("seconds").addEventListener("mousedown", () => { isSecondsActive = true; });
    document.getElementById("seconds").addEventListener("mouseup", () => { isSecondsActive = false; });
    document.getElementById("seconds").addEventListener("mouseleave", () => { isSecondsActive = false; });
    // pasar para atras
    document.getElementById("atras").addEventListener("click", () => {

        currentTrack--;
        if (currentTrack < 0) {
            currentTrack = playList.length - 1;
        }/* 
        console.log(currentTrack); */
        cambiarPista(currentTrack);
    }, false);
    // pasar para adelante
    document.getElementById("adelante").addEventListener("click", () => {
        currentTrack++;
        if (currentTrack >= playList.length) {
            currentTrack = 0;
        }
        cambiarPista(currentTrack);
    }, false);
    //pausar/reanudar
    document.getElementById("pausa").addEventListener("click", () => {/* 
        console.log(document.getElementById("pausa").src); */
        if (document.getElementById("pausa").classList.contains("pausa")) {
            document.getElementById("imagen").style.animationPlayState = "paused";
            document.getElementById("pausa").classList.remove("pausa");
            document.getElementById("pausa").classList.add("reanudar");
            howler.pause();

        } else {
            document.getElementById("imagen").style.animationPlayState = "running";
            document.getElementById("pausa").classList.remove("reanudar");
            document.getElementById("pausa").classList.add("pausa");
            howler.play();
        }
    }, false)
}

async function cambiarPista(number) {
    howler.stop();
    /* 
        let response = await fetch('../jsons/songsData.json'); */

    let song = playList[number]

    ponerFoto(song["image"]);

    loadSongs(song["src"], song["image"]);
    // crea los inputs
    crearInputs(song["title"], song["artist"]);
    howler.play();



}

//mezcla las canciones
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]]; // Intercambia los elementos
        if (i == currentTrack) {
            currentTrack = j;
        } else if (j == currentTrack) {
            currentTrack = i;
        }
    }
    return array;
}
// cambio entre radio y canciones


//mostrar
function mostrar(elemento) {
    elemento.classList.remove("oculto");
    if (!elemento.classList.contains("mostradoFlex")) {
        elemento.classList.add("mostradoFlex");
    }
}
function mostrarGrid(elemento) {
    elemento.classList.remove("oculto");
    if (!elemento.classList.contains("mostradoGrid")) {
        elemento.classList.add("mostradoGrid");
    }
}

//ocultar
function ocultar(elemento) {
    elemento.classList.remove("mostradoFlex");
    elemento.classList.remove("mostradoGrid");
    if (!elemento.classList.contains("oculto")) {
        elemento.classList.add("oculto");
    }
}

//control de volumen

function changeVolume(params) {
    songVolume = params;
    if (howler) {
        howler.volume(songVolume);
    }
}
// control de duracion

function changeTime(duration) {
    if (howler) {
        howler.seek(duration);
    }
}
function changePitch(rate) {
    const imagen = document.getElementById("imagen");
    let velocidad = 10 / rate;
    console.log(velocidad);
    /*     document.getElementById("imagen").style.animationPlayState = "paused";
        document.getElementById("imagen").style.animationDuration = velocidad+"s";
        document.getElementById("imagen").style.animationPlayState = "running"; */
    howler.rate(rate);

}
function changeBajos(bajo) {
    let bassValue = parseFloat(bajo);
    bassFilter.gain.value = bassValue * 20; // cambia -20 +20 db las frecuencias bajas
    console.log(bassFilter);
}
function changeAltos(alto) {
    let trebleValue = parseFloat(alto);
    trebleFilter.gain.value = trebleValue * 20;
}


// On Load
/* loadSongs(); */
enseñarCanciones(animo);
