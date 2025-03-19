<?php
/* ==================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This file contains the string labels for italian language
===================================================================*/
function lng($key){
    switch ($key){
        case "":return "";
        case "dashboard": return "Dashboard";
        case "user":return "Usuario";
        case "password":return "Contrase&ntilde;a";
        case "login":return "Iniciar sesi&oacute;n";
        case "send":return "Enviar";
        case "register": return "Registrar";
        case "giorno": return "D&iacute;a";
        case "notte": return "Noche";
        case "sera": return "Tarde";
        case "users": return "Usuarios";
        case "call_log": return "Registro de llamadas";
        case "preview":return "Vista previa";
        case "username": return "Nombre de usuario";
        case "daypart": return "Parte del d&iacute;a";
        case "download-data":return "Descargar datos";
        case "per_fasce_orarie": return "Por franjas horarias";
        case "device": return "Dispositivo";
        case "source": return "Fuente";
        case "city": return "Ciudad";
        case "referer": return "Referer";
        case "repeat_password":return "Repite la contrase&ntilde;a";
        case "email":return "Direcci&oacute;n de correo electr&oacute;nico";
        case "user_registration":return "Registro de usuario";
        case "verify":return "Verificar";
        case "update": return "Actualizar";
        case "autentication":return "Autenticaci&oacute;n";
        case "forgot_pass": return "Olvid&eacute; mi contrase&ntilde;a";
        case "invalid_uid_or_pass":return "Usuario o contrase&ntilde;a no v&aacute;lidos";
        case "api_loop": return "No es posible crear un bucle para acortar el enlace ";
        case "api_invalid-short": return "Este SHORT_ID no es v&aacute;lido.";
        case "front_insert-long": return "Inserta aqu&iacute; el enlace largo...";
        case "front_shorten":  return "Acortar enlace";
        case "change_pass_form": return "Cambiar contrase&ntilde;a";
        case "front_information":
        case "information": return "Informaci&oacute;n";
        case "language": return "Idioma";
        case "unavailable_data": return "Informaci&oacute;n no disponible";
        case "front_reduced-link": return "Enlace reducido";
        case "front_copied-link": return "Enlace copiado";
        case "new apikey":  return "Nueva clave API";
        case "change password": return "Cambiar contrase&ntilde;a";
        case "front_link-to-shrink": return "Enlace a acortar";
        case "front_insert-correct":  return "Inserta un enlace correcto antes de presionar &quot;<strong>".lng("front_shorten")."</strong>&quot;";
        case "link_limit-reached": return "Has alcanzado el n&uacute;mero m&aacute;ximo de enlaces que se pueden crear. Para crear un nuevo enlace, debes eliminar uno existente.";
        case "error":  return "Error";
        case "front_copy-error": return "Error al copiar";
        case "front_link-created-on": return "Ha sido creado el";
        case "front_short-link-is": return "El enlace corto es";
        case "front_access-data": return "Datos de acceso";
        case "front_title-detail-data": return "{{clicks}} clics de {{unique}} usuarios &uacute;nicos";
        case "not-found": return "no encontrado";
        case "front_instr-small": return "Introduce el enlace acortado y pulsa el bot&oacute;n &quot;<strong>Informaci&oacute;n</strong>&quot; para obtener datos del enlace.";
        case "front_incorrect-link": return "<strong>uri</strong> no es correcto o es un loop-<strong>uri</strong> (no es posible ni recomendable acortar un enlace de <strong>".getenv("URI")."</strong>)";
        case "date": return "Fecha";
        case "copy":  return "Copiar";
        case "close": return "Cerrar";
        case "times": return "Veces";
        case "change_link_code": return "Cambiar el c&oacute;digo del enlace:";
        case "change": return "Cambiar";
        case "code_exists": return "Este c&oacute;digo ya existe.";
        case "database_generic_error": return "Error durante el registro en la base de datos";
        case "front_was-req": return "Y ha sido solicitado";
        case "geoloc": return "geolocalizaci&oacute;n";
        case "front_link-is": return "El enlace original es";
        case "delete-element": return "Elimino el enlace acortado:<br><strong>{{code}}</strong><br>que tiene el siguiente URI:<br><strong>{{uri}}</strong>";
        case "front_instructions": return '<p><strong>Este es un sitio para la creaci&oacute;n de enlaces cortos.</strong></p>
                <p>Significa que me pasas un enlace largo y yo te devuelvo un enlace corto que puede reemplazar al enlace original.</p>
                <h3>&iquest;C&oacute;mo funciona?</h3>
                <p class="pad">Para crear un enlace corto, basta con introducir el enlace largo en el campo de texto y pulsar "<strong>'.lng("front_shorten").'</strong>". La versi&oacute;n acortada se mostrar&aacute; en la caja emergente.<br>
                Para utilizar el enlace corto, simplemente c&oacute;pialo y p&eacute;galo en el navegador, y el usuario ser&aacute; redirigido autom&aacute;ticamente al enlace original.<br>
                Para ver las estad&iacute;sticas, pulsa el bot&oacute;n "<strong>'.lng("front_information").'</strong>".</p>
                <h3>Ejemplo</h3>
                <p class="pad">Si quieres crear un enlace corto para <a href="https://www.google.com/search?client=firefox-b-d&q=como+se+acortan+los+links%3F">https://www.google.com/search?client=firefox-b-d&q=como+se+acortan+los+links%3F</a>,
                introd&uacute;celo en el campo de texto y pulsa "<strong>'.lng("front_shorten").'</strong>". Cuando obtengas el enlace corto, util&iacute;zalo directamente en el navegador para ver el resultado.</p>';
        case "site_index":
            return '
            <header class="bigtitle">
                <h1>&iexcl;Acorta, Comparte, Monitorea!</h1>
                <p class="bigsubtitle">Un proyecto open-source para la gesti&oacute;n de enlaces</p>
            </header>
            <div class="container">
                <main>
                    <h2>&iquest;Por qu&eacute; es &uacute;til y por qu&eacute; muchos usan acortar enlaces?</h2>
                    <p>Un enlace m&aacute;s corto es m&aacute;s pr&aacute;ctico de compartir y visualmente m&aacute;s limpio. Muchos servicios como redes sociales y apps de mensajer&iacute;a limitan el n&uacute;mero de caracteres, as&iacute; que un URL largo podr&iacute;a ser engorroso y poco legible.</p>
                    <p>Otra ventaja es la gesti&oacute;n din&aacute;mica del enlace: con un servicio de acortamiento, puedes modificar la destinaci&oacute;n de tu enlace incluso despu&eacute;s de haberlo compartido, sin necesidad de actualizar todos los lugares donde se public&oacute;.</p>
                    <p>Por &uacute;ltimo, puedes monitorear los clics efectuados, analizar el tr&aacute;fico, descubrir de d&oacute;nde provienen los usuarios y optimizar tu estrategia de compartici&oacute;n.</p>
                    <p>Nuestro servicio no se limita al uso manual: gracias a las API abiertas, los desarrolladores y las empresas pueden integrar la generaci&oacute;n y administraci&oacute;n de enlaces en sus propios sistemas.</p>
                    <div class="form-group center-content">
                        <a href="/_pls_fnc_login" class="btn btn-primary">Iniciar sesi&oacute;n</a>
                        <a href="/_pls_fnc_register" class="btn btn-secondary">Registrar</a>
                    </div>
                    <h2>&iquest;C&oacute;mo funciona?</h2>
                    <div style="padding-left:30px;">
                        <h3>V&iacute;a web</h3>
                        <ul class="list">
                            <li>🔒 Para utilizar nuestro servicio de enlaces acortados, debes ser un usuario registrado.</li>
                            <li>1️⃣ En tu p&aacute;gina principal, <strong>pega</strong> tu enlace largo en la caja superior.</li>
                            <li>2️⃣ <strong>Haz clic en &quot;Acortar enlace&quot;</strong> y obtendr&aacute;s una URL corta y un c&oacute;digo QR correspondiente.<div style="padding-left:20px">- Se generar&aacute; un enlace corto aleatorio, pero <strong>puedes cambiarlo</strong> usando uno m&aacute;s adecuado.</div></li>
                            <li>3️⃣ <strong>Comp&aacute;rtelo</strong> en redes, email o mensajes.</li>
                            <li>4️⃣ <strong>Monitorea</strong> las visitas con estad&iacute;sticas avanzadas que incluyen fecha, hora y ubicaci&oacute;n geogr&aacute;fica del usuario que hace clic.</li>
                            <li>💡 <strong>Soporta c&oacute;digo QR</strong> para compartir de forma inmediata.</li>
                        </ul>
                        <h3>V&iacute;a API</h3>
                        <div style="padding-left:30px;">
                            💻 <strong>Funciona v&iacute;a API</strong>: integra nuestro servicio en tus proyectos usando nuestras API potentes y flexibles.
                            <br>Ver: ><a class="nav-item" style="color:#A33" href="/pls_swagu" target="_blank">OpenAPI doc</a> - o: ><a class="nav-item" style="color:#A33" href="/pls_redoc" target="_blank">Redoc API doc</a>
                        </div>
                    </div>
                </main>
                <section>
                    <h2>&iquest;Por qu&eacute; elegir este proyecto?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source y Gratuito</strong> - El c&oacute;digo est&aacute; disponible para todos los usuarios.&nbsp;&gt;<a class="nav-item" style="color:#A33" href="/pls_about" target="_blank">GitHub</a></li>
                        <li>🔍 <strong>Transparente y Seguro</strong> - Sin seguimiento oculto ni pr&aacute;cticas invasivas, escrito de manera creativa y diferente de lo habitual para reducir experiencias de hacking.</li>
                        <li>🛠 <strong>Personalizable</strong> - Modificable para adaptarse a tus necesidades.</li>
                        <li>👥 <strong>Soportado por la Comunidad</strong> - Recibe soporte y contribuye con mejoras.</li>
                        <li>📡 <strong>API Abiertas</strong> - Ideal para desarrolladores y empresas.</li>
                        <li>🚀 <strong>Independiente</strong> - Sin publicidad ni influencias corporativas, solo tecnolog&iacute;a abierta.</li>
                        <li>🌍 <strong>Proyecto Europeo</strong> - Disponible en cuatro idiomas principales: Ingl&eacute;s, Italiano, Franc&eacute;s y Alem&aacute;n. Puedes cambiar f&aacute;cilmente el idioma en la parte superior derecha.</li>
                    </ul>
                </section>
                <section><br>&nbsp;
                    <h2>Preguntas Frecuentes</h2>
                    <div class="accordion">
                        <div class="tab">
                            <input type="checkbox" id="faq1">
                            <label class="tab__label" for="faq1">&iquest;Por qu&eacute; deber&iacute;a acortar un enlace?</label>
                            <div class="tab__content">
                                <p>Acortar enlaces los hace m&aacute;s f&aacute;ciles de compartir, mejora la legibilidad y permite monitorear sus resultados, ayud&aacute;ndote a saber de d&oacute;nde llega tu tr&aacute;fico.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq2">
                            <label class="tab__label" for="faq2">&iquest;Necesito una cuenta para usar el servicio?</label>
                            <div class="tab__content">
                                <p>S&iacute;, debes ser un usuario registrado para crear enlaces cortos. Esto garantiza privacidad, seguridad y acceso <strong>reservado</strong> a tus estad&iacute;sticas.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq3">
                            <label class="tab__label" for="faq3">&iquest;Mis enlaces caducan?</label>
                            <div class="tab__content">
                                <p>Los enlaces permanecen activos indefinidamente a menos que decidas eliminarlos en tu configuraci&oacute;n de usuario, d&aacute;ndote control total sobre su duraci&oacute;n.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq4">
                            <label class="tab__label" for="faq4">&iquest;Cu&aacute;nto cuesta el servicio?</label>
                            <div class="tab__content">
                                <p>Este proyecto es de c&oacute;digo abierto. Puedes descargarlo desde GitHub y usarlo. Si deseas usar las funcionalidades sin instalarlo en tu servidor, sigue las indicaciones de quien lo pone a disposici&oacute;n.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        ';
        case "0select": return "Selecciona ";
        case "0star": return "la estrella";
        case "0house": return "la casita";
        case "0computer": return "la pantalla de computadora";
        case "0car": return "el auto";
        case "0robot":  return "la cabeza del robot";
        case "0cloud":  return "la nube";
        case "0lock": return "el candado";
        case "0rocket": return "el cohete";
        case "0heart":  return "el coraz&oacute;n";
        case "0tree":  return "el &aacute;rbol";
        case "0plane":  return "el avi&oacute;n";
        case "0envelope": return "el sobre";
        case "0eye": return "el ojo";
        case "0nouid":return "El valor user-id es obligatorio.";
        case "0noemail":  return "El e-mail es obligatorio.";
        case "0nopass":  return "Debes insertar una contrase&ntilde;a.";
        case "0nospam": return "Debes seleccionar el icono antispam correcto.";
        case "0invemail": return "Ingresa una direcci&oacute;n de email v&aacute;lida.";
        case "0smallpass": return "La contrase&ntilde;a debe tener al menos 8 caracteres.";
        case "0diffpass":  return "Las contrase&ntilde;as no coinciden.";
        case "1diffpass":  return "contrase&ntilde;as DISTINTAS.";
        case "0poor": return "Insegura";
        case "0mean": return "Media";
        case "0strong": return "Segura";
        case "0regok": return "<h2>Registro completado con &eacute;xito</h2><p>Hemos enviado un email de verificaci&oacute;n a {{email}}. Revisa tu bandeja de entrada y sigue las instrucciones para completar el registro.</p>";
        case "0uexist": return "<h2>Usuario ya existente</h2><p>Hola, ya existe un usuario con este correo electr&oacute;nico. &iquest;Olvidaste <a href='/_pls_fnc_fgtpass'>la contrase&ntilde;a</a>?</p>";
        case "email_not_verified": return "<h2>Atenci&oacute;n</h2><p>Su direcci&oacute;n de correo electr&oacute;nico no ha sido verificada.<br>Por favor, revise su bandeja de entrada y siga las instrucciones para completar el registro, gracias.</p>";
        case "email_verified": return "<h2>Correo electr&oacute;nico verificado</h2><p>Su direcci&oacute;n de correo electr&oacute;nico ha sido verificada con &eacute;xito. Ahora puede iniciar sesi&oacute;n.</p>";
        case "email_needed": return "Para poder solicitar el cambio de la contrase&ntilde;a, debes introducir tu direcci&oacute;n de correo electr&oacute;nico.";
        case "subjchangepass":return "Cambio de contrase&ntilde;a";
        case "subjverifyemail": return "Verifica tu direcci&oacute;n de correo";
        case "change_pass_msg": return "<h2>Atenci&oacute;n</h2><p>Has solicitado el cambio de la contrase&ntilde;a; si tu direcci&oacute;n de correo electr&oacute;nico coincide con un usuario registrado, recibir&aacute;s en breve un mensaje en la direcci&oacute;n <strong>{{email}}</strong> con las instrucciones necesarias para cambiar tu contrase&ntilde;a.</p>";
        case "chngpass-email-body": return "<h1>Changement de mot de passe</h1><p>Ch&egrave;re {{username}},<br>Une demande de changement de mot de passe a &eacute;t&eacute; effectu&eacute;e pour votre compte.<br>Si cette demande vous concerne, cliquez sur le lien suivant :</p>{{link}}<p>Si vous n&apos;avez pas effectu&eacute; cette demande, veuillez ignorer simplement cet e-mail.</p>";
        case "check-email-body": return "<h1>Verifica tu correo electr&oacute;nico</h1><p>Haz clic en el siguiente enlace para verificar tu direcci&oacute;n de correo:</p>{{link}}<p>Si no solicitaste el registro, ignora este correo.</p>";
        default: return "valor de etiqueta $key desconocido...";
    }
}

function getLangDate($theDate){
    $date = new DateTime($theDate);
    $locale = 'es_ES';
    $formatter = new \IntlDateFormatter(
        $locale,                         // Locale
        \IntlDateFormatter::FULL,         // Tipo di formattazione della data
        \IntlDateFormatter::FULL,         // Tipo di formattazione dell'ora
        'Europe/Rome',                   // Fuso orario
        \IntlDateFormatter::GREGORIAN,    // Calendario
        "EEEE dd MMMM yyyy ! HH:mm:ss"      // Pattern di formattazione
    );
    return str_replace("!","alle",$formatter->format($date));
}