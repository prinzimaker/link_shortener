# Prinzimaker's Link Shortener

### **Quick and dirty link shortener** - **v1.4.0**

**This project is built in PHP and only requires Apache and MariaDB/MySQL.  
Once implemented, it becomes a complete website (in Italian by default) that allows you to manage the shortening of complex links and the monitoring of their usage.**

It is an _open source_ project (MIT license).

### **Examples:**
* **Insert a long, complicated link and get an 8-character shortened link plus a QR code.**
* **Insert a shortened link and get information about its usage.**

For example: **https://www.google.com/search?q=link+shortener**  
but privately managed by you.  
**It can be used from both a browser and via API (**json** reply).**

- Currently missing:
  - User management
  - An application log

Written in **PHP** (version **7.4** onward) for **Apache** and **MariaDB** or **MySQL**.

## Requirements

- **PHP** 7.4 or higher
- Web server
  - **Apache**
  - or **Apache2**
- Database server
  - **MariaDB**
  - or **MySQL**

### Open Api documentation
- Link:
https://prinzimaker.github.io/link_shortener/
---

## API interface docs/samples
### OpenApi documentation
- Link: https://prinzimaker.github.io/link_shortener/

### POSTMAN
- Import an API definition into Postman:\
- Select Import in the sidebar. 
- Choose to upload the following URL: https://prinzimaker.github.io/link_shortener/openapi.yaml 

Learn more about [importing an API into Postman](https://learning.postman.com/docs/designing-and-developing-your-api/importing-an-api/)

### REDOC Api documentation
- Link: https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml
---

# Installation

### 1. Clone the Repository

```bash
git clone https://github.com/prinzimaker/link_shortener.git
```

---

### 2. Install Dependencies

```bash
composer install
```

---

### 3. Apache Configuration
(_You can also find the file in the /DOC folder_)

To configure Apache for this project, you need to create a configuration file and enable some modules:

* ### Create the configuration file

Create a new configuration file for your site, for example `miosito.it.conf`, in the Apache sites-available directory.

**Path on Ubuntu/Debian:**

```bash
sudo nano /etc/apache2/sites-available/miosito.it.conf
```

You can find the sample configuration file in **docs/miosito.it.conf**.

* ### Enable the site and required modules

1. **Enable the Apache `rewrite` module:**

   ```bash
   sudo a2enmod rewrite
   ```

2. **Enable the newly created site:**

   ```bash
   sudo a2ensite miosito.it.conf
   ```

3. **Restart Apache to apply the changes:**

   ```bash
   sudo systemctl restart apache2
   ```

---

### 4. MySQL Configuration
In the /docs/ folder, you will find the **database_script.sql** file that creates the database and necessary tables.

---

### 5. Application Configuration

* ### Environment Variables

Rename the `.env.sample` file, located in the project's root directory, to `.env` and insert your data into the configuration variables.

**Note:** Ensure that the `.env` file is not publicly accessible and add it to your `.gitignore`.

---

### 6. Folder Permissions

Set the correct permissions on the project folder so that Apache can access the files:

```bash
sudo chown -R www-data:www-data /var/www/html/miosito.it
sudo chmod -R 755 /var/www/html/miosito.it
```

---

# Usage

## Web Access

Open your browser and go to `http://miosito.it` to access the link shortener’s web interface.

## API Usage

To use the APIs, make a GET request to the `api` endpoint with the parameter:
* `key` – currently, any value works since user management is not implemented

and at least one of the following parameters/commands:

* `uri` – followed by the long link to be shortened -> creates a short URL  
* `short` – followed by the shortened link code -> returns information about the link  
* `calls` – followed by the shortened link code -> returns a log of individual calls  

**Example: CREATING A SHORT URL**

```
http://miosito.it/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757
```

**Sample JSON response:**

```json
{
    "status": "success",
    "original_url": "http://miosito.it/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757",
    "short_url": "https://miosito.it/Ab3dE5fG"
}
```

---

## Features

- **Shortened link creation** via a web interface.
- **RESTful API** for external link generation.
- **Call statistics**: number of accesses and last access date for each link.
- **Call log**: list of accesses by IP address and date for each link.
- **Loop protection**: prevents the creation of shortened links pointing to `miosito.it`.

## Customization

### Custom Error Pages

The `403.html` and `404.html` error pages are located in the `/errors/` directory. You can customize them.

### Styles and Themes

You can modify the CSS and HTML files to change the appearance of the application.

## Debug and Logs

- **Apache Logs:**
  - Errors: `/var/log/apache2/miosito.it_error.log`
  - Access: `/var/log/apache2/miosito.it_access.log`

- **Application Log:**
  - NOT Implemented – _TO BE IMPLEMENTED_

## Contributing

- Missing user management
- Missing logging

If you wish to contribute:

1. Fork the repository.
2. Create a branch: `git checkout -b my-feature`.
3. Make your changes and commit them: `git commit -am 'Add a new feature'`.
4. Push the branch: `git push origin my-feature`.
5. Open a **Pull Request**.

## License

This project is distributed under the MIT license. See the [LICENSE](LICENSE) file for details.

---

**QR-CODE**

The QR-code generation uses a free online generator:  
### https://goqr.me/api/

| Fundata GmbH - Karlsruhe (DE)

---

** IP address geo-localisation**

This project supports the IP addresses geolocalisation through the IP2Location LITE free database.

### https://lite.ip2location.com
| Hexasoft Development Sdn. Bhd.

Check the /ip2location folder for further details


---

## Author

As of the latest version, the project author is:

- **Aldo Prinzi (Prinzimaker) aldo[AT]prinzi.it**

## Contacts

- **Email:** aldo[AT]prinzi.it
```