# PHP Framework - GameCatalog

## Docker commandes de base

- `docker-compose up -d` - permet de lancer ses conteneurs docker présents dans `docker-compose.yml`.
- `docker-compose down` - arrête tous les conteneurs en marche.
- `docker-compose ps` - liste tous les conteneurs et leur status.
- `docker-compose logs -f` - permet de voir les logs des conteneurs en marche.
- `docker-compose exec php bash` - permet d'entrer dans le conteneur php.

Par convention, le fichier doit s'appeler `docker-compose.yml` ou `compose.yml`. Il utilise le YAML.
Pour ajouter des dépendances, il faut utiliser le fichier `Dockerfile`.

## Circulation des données

| Couche     | Fait du SQL | Connait HTTP | Logique métier |
| ---------- | ----------- | ------------ | -------------- |
| BDD        | oui         | non          | non            |
| Repository | oui         | non          | non            |
| Service    | non         | non          | oui            |
| Controller | non         | oui          | non            |

### 1. Base de données (BDD)

**Rôle**
- Stocker les données brutes (tables, lignes)
- Aucune logique applicative
- MySQL / PostgreSQL / SQLite…

**Exemples**
- Table `games`
- Colonnes : `id`, `title`, `platform`, `genre`, `release_year`, `rating`

**Règle**
> La BDD ne sait rien de PHP, des contrôleurs ou de l’UI.

---

### 2. Repository (accès aux données)

**Rôle**
- Parler **uniquement à la base**
- Contenir le SQL
- Retourner des données “simples” (tableaux, objets)

**Responsabilités**
- `SELECT`, `INSERT`, `UPDATE`, `DELETE`
- Aucune logique métier
- Aucune logique HTTP

**Exemple**
```php
GameRepository::findAll()
GameRepository::findAllSortedByRating()
GameRepository::findTop($id)
```
**Règle**

Le repository ne sait pas pourquoi on demande les données,
il sait seulement comment les récupérer.

### 3. Service (logique métier)
**Rôle**
- Orchester les repositories
- Appliquer les règles métier
- Transformer / filtrer / valider les données

**Responsabilités**
- Combiner plusieurs repositories
- Décider quoi faire si une donnée n’existe pas
- Préparer les données pour le contrôleur

**Exemple**
```php
Copier le code
getLimitedGames($id)
getAllGames()
getAllGamesSortedByRating()
```

**Règle**

Le service ne connaît ni $_GET, ni $_POST, ni le HTML.

### 4. Controller (entrée HTTP)
**Rôle**
- Gérer la requête utilisateur
- Appeler les services
- Choisir la vue à afficher

**Responsabilités**
- Lire `$_GET`, `$_POST`
- Gérer les routes (?page=home, ?page=detail)
- Passer les données à la vue

**Exemple**

```php
Copier le code
home()
gameDetail()
addGame()
```

**Règle**

Le contrôleur ne fait pas de SQL
et ne décide pas des règles métier.

