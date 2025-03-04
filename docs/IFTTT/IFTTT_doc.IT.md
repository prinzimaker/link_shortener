# 📌 Integrazione PLS (*Prinzimaker's Link Shortener*) con IFTTT

Questa guida spiega come configurare **PLS** - ***Prinzimaker's Link Shortener*** per funzionare con **IFTTT**, in modo da ottenere notifiche automatiche ogni volta che un link accorciato viene cliccato.

## 📌 Requisiti
- Un account su [IFTTT](https://ifttt.com/)
- Un'API Key di **PLS** (disponibile nel pannello utente)
- Un Webhook configurato su IFTTT

---

## 🔹 1. Creare un Webhook su IFTTT
1️⃣ Accedi a [IFTTT Webhooks](https://ifttt.com/maker_webhooks) \
2️⃣ Clicca su **"Create"** per creare un nuovo Applet \
3️⃣ Scegli **"If This"** e seleziona **Webhooks** \
4️⃣ Scegli **"Receive a web request"** \
5️⃣ Inserisci il nome dell'evento: `PLS_click` \
6️⃣ Clicca su **"Create trigger"** 

---

## 🔹 2. Configurare l'azione su IFTTT
1️⃣ Dopo aver creato il trigger, clicca su **"Then That"** \
2️⃣ Scegli un servizio in cui ricevere la notifica:
   - **Google Sheets** → Registra ogni clic su un foglio di calcolo
   - **Slack** → Notifica ogni nuovo clic
   - **E-mail** → Invia un'email con i dettagli del clic
   - **Telegram** → Ricevi un messaggio privato 

3️⃣ Configura il servizio con i dati ricevuti dal Webhook \
4️⃣ Clicca su **"Create action"** per salvare

---

## 🔹 3. Ottenere la Chiave API di IFTTT
1️⃣ Vai sul tuo profilo [IFTTT Webhooks](https://ifttt.com/maker_webhooks) - oppure leggi le [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ) \
2️⃣ Usa **"Create"** \
3️⃣ Copia la chiave API che appare nell'URL:
   ```
   https://maker.ifttt.com/trigger/{event}/json/with/key/YOUR_IFTTT_KEY
   ```
4️⃣ Salva la chiave `YOUR_IFTTT_KEY`, nel file .env del progetto:
```.env
IFTTT_KEY="cuZbar01-eC4tc0X7HzHTy"
```

---

## 🔹 4. Configurare *Prinzimaker's Link Shortener* per inviare dati a IFTTT

Il software è già predisposto per inviare notifiche a IFTTT quando un link viene cliccato. Se un valore è indicato nel file .env al parametro IFTTT_KEY, per ogni click ***PLS*** invierà automaticamente i seguenti dati a IFTTT:
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

## 🔹 5. Testare l'Integrazione
1️⃣ Apri un browser e visita un link accorciato esistente, es:
   ```
   https://example.com/check-your-url-fb
   ```
2️⃣ Controlla in IFTTT se l'evento è stato ricevuto e l'azione è stata eseguita \
3️⃣ Se usi **Google Sheets**, verifica che il nuovo clic sia stato registrato

---

## 🎯 Risultato Finale
✅ **Ogni clic su un link accorciato sarà notificato automaticamente a IFTTT** 🚀 \
✅ **I dati saranno inviati a Google Sheets, Slack, Gmail o qualsiasi altro servizio IFTTT** 🔥
