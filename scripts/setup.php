<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') {http_response_code(403);exit;}
require dirname(__DIR__).'/src/app.php';
require dirname(__DIR__).'/src/school.php';
$pdo=db(); install_schema($pdo);
$catalogue=[
  ['BTS-GLT','Gestion Logistique et Transport','BTS'],
  ['BTS-RS','Réseaux et Sécurité','BTS'],
  ['BTS-SI','Sciences Infirmières','BTS'],
  ['LIPRO-GLBD','Génie Logiciel : Bases de données','Licence professionnelle'],
  ['MAPRO-GI','Génie Informatique','Master professionnel'],
  ['ING-GI','Génie Informatique','Ingénieur'],
];
$s=$pdo->prepare('INSERT OR IGNORE INTO courses(code,title,cycle) VALUES (?,?,?)');
foreach($catalogue as $course) $s->execute($course);
echo "Schéma SQLite et exemples de cours initialisés.\n";
if (($argv[1]??'')==='--demo') {
  $users=[['DEMO-ETU','Étudiant Démo','student'],['DEMO-PROF','Enseignant Démo','teacher']];
  foreach($users as [$code,$name,$role]){
    if(sql_one('SELECT id FROM users WHERE identifier=?',[$code]))continue;
    $pass=bin2hex(random_bytes(12));$id=create_user($pdo,$code,$name,$role,$pass);
    echo "$role: $code / $pass (à conserver localement)\n";
  }
  $student=sql_one('SELECT id FROM users WHERE identifier=?',['DEMO-ETU']);
  $teacher=sql_one('SELECT id FROM users WHERE identifier=?',['DEMO-PROF']);
  $course=sql_one('SELECT id FROM courses WHERE code=?',['BTS-RS']);
  $pdo->prepare('INSERT OR IGNORE INTO enrollments(student_id,course_id) VALUES(?,?)')->execute([$student['id'],$course['id']]);
  $pdo->prepare('INSERT OR IGNORE INTO teaching_assignments(teacher_id,course_id) VALUES(?,?)')->execute([$teacher['id'],$course['id']]);
  echo "Données fictives DEMO uniquement. Aucun résultat réel importé.\n";
}

