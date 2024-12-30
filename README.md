# Prinzimaker's Link Shortener

### **Quick and dirty link shortener** - **v1.2.1**

**Questo progetto è realizzato in php e necessita solo di apache e di mariadb/mysql.
Una volta implementato diventa un completo sito web (in italiano) che permette di gestire la riduzione dei link complessi e la gestione delle informazioni sull'uso di questi link.**

E' un progetto _open source_ (MIT license).

### **Esempi:**
* **inserisci un link lungo e complicato e ottieni un link ridotto a 8 caratteri e un qr-code.**
* **inserisci un link accorciato e ottieni informazioni sul suo uso.**

Ad esempio come uno dei tanti: **https://www.google.com/search?q=link+shortener**
ma gestito da te privatamente.
**E' usabile sia da browser che via API (**json** reply).**
- Mancano:
  - La gestione utenti
  - un log applicativo 

Scritto in **PHP** (dalla versione **7.4** in poi) per **apache** e **mariaDB** oppure **mySQL**.

## Requisiti

- **PHP** 7.4 o superiore
- Web server
  - **apache**
  - oppure **apache2**
- Database server
  - **mariaDB**
  - oppure **mySQL**

---

# Installazione

### 1. Clona il Repository

```bash
git clone https://github.com/prinzimaker/link_shortener.git
```
---
### 2. Installa le dipendenze

```bash
composer install
```
---
### 3. Configurazione di Apache
(_trovi il file anche nella cartella /DOC_)

Per configurare Apache per questo progetto, è necessario creare un file di configurazione e abilitare alcuni moduli:

* ### Creazione del file di configurazione

Crea un nuovo file di configurazione per il sito, ad esempio `miosito.it.conf`, nella directory dei siti disponibili di Apache.

**Percorso su Ubuntu/Debian:**

```bash
sudo nano /etc/apache2/sites-available/miosito.it.conf
```

Il file di configurazione si trova in **docs/miosito.it.conf**

* ### Abilitazione del sito e moduli necessari

1. **Abilita il modulo `rewrite` di Apache:**

   ```bash
   sudo a2enmod rewrite
   ```

2. **Abilita il sito appena creato:**

   ```bash
   sudo a2ensite miosito.it.conf
   ```

3. **Riavvia Apache per applicare le modifiche:**

   ```bash
   sudo systemctl restart apache2
   ```

---

### 4. Configurazione di MySQL
Nella cartella /docs/ trovi il file **database_script.sql** che serve a generare il database e le tabelle necessarie al funzionamento dell'applicazione. 

---
### 5. Configurazione dell'Applicazione

* ### Variabili d'Ambiente

Rinomina il file `.env.sample`, che si trova nella directory principale del progetto, in `.env`  e inserisci i tuoi dati nelle variabili di configurazione.

**Nota:** Assicurati che il file `.env` non sia accessibile pubblicamente e aggiungilo al tuo `.gitignore`.
---
### 6. Permessi delle Cartelle

Imposta i permessi corretti alla cartella del progetto per consentire ad Apache di accedere ai file:

```bash
sudo chown -R www-data:www-data /var/www/html/miosito.it
sudo chmod -R 755 /var/www/html/miosito.it
```
---
# Utilizzo

## Accesso via Web

Apri il browser e naviga verso `http://miosito.it` per accedere all'interfaccia web del link shortener.

## Utilizzo delle API

Per utilizzare le API, effettua una richiesta GET all'endpoint `api` con il parametro:
* `key` - al momento qualsiasi valore va bene, non c'è la gestione utenti

e almeno uno dei seguenti parametri/comando:

* `uri` - seguito dal link lungo da ridurre -> crea uno short url
* `short` - seguito dal codice del link ridotto -> restituisce informazioni sul link 
* `calls` - - seguito dal codice del link ridotto -> restituisce un log delle singole chiamate

**Esempio: CREAZIONE DI UNO SHORT URL**

