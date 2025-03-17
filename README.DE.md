# Prinzimaker's Linkverkürzer

### **Schneller und einfacher Linkverkürzer** - **v1.4.2**

**Dieses Projekt ist in PHP geschrieben und benötigt nur Apache und MariaDB/MySQL.  
Nach der Implementierung wird es zu einer vollständigen Website (standardmäßig auf Italienisch), die es ermöglicht, komplexe Links zu verkürzen und deren Nutzung zu überwachen.**

Es ist ein _Open-Source_-Projekt (MIT-Lizenz).

### **Beispiele:**
* **Geben Sie einen langen, komplizierten Link ein und erhalten Sie einen 8-stelligen Kurzlink sowie einen QR-Code.**
* **Geben Sie einen Kurzlink ein und erhalten Sie Informationen über dessen Nutzung.**

Zum Beispiel: **https://www.google.com/search?q=link+shortener**  
aber privat von Ihnen verwaltet.  
**Kann sowohl über einen Browser als auch über eine API (**json**-Antwort) verwendet werden.**

- Derzeit nicht implementiert:
  - Benutzerverwaltung
  - Anwendungsprotokoll

Geschrieben in **PHP** (Version **7.4** und höher) für **Apache** und **MariaDB** oder **MySQL**.

## Anforderungen

- **PHP** 7.4 oder höher
- Webserver
  - **Apache**
  - oder **Apache2**
- Datenbankserver
  - **MariaDB**
  - oder **MySQL**

### Open API-Dokumentation
- Link:
https://prinzimaker.github.io/link_shortener/
---

## API-Dokumentation - Schnittstelle und Beispiele  

