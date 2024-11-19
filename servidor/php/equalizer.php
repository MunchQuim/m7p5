<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../cliente/howler.css">
    <!-- <script src="backColorScript.js" defer></script> -->
    <script src="../js/equilizer.js" defer></script>
    <!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/howler@2.2.4/dist/howler.min.js"></script>
</head>

<body>
    <h1 id="title">Howler.js</h1>
    <main>
        <div id="songContainer">
            <div id="imgCanContainer">
                <div id="imgContainer">
                </div>
                <div id="canvasContainer">
                    <canvas id="equalizer" width="600" height="600"></canvas>
                </div>
            </div>

            <div id="optionsContainer" class="mostrado">
                <!--                 <input type="range" id="volumen">
                <input type="range" id="seconds" value=0> -->
            </div>
        </div>
        <div id="side">
            <div id="sideHeader">
                <button id="playlistBtn">Playlist</button>
                <button id="radioBtn">Radios</button>
            </div>
            <div id="sideMain">
                <div id="songList" class="mostrado"></div>
                <div id="radioList" class="oculto"></div>
            </div>
            <div id="sideFooter"></div>
        </div>
    </main>
    <div id="social-media">
        <a href="https://www.linkedin.com/in/joaquimpineda" target="_blank"><img src="../imgs//linkedin.png"
                alt="linkedin"></a>
        <a href="https://github.com/MunchQuim?tab=repositories" target="_blank"><img src="../imgs//github.png"
                alt="linkedin"></a>
    </div>

</body>

</html>