```
http://miosito.it/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757
```

**Risposta JSON di esempio:**

```json
{
    "status": "success",
    "original_url": "http://miosito.it/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757",
    "short_url": "https://miosito.it/Ab3dE5fG"
}
```

**Esempio: RICHIESTA INFORMAZIONI PER UNO SHORT URL ESISTENTE**

```
http://miosito.it/api?key=987697869&short=123456
```

**Risposta JSON di esempio:**

```json
{
    "status":"success",
    "original_url":"https:\\miosito.it\miolink&value1=123456",
    "created":"2024-12-01 09:15:00",
    "calls_count":4
}
```

**Esempio: RICHIESTA LOG CHIAMATE PER UNO SHORT URL ESISTENTE**

```
http://miosito.it/api?key=987697869&calls=4Idu5
```

**Risposta JSON di esempio:**

```json
{
    "status":"success",
    "short_id":"4Idu5",
    "calls_log":["10.10.10.10,2024-12-01 09:50:00,Rome|Lazio|Italy","128.1.15.16,2024-12-02 11:25:07,Venezia|Veneto|Italy","88.89.90.91,2024-12-02 18:03:11,Des Moines|Iowa|United States","15.5.5.1,2024-12-03 05:06:07,Brussels|Brussels Capital|Belgium"]
}
```

**Esempio: RICHIESTA ERRATA**

```
http://miosito.it/api?key=987697869&calls=DUNNO?
```

**Risposta JSON di esempio:**

```json
{
    "status":"error",
    "message":"[descrizione testuale dello stato d'errore]"
}
```


## Funzionalità

- **Creazione di link abbreviati** tramite interfaccia web.
- **API RESTful** per la generazione di link abbreviati da applicazioni esterne.
- **Statistica delle chiamate**: conteggio degli accessi e data dell'ultimo accesso per ogni link.
- **Log delle chiamate**: lista degli accessi per indirizzo IP e data, per ogni link.
- **Protezione contro loop**: verifica che non vengano creati link abbreviati che puntano a `miosito.it` stesso.

## Personalizzazione

### Pagine di Errore Personalizzate

Le pagine di errore `403.html` e `404.html` si trovano nella directory `/errors/`. Puoi personalizzarle secondo le tue esigenze.

### Stile e Temi

Puoi modificare i file CSS e HTML per personalizzare l'aspetto dell'applicazione.

## Debug e Log

- **Log di Apache:**
  - Errori: `/var/log/apache2/miosito.it_error.log`
  - Accessi: `/var/log/apache2/miosito.it_access.log`

- **Log dell'applicazione:**
  - NON Implementato - _DA IMPLEMENTARE_

## Contribuire

- Manca la gestione utenti
- Manca il logging

Se desideri contribuire al progetto:

1. Fai un fork del repository.
2. Crea un branch per la tua funzionalità o correzione: `git checkout -b mia-funzionalita`.
3. Effettua i tuoi cambiamenti e fai commit: `git commit -am 'Aggiunge una nuova funzionalità'`.
4. Pusha il branch: `git push origin mia-funzionalita`.
5. Apri una **Pull Request**.

## Licenza

Questo progetto è distribuito sotto la licenza MIT. Vedi il file [LICENSE](LICENSE) per maggiori dettagli.
___
**QR-CODE**

La generazione dei Qr-Code è ottenuta usando un generatore on line gratuito QR-SERVER.

Verifica le informazioni su 
### https://goqr.me/api/
| Fundata GmbH - Karlsruhe (DE)
___
**IP_API - API geografiche per indirizzi IP**

Le informazioni gegrafiche per gli IP sono ottenuti da un servizio gratuito sul web: 
### https://ip-api.com/

___
## Autore
Alla data dell'ultima versione l'autore del progetto è uno solo: 

- **Aldo Prinzi (Prinzimaker) aldo[AT]prinzi.it**

## Contatti

- **Email:** aldo[AT]prinzi.it