### OpenApi-Dokumentation  
- Link: [https://prinzimaker.github.io/link_shortener/](https://prinzimaker.github.io/link_shortener/)  

### POSTMAN  
- Eine API-Definition in Postman importieren:  
- Wählen Sie **Importieren** in der Seitenleiste.  
- Wählen Sie die folgende URL zum Hochladen aus:  
  [https://prinzimaker.github.io/link_shortener/openapi.yaml](https://prinzimaker.github.io/link_shortener/openapi.yaml)  

Erfahren Sie mehr über [den Import einer API in Postman](https://learning.postman.com/docs/designing-and-developing-your-api/importing-an-api/)  

### API-Dokumentation mit REDOC  
- Link: [https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml](https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml)  

---
# Installation

### 1. Repository klonen

```bash
git clone https://github.com/prinzimaker/link_shortener.git
```



### 2. Abhängigkeiten installieren

```bash
composer install
```


### 3. Apache-Konfiguration
(_Die Datei befindet sich auch im /DOC-Verzeichnis_)

Um Apache für dieses Projekt zu konfigurieren, müssen Sie eine Konfigurationsdatei erstellen und einige Module aktivieren:

* ### Erstellen der Konfigurationsdatei

Erstellen Sie eine neue Konfigurationsdatei für Ihre Website, z. B. `shortlink.conf`, im Apache-Verzeichnis `sites-available`.

**Pfad unter Ubuntu/Debian:**

```bash
sudo nano /etc/apache2/sites-available/shortlink.conf
```

Eine Beispielkonfigurationsdatei finden Sie unter **docs/shortlink.conf**.

* ### Aktivieren der Website und der erforderlichen Module

1. **Apache-Modul `rewrite` aktivieren:**

   ```bash
   sudo a2enmod rewrite
   ```

2. **Die neu erstellte Website aktivieren:**

   ```bash
   sudo a2ensite shortlink.conf
   ```

3. **Apache neu starten, um die Änderungen zu übernehmen:**

   ```bash
   sudo systemctl restart apache2
   ```



### 4. MySQL-Konfiguration
Im Verzeichnis `/docs/` finden Sie die Datei **database_script.sql**, die die Datenbank und die erforderlichen Tabellen erstellt.



### 5. Anwendungskonfiguration

* ### Umgebungsvariablen

Benennen Sie die Datei `.env.sample` im Stammverzeichnis des Projekts in `.env` um und fügen Sie Ihre Daten in die Konfigurationsvariablen ein.

**Hinweis:** Stellen Sie sicher, dass die `.env`-Datei nicht öffentlich zugänglich ist, und fügen Sie sie Ihrer `.gitignore` hinzu.



### 6. Ordnerberechtigungen

Legen Sie die richtigen Berechtigungen für das Projektverzeichnis fest, damit Apache auf die Dateien zugreifen kann:

```bash
sudo chown -R www-data:www-data /var/www/html/meineseite.de
sudo chmod -R 755 /var/www/html/meineseite.de
```

---

# Nutzung

## Webzugriff

Öffnen Sie Ihren Browser und gehen Sie zu `http://meineseite.de`, um die Weboberfläche des Linkverkürzers zu nutzen.

## API-Nutzung

Um die API zu verwenden, senden Sie eine GET-Anfrage an den `api`-Endpunkt mit dem Parameter:
* `key` – derzeit kann jeder Wert verwendet werden, da die Benutzerverwaltung noch nicht implementiert ist

und mindestens einem der folgenden Parameter/Befehle:

* `uri` – gefolgt vom langen Link, der verkürzt werden soll -> erstellt eine Kurz-URL  
* `short` – gefolgt vom Code des Kurzlinks -> gibt Informationen über den Link zurück  
* `calls` – gefolgt vom Code des Kurzlinks -> gibt ein Protokoll der einzelnen Aufrufe zurück  

**Beispiel: ERSTELLUNG EINER KURZ-URL**

```
http://meineseite.de/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757
```

**Beispiel für eine JSON-Antwort:**

```json
{
    "status": "success",
    "original_url": "http://meineseite.de/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757",
    "short_url": "https://meineseite.de/Ab3dE5fG"
}
```

---

## Funktionen

- **Erstellung von Kurzlinks** über eine Weboberfläche.
- **RESTful API** zur externen Generierung von Links.
- **Zugriffsstatistiken**: Anzahl der Zugriffe und Datum des letzten Zugriffs für jeden Link.
- **Zugriffsprotokoll**: Liste der Zugriffe nach IP-Adresse und Datum für jeden Link.
- **Schleifenschutz**: Verhindert die Erstellung von Kurzlinks, die auf `meineseite.de` verweisen.

## Anpassung

### Integration mit IFTTT
Siehe /docs/IFTTT für Anleitungen zur Integration von CLICK-Ereignissen mit IFTTT.

### Benutzerdefinierte Fehlerseiten

Die Fehlerseiten `403.html` und `404.html` befinden sich im Verzeichnis `/errors/`. Sie können sie anpassen.

### Stile und Themen

Sie können die CSS- und HTML-Dateien ändern, um das Erscheinungsbild der Anwendung anzupassen.

## Debugging und Protokolle

- **Apache-Protokolle:**
  - Fehler: `/var/log/apache2/meineseite.de_error.log`
  - Zugriff: `/var/log/apache2/meineseite.de_access.log`

- **Anwendungsprotokoll:**
  - NICHT Implementiert – _NOCH ZU IMPLEMENTIEREN_

## Mitwirken

- Fehlende Benutzerverwaltung
- Fehlendes Logging

Wenn Sie mitwirken möchten:

1. Forken Sie das Repository.
2. Erstellen Sie einen Branch: `git checkout -b mein-feature`.
3. Nehmen Sie Ihre Änderungen vor und committen Sie sie: `git commit -am 'Neue Funktion hinzugefügt'`.
4. Pushen Sie den Branch: `git push origin mein-feature`.
5. Öffnen Sie eine **Pull Request**.

## Lizenz

Dieses Projekt wird unter der MIT-Lizenz verteilt. Siehe die Datei [LICENSE](LICENSE) für weitere Details.

---

## Autor

In der neuesten Version ist der Autor des Projekts:

- **Aldo Prinzi (Prinzimaker) aldo[AT]prinzi.it**

## Kontakt

- **E-Mail:** aldo[AT]prinzi.it
