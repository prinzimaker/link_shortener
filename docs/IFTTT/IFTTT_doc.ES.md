# 📌 Integración de PLS (*Prinzimaker's Link Shortener*) con IFTTT

Esta guía explica cómo configurar **PLS** - ***Prinzimaker's Link Shortener*** para que funcione con **IFTTT**, permitiendo recibir notificaciones automáticas cada vez que se hace clic en un enlace acortado.

## 📌 Requisitos
- Una cuenta en [IFTTT](https://ifttt.com/)
- Una clave API de **PLS** (disponible en el panel de usuario)
- Un Webhook configurado en IFTTT

---

## 🔹 1. Crear un Webhook en IFTTT
1️⃣ Accede a [IFTTT Webhooks](https://ifttt.com/maker_webhooks) \
2️⃣ Haz clic en **"Create"** para crear un nuevo Applet \
3️⃣ Elige **"If This"** y selecciona **Webhooks** \
4️⃣ Selecciona **"Receive a web request"** \
5️⃣ Ingresa el nombre del evento: `PLS_click` \
6️⃣ Haz clic en **"Create trigger"**

---

## 🔹 2. Configurar la acción en IFTTT
1️⃣ Después de crear el trigger, haz clic en **"Then That"** \
2️⃣ Elige un servicio donde recibir la notificación:
   - **Google Sheets** → Registra cada clic en una hoja de cálculo
   - **Slack** → Notifica cada nuevo clic
   - **Correo electrónico** → Envía un email con los detalles del clic
   - **Telegram** → Recibe un mensaje privado

3️⃣ Configura el servicio con los datos recibidos del Webhook \
4️⃣ Haz clic en **"Create action"** para guardar

---

## 🔹 3. Obtener la clave API de IFTTT
1️⃣ Ve a tu perfil en [IFTTT Webhooks](https://ifttt.com/maker_webhooks) - o consulta las [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ) \
2️⃣ Usa **"Create"** \
3️⃣ Copia la clave API que aparece en la URL:
   ```
   https://maker.ifttt.com/trigger/{event}/json/with/key/YOUR_IFTTT_KEY
   ```
4️⃣ Guarda la clave `YOUR_IFTTT_KEY` en el archivo .env del proyecto:
```.env
IFTTT_KEY="cuZbar01-eC4tc0X7HzHTy"
```

---

## 🔹 4. Configurar *Prinzimaker's Link Shortener* para enviar datos a IFTTT

El software ya está preparado para enviar notificaciones a IFTTT cuando se hace clic en un enlace. Si se indica un valor en el archivo .env en el parámetro IFTTT_KEY, por cada clic ***PLS*** enviará automáticamente los siguientes datos a IFTTT:
   ```json
   value1:"example.com",
   value2:"shorturlkey",
   value3:{
       "click_date": "2024-03-02 14:30:00",
       "short_uri": "example.com/check-your-url-fb",
       "complete_uri": "https://www.longurl-example.com/long-page-link?utm_social=fb",
       "geoinfo":"United States, New York, Manhattan soho, 123R456",
       "referer":"https://www.facebook.com/yourpage",
       "agent":"Mozilla 4.1, windows 11",
   },
   ```

---

## 🔹 5. Probar la integración
1️⃣ Abre un navegador y visita un enlace acortado existente, por ejemplo:
   ```
   https://example.com/check-your-url-fb
   ```
2️⃣ Verifica en IFTTT si el evento ha sido recibido y la acción se ha ejecutado \
3️⃣ Si usas **Google Sheets**, comprueba que el nuevo clic haya sido registrado

---

## 🎯 Resultado final
✅ **Cada clic en un enlace acortado será notificado automáticamente a IFTTT** 🚀 \
✅ **Los datos serán enviados a Google Sheets, Slack, Gmail o cualquier otro servicio de IFTTT** 🔥
