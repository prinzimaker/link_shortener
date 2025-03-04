# Acortador de Enlaces de Prinzimaker
**Acortador de enlaces rápido y sencillo - v1.4.0**

Este proyecto está realizado en PHP y solo requiere Apache y MariaDB/MySQL. Una vez implementado, se convierte en un sitio web completo (en italiano) que permite gestionar la reducción de enlaces complejos y la administración de información sobre el uso de estos enlaces.

Es un proyecto de código abierto (licencia MIT).

## Ejemplos:
- Introduce un enlace largo y complicado y obtén un enlace reducido a 8 caracteres y un código QR.
- Introduce un enlace acortado y obtén información sobre su uso.  
  Por ejemplo, como uno de muchos: [https://www.google.com/search?q=link+shortener](https://www.google.com/search?q=link+shortener), pero gestionado por ti de forma privada. Es utilizable tanto desde el navegador como a través de una API (respuesta en JSON).

## Faltan:
- La gestión de usuarios.
- Un registro (log) de la aplicación.

Escrito en PHP (desde la versión 7.4 en adelante) para Apache y MariaDB o MySQL.

## Requisitos
- **PHP**: 7.4 o superior  
- **Servidor web**:  
  - Apache  
  - O Apache2  
- **Servidor de base de datos**:  
  - MariaDB  
  - O MySQL  

## Documentación Open API
- Enlace: [https://prinzimaker.github.io/link_shortener/](https://prinzimaker.github.io/link_shortener/)  

## Documentación API - Interfaz y Ejemplos
### Documentación OpenAPI
- Enlace: [https://prinzimaker.github.io/link_shortener/](https://prinzimaker.github.io/link_shortener/)  

### POSTMAN
Para importar una definición de API en Postman:  
1. Selecciona **Importar** en la barra lateral.  
2. Elige cargar la siguiente URL:  
   [https://prinzimaker.github.io/link_shortener/openapi.yaml](https://prinzimaker.github.io/link_shortener/openapi.yaml)  
3. Descubre más sobre cómo importar una API en Postman.

### Documentación API con REDOC
- Enlace: [https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml](https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml)  

## Instalación
1. **Clona el Repositorio**  
```bash
   git clone https://github.com/prinzimaker/link_shortener.git
```

### 2. Instala las dependencias
```bash
composer install
```

### 3. Configuración de Apache
(_encuentra el archivo también en la carpeta /DOC_)

Para configurar Apache para este proyecto, es necesario crear un archivo de configuración y habilitar algunos módulos:

* ### Creación del archivo de configuración

Crea un nuevo archivo de configuración para el sitio, por ejemplo `misitio.es.conf`, en el directorio de sitios disponibles de Apache.

**Ruta en Ubuntu/Debian:**

```bash
sudo nano /etc/apache2/sites-available/misitio.es.conf
```

El archivo de configuración se encuentra en **docs/misitio.es.conf**

* ### Habilitación del sitio y módulos necesarios

1. **Habilita el módulo `rewrite` de Apache:**

   ```bash
   sudo a2enmod rewrite
   ```

2. **Habilita el sitio recién creado:**

   ```bash
   sudo a2ensite miosito.it.conf
   ```

3. **Reinicia Apache para aplicar los cambios:**

   ```bash
   sudo systemctl restart apache2
   ```



### 4. Configuración de MySQL
En la carpeta /docs/ encontrarás el archivo **database_script.sql** que sirve para generar la base de datos y las tablas necesarias para el funcionamiento de la aplicación.

### 5. Configuración de la Aplicación

* ### Variables de Entorno

Renombra el archivo `.env.sample`, que se encuentra en el directorio principal del proyecto, a `.env` e inserta tus datos en las variables de configuración.

**Nota:** Asegúrate de que el archivo `.env` no sea accesible públicamente y añádelo a tu `.gitignore`.

### 6. Permisos de las Carpetas

Configura los permisos correctos en la carpeta del proyecto para permitir que Apache acceda a los archivos:

```bash
sudo chown -R www-data:www-data /var/www/html/miosito.it
sudo chmod -R 755 /var/www/html/miosito.it
```
---
# Uso

## Acceso vía Web

Abre el navegador y navega hacia `http://misitio.es` para acceder a la interfaz web del acortador de enlaces.

## Uso de las APIs

Para utilizar las APIs, realiza una solicitud GET al endpoint `api` con el parámetro:
* `key` - por ahora cualquier valor es válido, no hay gestión de usuarios

y al menos uno de los siguientes parámetros/comandos:

* `uri` - seguido del enlace largo a reducir -> crea una URL corta
* `short` - seguido del código del enlace reducido -> devuelve información sobre el enlace
* `calls` - seguido del código del enlace reducido -> devuelve un registro de las llamadas individuales

**Ejemplo: CREACIÓN DE UNA URL CORTA**
```
http://miosito.it/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757
```

**Respuesta JSON de ejemplo:**

```json
{
    "status": "success",
    "original_url": "http://miosito.it/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757",
    "short_url": "https://miosito.it/Ab3dE5fG"
}
```

**Ejemplo: SOLICITUD DE INFORMACIÓN PARA UNA URL CORTA EXISTENTE**

```
http://miosito.it/api?key=987697869&short=123456
```

**Respuesta JSON de ejemplo:**

```json
{
    "status":"success",
    "original_url":"https:\\miosito.it\miolink&value1=123456",
    "created":"2024-12-01 09:15:00",
    "calls_count":4
}
```

**Ejemplo: SOLICITUD DE REGISTRO DE LLAMADAS PARA UNA URL CORTA EXISTENTE**

```
http://miosito.it/api?key=987697869&calls=4Idu5
```

**Respuesta JSON de ejemplo:**

```json
{
    "status":"success",
    "short_id":"4Idu5",
    "calls_log":["10.10.10.10,2024-12-01 09:50:00,Rome|Lazio|Italy","128.1.15.16,2024-12-02 11:25:07,Venezia|Veneto|Italy","88.89.90.91,2024-12-02 18:03:11,Des Moines|Iowa|United States","15.5.5.1,2024-12-03 05:06:07,Brussels|Brussels Capital|Belgium"]
}
```

**Ejemplo: SOLICITUD ERRÓNEA**

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


## Funcionalidades

- **Creación de enlaces acortados** a través de la interfaz web.
- **API RESTful** para la generación de enlaces acortados desde aplicaciones externas.
- **Estadísticas de las llamadas**: conteo de accesos y fecha del último acceso para cada enlace.
- **Registro de las llamadas**: lista de accesos por dirección IP y fecha para cada enlace.
- **Protección contra bucles**: verifica que no se creen enlaces acortados que apunten a `misitio.es` mismo.

## Personalización

### Integración con IFTTT
Consulta /docs/IFTTT para instrucciones sobre cómo integrar los eventos CLICK con IFTTT.

### Páginas de Error Personalizadas

Las páginas de error `403.html` y `404.html` se encuentran en el directorio `/errors/`. Puedes personalizarlas según tus necesidades.

### Estilo y Temas

Puedes modificar los archivos CSS y HTML para personalizar la apariencia de la aplicación.

## Depuración y Registros

- **Registros de Apache:**
  - Errores: `/var/log/apache2/misitio.es_error.log`
  - Accesos: `/var/log/apache2/misitio.es_access.log`

- **Registro de la aplicación:**
  - NO Implementado - _POR IMPLEMENTAR_

## Contribuir

- Falta la gestión de usuarios
- Falta el registro (logging)

Si deseas contribuir al proyecto:

1. Haz un fork del repositorio.
2. Crea una rama para tu funcionalidad o corrección: `git checkout -b mi-funcionalidad`.
3. Realiza tus cambios y haz commit: `git commit -am 'Añade una nueva funcionalidad'`.
4. Sube la rama: `git push origin mi-funcionalidad`.
5. Abre una **Pull Request**.

## Licencia

Este proyecto está distribuido bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
---
**QR-CODE**

La generación de códigos QR se realiza utilizando un generador en línea gratuito QR-SERVER.

Verifica la información en 
### https://goqr.me/api/
| Fundata GmbH - Karlsruhe (DE)
---
**Localización de Direcciones IP**

Este proyecto soporta la localización de direcciones IP a través de la base de datos de IP2Location LITE.

Verifica la información en 
### https://lite.ip2location.com
| Hexasoft Development Sdn. Bhd.

Consulta la carpeta /ip2location para más información
---
## Autor
A la fecha de la última versión, el autor del proyecto es solo uno:

- **Aldo Prinzi (Prinzimaker) aldo[AT]prinzi.it**

## Contactos

- **Email:** aldo[AT]prinzi.it