# 📌 PLS (*Prinzimaker's Link Shortener*) Integration with IFTTT

This guide explains how to configure **PLS** - ***Prinzimaker's Link Shortener*** to work with **IFTTT**, allowing you to receive automatic notifications whenever a shortened link is clicked.

## 📌 Requirements
- An account on [IFTTT](https://ifttt.com/)
- A **PLS** API Key (available in the user panel)
- A Webhook configured on IFTTT

---

## 🔹 1. Creating a Webhook on IFTTT
1️⃣ Go to [IFTTT Webhooks](https://ifttt.com/maker_webhooks) \
2️⃣ Click **"Create"** to create a new Applet \
3️⃣ Choose **"If This"** and select **Webhooks** \
4️⃣ Choose **"Receive a web request"** \
5️⃣ Enter the event name: `PLS_click` \
6️⃣ Click **"Create trigger"**

---

## 🔹 2. Configuring the Action on IFTTT
1️⃣ After creating the trigger, click **"Then That"** \
2️⃣ Choose a service to receive the notification:
   - **Google Sheets** → Log each click in a spreadsheet
   - **Slack** → Notify every new click
   - **E-mail** → Send an email with the click details
   - **Telegram** → Receive a private message 

3️⃣ Configure the service with the data received from the Webhook \
4️⃣ Click **"Create action"** to save

---

## 🔹 3. Getting the IFTTT API Key
1️⃣ Go to your [IFTTT Webhooks](https://ifttt.com/maker_webhooks) profile - or read the [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ) \
2️⃣ Click **"Create"** \
3️⃣ Copy the API key shown in the URL:
   ```
   https://maker.ifttt.com/trigger/{event}/json/with/key/YOUR_IFTTT_KEY
   ```
4️⃣ Save the key `YOUR_IFTTT_KEY` in the project's .env file:
```.env
IFTTT_KEY="cuZbar01-eC4tc0X7HzHTy"
```

---

## 🔹 4. Configuring *Prinzimaker's Link Shortener* to Send Data to IFTTT

The software is already set up to send notifications to IFTTT when a link is clicked. If a value is specified in the .env file under the IFTTT_KEY parameter, for each click ***PLS*** will automatically send the following data to IFTTT:
   ```json
    value1:"example.com",
    value2:"shorturlkey",
    value3:{
       "click_date": "2024-03-02 14:30:00",
       "short_uri": "example.com/check-your-url-fb",
       "complete_uri": "https://www.longurl-example.com/long-page-link?utm_social=fb",
       "geoinfo":"United States, New York, Manhattan soho, 123R456",
       "referer":"https://www.facebook.com/yourpage",
       "agent":"Mozilla 4.1, windows 11"
    }
   ```

---

## 🔹 5. Testing the Integration
1️⃣ Open a browser and visit an existing shortened link, e.g.:
   ```
   https://example.com/check-your-url-fb
   ```
2️⃣ Check in IFTTT if the event was received and the action executed \
3️⃣ If using **Google Sheets**, verify that the new click was logged

---

## 🎯 Final Result
✅ **Every click on a shortened link will be automatically notified to IFTTT** 🚀 \
✅ **Data will be sent to Google Sheets, Slack, Gmail, or any other IFTTT service** 🔥
