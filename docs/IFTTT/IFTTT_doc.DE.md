# 📌 Integration von PLS (*Prinzimaker's Link Shortener*) mit IFTTT

Diese Anleitung erklärt, wie **PLS** - ***Prinzimaker's Link Shortener*** mit **IFTTT** konfiguriert wird, um automatische Benachrichtigungen zu erhalten, wenn ein verkürzter Link geklickt wird.

## 📌 Voraussetzungen
- Ein Konto bei [IFTTT](https://ifttt.com/)
- Ein **PLS** API-Schlüssel (verfügbar im Benutzerpanel)
- Ein Webhook, der in IFTTT konfiguriert ist

---

## 🔹 1. Erstellen eines Webhooks in IFTTT
1️⃣ Gehe zu [IFTTT Webhooks](https://ifttt.com/maker_webhooks) \
2️⃣ Klicke auf **"Create"**, um ein neues Applet zu erstellen \
3️⃣ Wähle **"If This"** und dann **Webhooks** \
4️⃣ Wähle **"Receive a web request"** \
5️⃣ Gib den Ereignisnamen ein: `PLS_click` \
6️⃣ Klicke auf **"Create trigger"**

---

## 🔹 2. Konfiguration der Aktion in IFTTT
1️⃣ Nachdem der Trigger erstellt wurde, klicke auf **"Then That"** \
2️⃣ Wähle einen Dienst, um die Benachrichtigung zu erhalten:
   - **Google Sheets** → Protokolliert jeden Klick in einer Tabelle
   - **Slack** → Sendet eine Benachrichtigung für jeden neuen Klick
   - **E-Mail** → Sendet eine E-Mail mit den Klickdetails
   - **Telegram** → Erhält eine private Nachricht 

3️⃣ Konfiguriere den Dienst mit den vom Webhook empfangenen Daten \
4️⃣ Klicke auf **"Create action"**, um zu speichern

---

## 🔹 3. Abrufen des IFTTT API-Schlüssels
1️⃣ Gehe zu deinem [IFTTT Webhooks](https://ifttt.com/maker_webhooks) Profil - oder lies die [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ) \
2️⃣ Klicke auf **"Create"** \
3️⃣ Kopiere den API-Schlüssel aus der URL:
   ```
   https://maker.ifttt.com/trigger/{event}/json/with/key/DEIN_IFTTT_KEY
   ```
4️⃣ Speichere den Schlüssel `DEIN_IFTTT_KEY` in der .env-Datei des Projekts:
```.env
IFTTT_KEY="cuZbar01-eC4tc0X7HzHTy"
```

---

## 🔹 4. Konfiguration von *Prinzimaker's Link Shortener* zur Datenübermittlung an IFTTT

Die Software ist bereits so konfiguriert, dass sie Benachrichtigungen an IFTTT sendet, wenn ein Link geklickt wird. Falls in der .env-Datei unter dem Parameter IFTTT_KEY ein Wert angegeben ist, sendet ***PLS*** automatisch die folgenden Daten an IFTTT:
   ```json
    value1:"example.com",
    value2:"shorturlkey",
    value3:{
       "click_date": "2024-03-02 14:30:00",
       "short_uri": "example.com/check-your-url-fb",
       "complete_uri": "https://www.longurl-example.com/long-page-link?utm_social=fb",
       "geoinfo":"Vereinigte Staaten, New York, Manhattan Soho, 123R456",
       "referer":"https://www.facebook.com/yourpage",
       "agent":"Mozilla 4.1, Windows 11"
    }
   ```

---

## 🔹 5. Testen der Integration
1️⃣ Öffne einen Browser und besuche einen bestehenden verkürzten Link, z. B.:
   ```
   https://example.com/check-your-url-fb
   ```
2️⃣ Überprüfe in IFTTT, ob das Ereignis empfangen und die Aktion ausgeführt wurde \
3️⃣ Falls du **Google Sheets** verwendest, stelle sicher, dass der neue Klick protokolliert wurde

---

## 🎯 Endergebnis
✅ **Jeder Klick auf einen verkürzten Link wird automatisch an IFTTT gemeldet** 🚀 \
✅ **Daten werden an Google Sheets, Slack, Gmail oder jeden anderen IFTTT-Dienst gesendet** 🔥