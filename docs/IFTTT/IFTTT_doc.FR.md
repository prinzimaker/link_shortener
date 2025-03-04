# 📌 Intégration de PLS (*Prinzimaker's Link Shortener*) avec IFTTT

Ce guide explique comment configurer **PLS** - ***Prinzimaker's Link Shortener*** pour fonctionner avec **IFTTT**, permettant de recevoir des notifications automatiques chaque fois qu'un lien raccourci est cliqué.

## 📌 Prérequis
- Un compte sur [IFTTT](https://ifttt.com/)
- Une clé API **PLS** (disponible dans le panneau utilisateur)
- Un Webhook configuré sur IFTTT

---

## 🔹 1. Création d'un Webhook sur IFTTT
1️⃣ Accédez à [IFTTT Webhooks](https://ifttt.com/maker_webhooks) \
2️⃣ Cliquez sur **"Create"** pour créer un nouvel Applet \
3️⃣ Choisissez **"If This"** et sélectionnez **Webhooks** \
4️⃣ Choisissez **"Receive a web request"** \
5️⃣ Entrez le nom de l'événement : `PLS_click` \
6️⃣ Cliquez sur **"Create trigger"**

---

## 🔹 2. Configuration de l'Action sur IFTTT
1️⃣ Après avoir créé le déclencheur, cliquez sur **"Then That"** \
2️⃣ Choisissez un service pour recevoir la notification :
   - **Google Sheets** → Enregistre chaque clic dans une feuille de calcul
   - **Slack** → Notifie chaque nouveau clic
   - **E-mail** → Envoie un e-mail avec les détails du clic
   - **Telegram** → Reçoit un message privé 

3️⃣ Configurez le service avec les données reçues du Webhook \
4️⃣ Cliquez sur **"Create action"** pour enregistrer

---

## 🔹 3. Obtention de la clé API IFTTT
1️⃣ Accédez à votre profil [IFTTT Webhooks](https://ifttt.com/maker_webhooks) - ou consultez la [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ) \
2️⃣ Cliquez sur **"Create"** \
3️⃣ Copiez la clé API affichée dans l'URL :
   ```
   https://maker.ifttt.com/trigger/{event}/json/with/key/VOTRE_CLE_IFTTT
   ```
4️⃣ Enregistrez la clé `VOTRE_CLE_IFTTT` dans le fichier .env du projet :
```.env
IFTTT_KEY="cuZbar01-eC4tc0X7HzHTy"
```

---

## 🔹 4. Configuration de *Prinzimaker's Link Shortener* pour envoyer des données à IFTTT

Le logiciel est déjà configuré pour envoyer des notifications à IFTTT lorsqu'un lien est cliqué. Si une valeur est spécifiée dans le fichier .env sous le paramètre IFTTT_KEY, pour chaque clic, ***PLS*** enverra automatiquement les données suivantes à IFTTT :
   ```json
    value1:"example.com",
    value2:"shorturlkey",
    value3:{
       "click_date": "2024-03-02 14:30:00",
       "short_uri": "example.com/check-your-url-fb",
       "complete_uri": "https://www.longurl-example.com/long-page-link?utm_social=fb",
       "geoinfo":"États-Unis, New York, Manhattan Soho, 123R456",
       "referer":"https://www.facebook.com/yourpage",
       "agent":"Mozilla 4.1, Windows 11"
    }
   ```

---

## 🔹 5. Tester l'intégration
1️⃣ Ouvrez un navigateur et visitez un lien raccourci existant, par exemple :
   ```
   https://example.com/check-your-url-fb
   ```
2️⃣ Vérifiez dans IFTTT si l'événement a été reçu et l'action exécutée \
3️⃣ Si vous utilisez **Google Sheets**, assurez-vous que le nouveau clic a été enregistré

---

## 🎯 Résultat final
✅ **Chaque clic sur un lien raccourci sera automatiquement notifié à IFTTT** 🚀 \
✅ **Les données seront envoyées à Google Sheets, Slack, Gmail ou tout autre service IFTTT** 🔥