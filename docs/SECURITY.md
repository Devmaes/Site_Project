# Sécurité du portail académique — périmètre de la démonstration

- **Aucune donnée universitaire réelle.** Aucun fichier de résultats ou liste d'étudiants issu du site officiel n'est importé.
- **Dossiers publics séparés.** Document root `public/`, et base SQLite stockée à côté du dépôt (`../iuste_private/`) ou dans `IUSTE_DATA_DIR`.
- **Sessions protégées.** Cookie HTTP-only, SameSite=Lax, HTTPS-only sur une connexion HTTPS, régénération de session à la connexion et déconnexion.
- **Anti-CSRF.** Chaque écriture POST nécessite un jeton CSRF côté serveur.
- **Rôles.** Étudiant : consultation de *ses propres* notes publiées. Enseignant : saisie/publication **uniquement** sur les cours qui lui sont attribués. Admin : création de comptes et affectations.
- **Notes en brouillon.** Non visibles par les étudiants jusqu'à publication par l'enseignant responsable ; toute correction remet la note en brouillon.
- **SQL paramétré.** Les données utilisateur passent par des requêtes préparées ; les seules interpolations de table concernent deux noms constants définis côté serveur.
- **Mots de passe.** Hachage via `password_hash(PASSWORD_DEFAULT)` ; 12 caractères min. ; jamais d'identifiants intégrés au frontend. Ne jamais connecter des comptes institutionnels réels à ce prototype.
- **Journalisation.** Créations de comptes, affectations, saisies et publications enregistrées sans mots de passe.
- **Démonstration.** `php scripts/setup.php --demo` crée deux comptes *fictifs* avec mots de passe générés aléatoirement, à usage local uniquement.

## Avant tout déploiement institutionnel

Accord écrit de l'université ; politique de protection des données selon la législation applicable ; HTTPS ; stockage chiffré et sauvegardes ; MFA pour les comptes sensibles ; authentification institutionnelle ; procédure de rectification des notes ; journal d'audit détaillé ; surveillance, gestion de sessions et limites par IP ; politique de renouvellement des mots de passe ; revue de sécurité indépendante et tests de charge.

Les photographies reprises du dépôt original sont présentées comme photographies de l'école par ce dépôt. Le responsable du projet doit confirmer leurs droits de republication avant une publication externe. Ce prototype ne représente pas officiellement l'IUSTE.

