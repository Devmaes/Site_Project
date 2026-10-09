<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/src/app.php';
require_once dirname(__DIR__) . '/src/school.php';
require_once dirname(__DIR__) . '/src/pages.php';
session_start_safe();
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
header('Cache-Control: no-store');
$uri=parse_url($_SERVER['REQUEST_URI'] ?? '/',PHP_URL_PATH) ?: '/';
$method=$_SERVER['REQUEST_METHOD'] ?? 'GET';
$error=null;
try {
    if ($method==='POST') {
        verify_csrf();
        if ($uri==='/connexion') {
            $loginId=trim((string)($_POST['identifier']??'')); $password=(string)($_POST['password']??'');
            if (login($loginId,$password)) redirect('/portail');
            $error='Identifiant ou mot de passe incorrect.';
        } elseif ($uri==='/deconnexion') {
            logout(); notice('Vous êtes déconnecté.'); redirect('/');
        } elseif ($uri==='/enseignant/note') {
            $actor=require_role('teacher');
            save_grade(db(),(int)$actor['id'],(int)($_POST['course_id']??0), (int)($_POST['student_id']??0),
               (string)($_POST['semester']??''), $_POST['grade']??'');
            notice('Brouillon enregistré. La note n’est pas encore visible pour l’étudiant.'); redirect('/portail');
        } elseif ($uri==='/enseignant/publier') {
            $actor=require_role('teacher');
            $count=publish_grades(db(),(int)$actor['id'],(int)($_POST['course_id']??0),(string)($_POST['semester']??''));
            notice("$count note(s) publiée(s). Les étudiants concernés peuvent les consulter."); redirect('/portail');
        } elseif ($uri==='/admin/utilisateur') {
            $actor=require_role('admin');
            $id=create_user(db(),(string)($_POST['identifier']??''),(string)($_POST['full_name']??''),(string)($_POST['role']??''),(string)($_POST['password']??''));
            audit(db(),(int)$actor['id'],'account_created',"user=$id");
            notice('Compte créé. Pensez à l’inscrire à des cours ou à lui attribuer des cours.'); redirect('/portail');
        } elseif ($uri==='/admin/affectation') {
            $actor=require_role('admin');
            $uid=(int)($_POST['user_id']??0);$course=(int)($_POST['course_id']??0);
            $role=sql_one('SELECT role FROM users WHERE id=?',[$uid]);
            if (!$role||!in_array($role['role'],['student','teacher'],true)||!sql_one('SELECT id FROM courses WHERE id=?',[$course])) throw new DomainException('Compte ou cours invalide.');
            $table=$role['role']==='student'?'enrollments':'teaching_assignments';
            $column=$role['role']==='student'?'student_id':'teacher_id';
            $stmt=db()->prepare("INSERT OR IGNORE INTO $table($column,course_id) VALUES (?,?)");$stmt->execute([$uid,$course]);
            audit(db(),(int)$actor['id'],'course_assignment',"user=$uid course=$course");
            notice('Affectation enregistrée.');redirect('/portail');
        } else { http_response_code(404); $error='Action inexistante.'; }
    }
    if ($uri==='/') page_home();
    elseif ($uri==='/connexion') page_login($error);
    elseif ($uri==='/portail') {
       $actor=require_role('student','teacher','admin');
       if($actor['role']==='student') page_student($actor);
       elseif($actor['role']==='teacher') page_teacher($actor);
       else page_admin($actor);
    } elseif ($uri==='/mentions') page_legal();
    else {http_response_code(404);page_error('Page introuvable','Cette adresse ne correspond à aucune page.');}
} catch (DomainException $e) {
    if(http_response_code()===200) http_response_code(400);
    page_error('Action impossible',$e->getMessage());
} catch (PDOException $e) {
    error_log('IUSTE PDO error: '.$e->getMessage());
    http_response_code(503);
    page_error('Service temporairement indisponible','Initialisez le portail et vérifiez la configuration de la base de données.');
} catch (RuntimeException $e) {
    error_log('IUSTE configuration error: '.$e->getMessage());
    http_response_code(503);
    page_error('Configuration nécessaire','Veuillez vérifier l’installation de PHP, de SQLite et du stockage privé.');
}

