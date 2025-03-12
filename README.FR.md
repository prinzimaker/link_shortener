# Raccourcisseur de liens de Prinzimaker

### **Raccourcisseur de liens rapide et simple** - **v1.4.1**

**Ce projet est développé en PHP et ne nécessite qu'Apache et MariaDB/MySQL.  
Une fois mis en œuvre, il devient un site web complet (en italien par défaut) permettant de gérer le raccourcissement de liens complexes et de surveiller leur utilisation.**

C'est un projet _open source_ (licence MIT).

### **Exemples :**
* **Insérez un lien long et complexe et obtenez un lien raccourci de 8 caractères ainsi qu'un QR code.**
* **Insérez un lien raccourci et obtenez des informations sur son utilisation.**

Par exemple : **https://www.google.com/search?q=link+shortener**  
mais géré de manière privée par vous.  
**Utilisable à la fois depuis un navigateur et via une API (**réponse json**).**

- Actuellement manquant :
  - Gestion des utilisateurs
  - Un journal d'application

Écrit en **PHP** (version **7.4** et plus) pour **Apache** et **MariaDB** ou **MySQL**.

## Exigences

- **PHP** 7.4 ou supérieur
- Serveur Web
  - **Apache**
  - ou **Apache2**
- Serveur de base de données
  - **MariaDB**
  - ou **MySQL**

### Documentation Open API
- Lien :
https://prinzimaker.github.io/link_shortener/
---

## Documentation API - Interface et Exemples  

### Documentation OpenApi  
- Lien : [https://prinzimaker.github.io/link_shortener/](https://prinzimaker.github.io/link_shortener/)  

### POSTMAN  
- Importer une définition d'API dans Postman :  
- Sélectionnez **Importer** dans la barre latérale.  
- Choisissez de charger l'URL suivante :  
  [https://prinzimaker.github.io/link_shortener/openapi.yaml](https://prinzimaker.github.io/link_shortener/openapi.yaml)  

En savoir plus sur [l'importation d'une API dans Postman](https://learning.postman.com/docs/designing-and-developing-your-api/importing-an-api/)  

### Documentation API avec REDOC  
- Lien : [https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml](https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml)  
---

# Installation

### 1. Cloner le Référentiel

```bash
git clone https://github.com/prinzimaker/link_shortener.git
```



### 2. Installer les Dépendances

```bash
composer install
```



### 3. Configuration Apache
(_Vous pouvez également trouver le fichier dans le dossier /DOC_)

Pour configurer Apache pour ce projet, vous devez créer un fichier de configuration et activer certains modules :

* ### Créer le fichier de configuration

Créez un nouveau fichier de configuration pour votre site, par exemple `shortlink.conf`, dans le répertoire sites-available d'Apache.

**Chemin sous Ubuntu/Debian :**

```bash
sudo nano /etc/apache2/sites-available/shortlink.conf
```

Vous pouvez trouver un fichier de configuration exemple dans **docs/shortlink.conf**.

* ### Activer le site et les modules requis

1. **Activer le module `rewrite` d'Apache :**

   ```bash
   sudo a2enmod rewrite
   ```

2. **Activer le site nouvellement créé :**

   ```bash
   sudo a2ensite shortlink.conf
   ```

3. **Redémarrer Apache pour appliquer les modifications :**

   ```bash
   sudo systemctl restart apache2
   ```



### 4. Configuration MySQL
Dans le dossier `/docs/`, vous trouverez le fichier **database_script.sql** qui crée la base de données et les tables nécessaires.



### 5. Configuration de l'Application

* ### Variables d'Environnement

Renommez le fichier `.env.sample`, situé à la racine du projet, en `.env` et insérez vos données dans les variables de configuration.

**Remarque :** Assurez-vous que le fichier `.env` n'est pas accessible publiquement et ajoutez-le à votre `.gitignore`.



### 6. Permissions des Dossiers

Définissez les bonnes permissions sur le dossier du projet afin qu'Apache puisse accéder aux fichiers :

```bash
sudo chown -R www-data:www-data /var/www/html/monsite.fr
sudo chmod -R 755 /var/www/html/monsite.fr
```

---

# Utilisation

## Accès Web

Ouvrez votre navigateur et accédez à `http://monsite.fr` pour utiliser l'interface web du raccourcisseur de liens.

## Utilisation de l'API

Pour utiliser les API, effectuez une requête GET vers le point d'accès `api` avec le paramètre :
* `key` – actuellement, n'importe quelle valeur fonctionne car la gestion des utilisateurs n'est pas encore implémentée

et au moins l'un des paramètres/commandes suivants :

* `uri` – suivi du lien long à raccourcir -> crée une URL courte  
* `short` – suivi du code du lien raccourci -> renvoie des informations sur le lien  
* `calls` – suivi du code du lien raccourci -> renvoie un journal des appels individuels  

**Exemple : CRÉATION D'UNE URL COURTE**

```
http://monsite.fr/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757
```

**Exemple de réponse JSON :**

```json
{
    "status": "success",
    "original_url": "http://monsite.fr/api?key=987697869&uri=https://www.example.com/page.php?myvalue1=23456&othval=97867ygh087g087g8&longval=6576576-hjuhiu-ouy8757",
    "short_url": "https://monsite.fr/Ab3dE5fG"
}
```

---

## Fonctionnalités

- **Création de liens raccourcis** via une interface web.
- **API RESTful** pour la génération externe de liens.
- **Statistiques d'accès** : nombre d'accès et date du dernier accès pour chaque lien.
- **Journal des accès** : liste des accès par adresse IP et date pour chaque lien.
- **Protection contre les boucles** : empêche la création de liens raccourcis pointant vers `monsite.fr`.

## Personnalisation

### Intégration avec IFTTT
Voir /docs/IFTTT pour les instructions sur l'intégration des événements CLICK avec IFTTT.

### Pages d'Erreur Personnalisées

Les pages d'erreur `403.html` et `404.html` sont situées dans le répertoire `/errors/`. Vous pouvez les personnaliser.

### Styles et Thèmes

Vous pouvez modifier les fichiers CSS et HTML pour changer l'apparence de l'application.

## Débogage et Journaux

- **Journaux Apache :**
  - Erreurs : `/var/log/apache2/monsite.fr_error.log`
  - Accès : `/var/log/apache2/monsite.fr_access.log`

- **Journal de l'application :**
  - NON Implémenté – _À IMPLÉMENTER_

## Contribution

- Gestion des utilisateurs manquante
- Journalisation manquante

Si vous souhaitez contribuer :

1. Forkez le référentiel.
2. Créez une branche : `git checkout -b ma-fonctionnalité`.
3. Apportez vos modifications et validez-les : `git commit -am 'Ajout d'une nouvelle fonctionnalité'`.
4. Poussez la branche : `git push origin ma-fonctionnalité`.
5. Ouvrez une **Pull Request**.

## Licence

Ce projet est distribué sous la licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

**QR-CODE**

La génération du QR-code utilise un générateur en ligne gratuit :  
### https://goqr.me/api/

| Fundata GmbH - Karlsruhe (DE)

---

**Géolocalisation des adresses IP**

Ce projet prend en charge la géolocalisation des adresses IP via la base de données gratuite IP2Location LITE.

### https://lite.ip2location.com
| Hexasoft Development Sdn. Bhd.

Consultez le dossier `/ip2location` pour plus de détails.

---

## Auteur

À la dernière version, l'auteur du projet est :

- **Aldo Prinzi (Prinzimaker) aldo[AT]prinzi.it**

## Contacts

- **Email:** aldo[AT]prinzi.it
