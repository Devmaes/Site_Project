# Comment ouvrir la nouvelle interface

## Accueil rénové (visible immédiatement)

Ouvrir **index.html à la racine** du dépôt. L’ancienne page \`Home/Home_page.html\` redirige également vers la nouvelle vitrine.

Les menus de navigation, le diaporama, le filtre/recherche de formations, les accordéons FAQ et la démonstration de notes étudiant/enseignant sont interactifs.

**La démonstration dans index.html n’est pas une authentification ni un système de notes réel.** Aucun résultat universitaire n’y est enregistré. Les photos utilisées proviennent de fichiers existants du dépôt, réutilisés sous \`public/assets/photos/\`.

## Portail sécurisé PHP/SQLite

Pour enregistrer des notes fictives persistantes avec contrôle des rôles, il faut lancer l’application PHP :

\`\`\`sh
php scripts/setup.php
php scripts/setup.php --demo
php -S 127.0.0.1:8000 -t public public/router.php
\`\`\`

Puis ouvrir **http://127.0.0.1:8000/** et sélectionner la connexion étudiant ou enseignant. Les comptes de démonstration sont générés localement. L’administrateur crée les comptes et attribue les cours. Seules les notes publiées sont consultables par l’étudiant concerné.

Prérequis : PHP 8.2+ avec extensions pdo_sqlite et sqlite3.

**GitHub Pages ne peut pas exécuter PHP.** Pour mettre en ligne le portail réel, utilisez un hébergement PHP HTTPS dont la racine web est \`public/\`, jamais la racine du dépôt. N’utilisez pas de vraies données personnelles sans autorisation institutionnelle et audit de sécurité.
