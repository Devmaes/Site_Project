<?php
declare(strict_types=1);
require_once __DIR__.'/app.php';

function layout_start(string $title, string $bodyClass=''): void {
    $name=user();$flash=flash_notice();
    ?><!doctype html><html lang="fr"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#0a2534">
    <meta name="description" content="Prototype de portail académique IUSTE : formations, actualités et espaces privés. Projet indépendant de démonstration.">
    <title><?=e($title)?> — IUSTE · Portail académique</title>
    <link rel="stylesheet" href="/assets/site.css"><script src="/assets/site.js" defer></script>
    </head><body class="<?=e($bodyClass)?>">
    <div class="prototype-bar"><span>PROJET DE DÉMONSTRATION — plateforme indépendante, non connectée au système officiel de l’IUSTE</span></div>
    <header class="header" id="haut"><div class="container nav-row">
      <a class="brand" href="/" aria-label="Retour à l’accueil IUSTE">
        <span class="brand-mark">I<span>U</span></span><span><strong>IUSTE</strong><small>Excellence · Métiers · Innovation</small></span>
      </a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Ouvrir le menu"><span></span><span></span><span></span></button>
      <nav class="nav" id="primary-nav" aria-label="Navigation principale">
        <a href="/#accueil">Accueil</a><a href="/#institution">L’institut</a><a href="/#formations">Formations</a><a href="/#campus">Vie du campus</a><a href="/#actualites">Actualités</a>
        <a class="nav-portal" href="<?= $name?'/portail':'/connexion' ?>"><?= $name?'Mon espace':'Espace numérique' ?><span aria-hidden="true">↗</span></a>
      </nav>
    </div></header>
    <?php if($flash): ?><div class="container"><div class="alert <?=e($flash['kind'])?>" role="status"><?=e($flash['message'])?></div></div><?php endif; ?>
    <?php
}
function layout_end(): void { ?>
    <footer class="footer"><div class="container footer-grid">
      <div><a class="footer-brand" href="/">IUSTE<span class="accent">.</span></a><p>Une proposition de portail numérique pour valoriser l’apprentissage, la recherche et la vie universitaire.</p><p class="tiny">Prototype personnel — non affilié, non autorisé et non déployé par l’établissement.</p></div>
      <div><h3>Explorer</h3><a href="/#institution">À propos de l’IUSTE</a><a href="/#formations">Cycles de formation</a><a href="/#actualites">Actualités</a><a href="/connexion">Portail académique</a></div>
      <div><h3>Coordonnées publiques</h3><p>Mendong, Yaoundé · Cameroun</p><a href="tel:+237677363640">+237 677 363 640</a><a href="mailto:contact@univ-stee.com">contact@univ-stee.com</a><a href="https://www.univ-stee.com/" rel="noopener noreferrer" target="_blank">Site officiel ↗</a></div>
    </div><div class="container footer-base"><span>© <?=date('Y')?> · Concept de démonstration IUSTE</span><span>Conçu et développé par <strong>Devmaes</strong> · <a href="/mentions">Confidentialité & mentions</a></span></div></footer>
    </body></html><?php
}
function page_home(): void {
    layout_start('Bienvenue','home'); ?>
    <main>
    <section class="hero" id="accueil"><div class="hero-background" aria-hidden="true"></div>
      <div class="container hero-content"><div class="hero-copy reveal">
        <p class="eyebrow light"><span class="eyebrow-line"></span> INSTITUT UNIVERSITAIRE · YAOUNDÉ, CAMEROUN</p>
        <h1>L’avenir se <em>construit</em> ici.</h1>
        <p class="hero-intro">Apprendre, expérimenter et transformer ses ambitions en compétences. Découvrez une université tournée vers la pratique et les métiers de demain.</p>
        <div class="actions"><a class="btn btn-lime" href="#formations">Explorer nos formations <span aria-hidden="true">↗</span></a><a class="btn btn-ghost" href="/connexion">Accéder au portail <span aria-hidden="true">→</span></a></div>
        <div class="hero-facts"><span><b>05</b> cycles de formation</span><span class="fact-sep"></span><span><b>📍</b> Mendong, Yaoundé</span><span class="fact-sep"></span><span><b>BTS</b> à Ingénieur</span></div>
      </div><div class="hero-stamp" aria-label="Pédagogie et pratique"><span>LE SAVOIR</span><strong>EN ACTION</strong><span>• IUSTE •</span></div></div>
      <div class="hero-side"><span>01 — CAMPUS & AMBITION</span><span>Défiler pour explorer ↓</span></div>
    </section>
    <section class="intro section" id="institution"><div class="container intro-grid">
      <div class="section-copy reveal"><span class="eyebrow">L’INSTITUT, EN QUELQUES MOTS</span><h2>Une formation tournée vers <em>le réel.</em></h2>
        <p>L’Institut Universitaire des Sciences, des Technologies et de l’Éthique (IUSTE) met l’accent sur les enseignements professionnels, les travaux pratiques et l’accompagnement des étudiants.</p>
        <p>Son offre s’étend des cycles BTS aux formations d’ingénieurs, avec des parcours en gestion, technologie et santé.</p>
        <a class="text-link" target="_blank" rel="noopener noreferrer" href="https://www.univ-stee.com/presentation-de-liuste/">Découvrir la présentation officielle <span>↗</span></a>
      </div><div class="intro-visual reveal"><img src="/assets/photos/atelier.jpg" alt="Étudiants dans un atelier technique — photographie du projet IUSTE" loading="lazy"><div class="visual-tag"><span class="dot-green"></span><span>La pratique au cœur<br><strong>de l’apprentissage</strong></span></div></div>
    </div></section>
    <section class="programs section" id="formations"><div class="container"><div class="section-heading reveal"><div><span class="eyebrow">CONSTRUIRE SON PARCOURS</span><h2>Les formations <em>à l’IUSTE.</em></h2></div><p>Des cycles pensés pour accompagner chaque étape de votre évolution professionnelle.</p></div>
    <div class="program-grid">
      <?php $programs=[
       ['01','BTS','Formation initiale','Des bases techniques et professionnelles dans de nombreuses filières.','/assets/photos/atelier.jpg','https://www.univ-stee.com/'],
       ['02','Licence professionnelle','Se spécialiser','Approfondir ses compétences dans des domaines appliqués et professionnalisants.','/assets/photos/campus.jpg','https://www.univ-stee.com/'],
       ['03','Master professionnel','Faire évoluer son expertise','Développer une expertise avancée adaptée au marché et aux organisations.','/assets/photos/atelier-sante.jpg','https://www.univ-stee.com/'],
       ['04','Cycle ingénieur','Concevoir & innover','Se former aux défis de l’ingénierie et des technologies.','/assets/photos/ingenierie.jpg','https://www.univ-stee.com/'],
      ]; foreach($programs as $index=>$item): ?>
      <article class="program-card reveal"><div class="program-image"><img loading="lazy" src="<?=e($item[4])?>" alt="Illustration photographique d’une formation de l’IUSTE"><span class="program-number"><?=e($item[0])?></span></div><div class="program-content"><span class="program-category"><?=e($item[2])?></span><h3><?=e($item[1])?></h3><p><?=e($item[3])?></p><a href="<?=e($item[5])?>" target="_blank" rel="noopener noreferrer" aria-label="Informations officielles : <?=e($item[1])?>">En savoir plus <span>↗</span></a></div></article>
      <?php endforeach; ?>
    </div><p class="section-note">Les filières et modalités d’admission doivent être confirmées sur le <a target="_blank" rel="noopener noreferrer" href="https://www.univ-stee.com/">site officiel</a>.</p>
    </div></section>
    <section class="campus section" id="campus"><div class="container campus-grid"><div class="campus-gallery reveal"><div class="campus-main"><img loading="lazy" src="/assets/photos/campus.jpg" alt="Bâtiment photographié et utilisé dans le projet universitaire IUSTE"></div><div class="campus-small"><img loading="lazy" src="/assets/photos/salle.jpg" alt="Salle de formation photographiée pour le projet IUSTE"></div><span class="gallery-caption">UNE EXPÉRIENCE QUI DÉPASSE LA SALLE DE COURS</span></div><div class="campus-copy reveal"><span class="eyebrow">UNE VIE UNIVERSITAIRE CONCRÈTE</span><h2>Plus qu’un campus. <em>Un lieu d’expériences.</em></h2><p>Ateliers pratiques, équipements pédagogiques et vie associative : un environnement conçu pour associer théorie et mise en application.</p>
     <div class="benefit"><span>01</span><div><strong>Ateliers et laboratoires</strong><small>Apprendre en manipulant et en expérimentant.</small></div></div>
     <div class="benefit"><span>02</span><div><strong>Encadrement et suivi</strong><small>Un accompagnement pédagogique tout au long du parcours.</small></div></div>
     <div class="benefit"><span>03</span><div><strong>Ouverture professionnelle</strong><small>Des enseignements en lien avec les compétences métiers.</small></div></div>
     <a class="btn btn-dark" href="https://www.univ-stee.com/nos-infrastructures/" target="_blank" rel="noopener noreferrer">Visiter les infrastructures <span>↗</span></a>
    </div></div></section>
    <section class="portal-promo"><div class="container portal-promo-inner reveal"><div><span class="eyebrow light">UN ESPACE POUR CHACUN</span><h2>Votre vie académique, <em>en un clic.</em></h2><p>Consultez vos notes publiées ou gérez vos cours depuis un espace protégé, réservé aux utilisateurs habilités du prototype.</p></div><div class="portal-links"><a href="/connexion?role=student">Je suis étudiant <span>↗</span></a><a href="/connexion?role=teacher">Je suis enseignant <span>↗</span></a></div></div></section>
    <section class="news section" id="actualites"><div class="container"><div class="section-heading reveal"><div><span class="eyebrow">LA VIE DE L’INSTITUT</span><h2>À la <em>une.</em></h2></div><p>Une sélection éditoriale avec liens vers les articles du site officiel.</p></div><div class="news-grid">
      <article class="news-card reveal"><a target="_blank" rel="noopener noreferrer" href="https://www.univ-stee.com/deuxieme-session-du-conseil-academique-et-scientifique-de-liuste/"><div class="news-visual"><img loading="lazy" src="/assets/photos/salle.jpg" alt="Salle d’enseignement présentée dans le projet IUSTE"></div><span>28 JANVIER 2026 · VIE ACADÉMIQUE</span><h3>Deuxième session du Conseil académique et scientifique de l’IUSTE</h3><small>Lire sur le site officiel ↗</small></a></article>
      <article class="news-card reveal"><a target="_blank" rel="noopener noreferrer" href="https://www.univ-stee.com/un-ancien-etudiant-de-liuste-sacre-major-national-au-concours-du-cpfa/"><div class="news-visual"><img loading="lazy" src="/assets/photos/atelier-sante.jpg" alt="Séance de formation médicale présentée dans le projet IUSTE"></div><span>28 JANVIER 2026 · RÉUSSITE</span><h3>Un ancien étudiant distingué au concours du CPFA</h3><small>Lire sur le site officiel ↗</small></a></article>
      <article class="news-card reveal"><a target="_blank" rel="noopener noreferrer" href="https://www.univ-stee.com/%F0%9F%9A%A6-recherche-scientifique-a-liuste-publication-sur-la-mobilite-pendulaire-a-yaounde/"><div class="news-visual"><img loading="lazy" src="/assets/photos/ingenierie.jpg" alt="Étudiants en formation technique présentés dans le projet IUSTE"></div><span>20 NOVEMBRE 2025 · RECHERCHE</span><h3>Recherche scientifique : publication sur la mobilité pendulaire à Yaoundé</h3><small>Lire sur le site officiel ↗</small></a></article>
    </div></div></section>
    <section class="contact-band"><div class="container contact-inner"><div><span class="eyebrow">ÉCHANGER AVEC L’ÉCOLE</span><h2>Votre parcours commence ici.</h2><p>Pour les admissions et renseignements officiels, contactez directement l’établissement.</p></div><a class="btn btn-dark" href="mailto:contact@univ-stee.com">Contacter l’IUSTE ↗</a></div></section>
    </main><?php layout_end();
}
function page_login(?string $error): void {
   layout_start('Connexion','auth-page');?>
   <main class="auth-shell"><div class="auth-visual"><img src="/assets/photos/campus.jpg" alt="Vue du bâtiment présentée dans les archives du projet IUSTE"><div class="auth-visual-overlay"><span class="eyebrow light">VOTRE ESPACE NUMÉRIQUE</span><h1>Le savoir se partage.<br><em>Vos données se protègent.</em></h1><p>Un accès personnalisé pour étudiants, enseignants et personnel autorisé.</p></div></div>
   <div class="auth-panel"><div class="auth-form"><a class="return-link" href="/">← Retour à l’accueil</a><span class="eyebrow">ESPACE PRIVÉ</span><h2>Heureux de vous retrouver.</h2><p>Connectez-vous avec les identifiants fournis par l’administrateur de cette démonstration.</p>
   <?php if($error):?><div class="alert danger" role="alert"><?=e($error)?></div><?php endif;?>
   <form action="/connexion" method="post"><?=csrf_field()?><label for="identifier">Identifiant ou matricule</label><input id="identifier" name="identifier" required minlength="3" maxlength="40" autocomplete="username" placeholder="Votre identifiant"><label for="password">Mot de passe</label><input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Votre mot de passe"><button class="btn btn-dark full" type="submit">Se connecter <span aria-hidden="true">→</span></button></form>
   <div class="auth-note"><strong>Confidentialité d’abord</strong><p>Aucun compte officiel de l’université ne fonctionne ici. Cette application ne doit jamais recevoir de véritables notes sans autorisation, audit et déploiement sécurisé.</p></div></div></div></main>
   <?php layout_end();
}
function dash_open(string $title, array $person): void {
 layout_start($title,'dashboard-page');?><main class="dashboard container"><div class="dash-head"><div><span class="eyebrow">ESPACE ACADÉMIQUE SÉCURISÉ</span><h1>Bonjour, <?=e($person['full_name'])?> <span class="wave">✳</span></h1><p><?=e(match($person['role']){'student'=>'Votre progression académique, en toute confidentialité.','teacher'=>'Saisissez les notes, vérifiez-les puis publiez-les.','admin'=>'Gérez les comptes et les accès aux cours.',default=>''})?></p></div><form action="/deconnexion" method="post"><?=csrf_field()?><button class="btn btn-outline" type="submit">Se déconnecter ↗</button></form></div>
 <?php
}
function dash_close(): void {echo '</main>';layout_end();}
function page_student(array $person): void {
 $results=student_results(db(),(int)$person['id']);
 dash_open('Mes résultats',$person);?>
 <section class="dash-summary"><div class="summary-card"><span>Notes publiées</span><strong><?=count($results)?></strong><small>Uniquement vos propres résultats</small></div><div class="summary-card"><span>Matières suivies</span><strong><?=count(sql_all('SELECT course_id FROM enrollments WHERE student_id=?',[$person['id']]))?></strong><small>Inscriptions enregistrées</small></div><div class="summary-card highlight"><span>Statut de publication</span><strong>Protégé</strong><small>Les brouillons ne sont pas accessibles</small></div></section>
 <section class="panel"><div class="panel-head"><div><span class="eyebrow">CONSULTATION PERSONNELLE</span><h2>Mes résultats académiques</h2></div><span class="pill">Lecture seule</span></div>
 <?php if(!$results): ?><div class="empty"><span>⌁</span><h3>Aucune note publiée pour l’instant.</h3><p>Les résultats apparaîtront une fois validés et publiés par l’enseignant responsable.</p></div>
 <?php else: ?><div class="table-scroll"><table><thead><tr><th>Code</th><th>Matière</th><th>Semestre</th><th>Note / 20</th><th>État</th></tr></thead><tbody><?php foreach($results as $r): ?><tr><td><?=e($r['code'])?></td><td><?=e($r['title'])?></td><td><?=e($r['semester'])?></td><td><strong><?=number_format((float)$r['grade'],2,',',' ')?></strong></td><td><span class="pill green">Publiée</span></td></tr><?php endforeach;?></tbody></table></div><?php endif;?></section>
 <?php dash_close();
}
function page_teacher(array $person):void {
 $courses=sql_all('SELECT c.* FROM courses c JOIN teaching_assignments t ON c.id=t.course_id WHERE t.teacher_id=? ORDER BY c.title',[$person['id']]);
 $ids=array_column($courses,'id');$students=[];$grades=[];
 foreach($courses as $course){
   $students[$course['id']]=sql_all('SELECT u.id,u.full_name,u.identifier FROM users u JOIN enrollments e ON e.student_id=u.id WHERE e.course_id=? ORDER BY u.full_name',[$course['id']]);
   $grades[$course['id']]=sql_all('SELECT g.*,u.full_name,c.title FROM grades g JOIN users u ON u.id=g.student_id JOIN courses c ON c.id=g.course_id WHERE g.course_id=? ORDER BY g.updated_at DESC',[$course['id']]);
 }
 dash_open('Espace enseignant',$person);?>
 <div class="dash-summary"><div class="summary-card"><span>Mes cours</span><strong><?=count($courses)?></strong><small>Cours attribués</small></div><div class="summary-card"><span>Étudiants inscrits</span><strong><?=array_sum(array_map('count',$students))?></strong><small>Sur les cours attribués</small></div><div class="summary-card highlight"><span>Workflow de publication</span><strong>Brouillon → Publication</strong><small>Une note modifiée retourne en brouillon</small></div></div>
 <?php if(!$courses):?><section class="panel empty"><h2>Aucun cours attribué</h2><p>Demandez à l’administrateur du prototype de vous affecter à une matière.</p></section><?php endif;?>
 <?php foreach($courses as $c): ?><section class="panel course-panel"><div class="panel-head"><div><span class="eyebrow"><?=e($c['code'])?> · <?=e($c['cycle'])?></span><h2><?=e($c['title'])?></h2></div><span class="pill"><?=count($students[$c['id']])?> étudiant(s)</span></div>
 <div class="teacher-grid"><form action="/enseignant/note" method="post" class="form-card"><?=csrf_field()?><h3>Saisir une note</h3><input type="hidden" name="course_id" value="<?= (int)$c['id']?>"><label>Étudiant inscrit<select name="student_id" required><option value="">Choisir...</option><?php foreach($students[$c['id']] as $stu):?><option value="<?=(int)$stu['id']?>"><?=e($stu['full_name'])?> — <?=e($stu['identifier'])?></option><?php endforeach;?></select></label><label>Semestre<input required name="semester" maxlength="30" placeholder="2026-S1" value="2026-S1"></label><label>Note / 20<input required type="number" min="0" max="20" step="0.01" name="grade" placeholder="14,5"></label><button class="btn btn-dark full" type="submit" <?=count($students[$c['id']])?'':'disabled'?>>Enregistrer le brouillon →</button><small>Les notes enregistrées restent invisibles aux étudiants jusqu’à leur publication.</small></form>
 <div class="form-card"><h3>Publier les résultats</h3><p>Après vérification, les notes en brouillon deviennent consultables par les étudiants concernés.</p><form action="/enseignant/publier" method="post" data-confirm="Publier toutes les notes en brouillon de ce semestre ?"><?=csrf_field()?><input type="hidden" name="course_id" value="<?=(int)$c['id']?>"><label>Semestre<input required maxlength="30" name="semester" value="2026-S1"></label><button class="btn btn-lime full" type="submit">Publier les notes →</button></form><p class="muted-text">Toute correction remet automatiquement la note concernée en brouillon.</p></div></div>
 <?php if($grades[$c['id']]):?><div class="table-scroll"><table><thead><tr><th>Étudiant</th><th>Semestre</th><th>Note / 20</th><th>État</th><th>Mise à jour</th></tr></thead><tbody><?php foreach($grades[$c['id']] as $r):?><tr><td><?=e($r['full_name'])?></td><td><?=e($r['semester'])?></td><td><?=number_format((float)$r['grade'],2,',',' ')?></td><td><span class="pill <?=$r['status']==='published'?'green':''?>"><?=e($r['status']==='published'?'Publiée':'Brouillon')?></span></td><td><?=e($r['updated_at'])?></td></tr><?php endforeach;?></tbody></table></div><?php endif;?></section><?php endforeach;
 dash_close();
}
function page_admin(array $person):void {
 $courses=sql_all('SELECT * FROM courses ORDER BY cycle,title');
 $people=sql_all("SELECT id,identifier,full_name,role FROM users WHERE role IN ('student','teacher') ORDER BY role,full_name LIMIT 200");
 dash_open('Administration',$person);?>
 <section class="dash-summary"><div class="summary-card"><span>Utilisateurs</span><strong><?=count(sql_all('SELECT id FROM users'))?></strong><small>Comptes locaux</small></div><div class="summary-card"><span>Cours</span><strong><?=count($courses)?></strong><small>Catalogue du prototype</small></div><div class="summary-card highlight"><span>Séparation des accès</span><strong>3 rôles</strong><small>Étudiants · Enseignants · Administration</small></div></section>
 <div class="admin-grid"><section class="panel"><h2>Créer un compte</h2><p>Distribuez les identifiants par un canal privé. Ne réutilisez jamais un mot de passe officiel.</p><form method="post" action="/admin/utilisateur" class="form-stack"><?=csrf_field()?><label>Identifiant<input required name="identifier" pattern="[A-Za-z0-9._-]{3,40}" placeholder="ETU-2026-001"></label><label>Nom complet<input required name="full_name" maxlength="150" placeholder="Nom et prénom"></label><label>Rôle<select required name="role"><option value="student">Étudiant</option><option value="teacher">Enseignant</option></select></label><label>Mot de passe temporaire (12 caractères min.)<input type="password" minlength="12" required name="password" autocomplete="new-password"></label><button class="btn btn-dark full">Créer le compte →</button></form></section>
 <section class="panel"><h2>Affecter à un cours</h2><p>Étudiant : inscription. Enseignant : autorisation de saisie et publication.</p><form method="post" action="/admin/affectation" class="form-stack"><?=csrf_field()?><label>Utilisateur<select name="user_id" required><option value="">Choisir...</option><?php foreach($people as $u):?><option value="<?=(int)$u['id']?>"><?=e($u['identifier'])?> — <?=e($u['full_name'])?> (<?=e($u['role'])?>)</option><?php endforeach;?></select></label><label>Formation / cours<select name="course_id" required><option value="">Choisir...</option><?php foreach($courses as $c):?><option value="<?=(int)$c['id']?>"><?=e($c['code'])?> · <?=e($c['title'])?></option><?php endforeach;?></select></label><button class="btn btn-lime full">Enregistrer l’affectation →</button></form></section></div>
 <section class="panel"><div class="panel-head"><h2>Comptes locaux</h2><span class="pill">Affichage limité à 200 comptes</span></div><div class="table-scroll"><table><thead><tr><th>Identifiant</th><th>Nom</th><th>Rôle</th></tr></thead><tbody><?php foreach($people as $u):?><tr><td><?=e($u['identifier'])?></td><td><?=e($u['full_name'])?></td><td><?=e($u['role'])?></td></tr><?php endforeach;?></tbody></table></div></section>
 <?php dash_close();
}
function page_legal():void {layout_start('Mentions et confidentialité');?><main class="container legal"><span class="eyebrow">INFORMATIONS SUR LE PROJET</span><h1>Mentions & confidentialité</h1><p>Ce site est un <strong>prototype indépendant</strong> inspiré de l’IUSTE. Il n’est ni le site officiel, ni une plateforme autorisée pour stocker des notes réelles. Les coordonnées et descriptions institutionnelles proviennent du site public de l’IUSTE.</p><p>Les images affichées sont des photographies déjà présentes dans le dépôt de démonstration de Devmaes et présentées dans ce dépôt comme se rapportant à l’établissement. Leur autorisation de republication doit être vérifiée avant toute mise en ligne publique.</p><p>Les identifiants, empreintes de mots de passe et notes fictives sont stockés localement dans SQLite, hors du répertoire public. Aucun mot de passe ne doit être réutilisé d’un compte universitaire officiel.</p><p>Pour toute exploitation réelle, obtenir l’accord formel de l’établissement, auditer la sécurité, documenter le traitement des données et définir les responsables de publication.</p><a class="text-link" href="/">← Retour à l’accueil</a></main><?php layout_end();}
function page_error(string $title,string $message):void {layout_start($title);?><main class="container legal"><span class="eyebrow">INFORMATION</span><h1><?=e($title)?></h1><p><?=e($message)?></p><a class="btn btn-dark" href="/">Retour à l’accueil →</a></main><?php layout_end();}

