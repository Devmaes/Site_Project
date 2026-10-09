# IUSTE · Campus & Portail académique (prototype indépendant)

**Projet de refonte de `Devmaes/Site_Project`** : site vitrine responsive, animé et accessible, plus authentification PHP / SQLite et publication sécurisée de notes fictives. **Developed by Devmaes.**

> ⚠️ **Projet personnel de démonstration, non officiel, non connecté au SI de l'université.** Ne pas stocker de véritables notes, identifiants ou dossiers étudiants sans l'autorisation écrite de l'établissement et un audit complet.

## Visuels authentiques et provenance

Les cinq images reprises de `Home/slide (*.jpg)` ont été trouvées **dans le dépôt original** et sont des photographies, pas des créations générées par IA ni des images de banque générique :

| Nouveau chemin | Fichier existant réutilisé | Usage |
| --- | --- | --- |
| `public/assets/photos/campus.jpg` | `Home/slide (2).jpg` | Bâtiment/campus |
| `public/assets/photos/atelier.jpg` | `Home/slide (5).jpg` | Atelier pratique |
| `public/assets/photos/atelier-sante.jpg` | `Home/slide (12).jpg` | Formation santé |
| `public/assets/photos/ingenierie.jpg` | `Home/slide (11).jpg` | Ingénierie |
| `public/assets/photos/salle.jpg` | `Home/slide (13).jpg` | Salle de classe |

Les nouveaux chemins Git réutilisent les **objets binaires existants à l'identique** (mêmes SHA Git). Il faut néanmoins vérifier les autorisations de publication et l'identification des lieux avant de publier ce projet au nom de l'établissement.

Les descriptions de l'école et les cycles de formation proviennent de [univ-stee.com](https://www.univ-stee.com/) ; le prototype renvoie vers le site officiel pour la vérification des programmes et les démarches d'admission. Les actualités sont une sélection éditoriale datée, **pas** une synchronisation en temps réel.

## Fonctionnalités

- Accueil éditorial moderne avec photographie du campus, animations légères, version mobile, navigation accessible, mise en page responsive et respect de `prefers-reduced-motion`.
- Présentation de l'établissement, cycles BTS / licence professionnelle / master professionnel / ingénieur, campus et actualités.
- Connexion de démonstration avec identifiant et mot de passe haché.
- **Étudiant :** ses propres notes *publiées* uniquement ; les brouillons des enseignants restent invisibles.
- **Enseignant :** notes de ses étudiants, seulement pour les cours qui lui sont attribués, saisie de brouillons et publication explicite.
- **Administrateur :** comptes étudiants/enseignants et affectations aux cours.
- SQLite local, événements d'audit, jetons anti-CSRF, droits d'accès contrôlés côté serveur.

## Prérequis

- PHP **8.2+** avec extensions **PDO SQLite** / `pdo_sqlite` / `sqlite3`.
- Une installation locale ou un hébergement PHP qui permet de choisir **`public/` comme racine du site**.
- Pas de dépendance npm ou Composer pour démarrer.

## Démarrage local

```bash
php -m | grep -i sqlite
php scripts/setup.php
php scripts/setup.php --demo   # facultatif : crée 2 comptes fictifs, imprime des mots de passe aléatoires
php -S 127.0.0.1:8000 -t public public/router.php
```

Naviguer sur `http://127.0.0.1:8000`. Pour créer un administrateur sans mot de passe en argument :

```bash
php scripts/create_admin.php ADM-LOCAL "Administrateur local"
```

Les données privées sont stockées par défaut **en dehors du dépôt** dans `../iuste_private/iuste.sqlite`. Pour utiliser un autre emplacement, définissez la variable d'environnement `IUSTE_DATA_DIR`.

**À ne pas faire :** démarrer un serveur web dont la racine est celle du dépôt entier. Cela exposerait les scripts serveur et éventuellement les données personnelles.

## Tests

```bash
php tests/run.php
find public src scripts tests -name '*.php' -exec php -l {} \;
node --check public/assets/site.js
```

Les tests incluent la protection des notes en brouillon, l'isolation étudiant/étudiant, le contrôle enseignant/cours, le blocage des notes hors 0–20 et la vérification du hachage des mots de passe. GitHub Actions lance automatiquement les tests dans un environnement PHP avec SQLite.

## Architecture

```text
public/                Racine publique (PHP, CSS, JS, photos de l'école)
src/app.php            Configuration, sessions, authentification, SQLite
src/school.php         Règles métier des cours et résultats
src/pages.php          Vues serveur HTML accessibles
scripts/setup.php      Migration initiale + démonstration fictive
scripts/create_admin.php
 tests/run.php          Tests d'intégrité et d'autorisation
```

L'ancien prototype HTML/PHP, ses essais, son CSS et ses archives photos restent accessibles dans l'historique et les dossiers originaux du dépôt Git. `main` n'est pas modifié avant la revue de la pull request.

Consulter [docs/SECURITY.md](docs/SECURITY.md) et [docs/ROADMAP.md](docs/ROADMAP.md).

## Attribution

**Developed by Devmaes**. Concept personnel, sans affiliation officielle ni marque déposée revendiquée.

