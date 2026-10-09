<?php
declare(strict_types=1);
$testDir=sys_get_temp_dir().'/iuste_test_'.bin2hex(random_bytes(6));
putenv('IUSTE_DATA_DIR='.$testDir);
require dirname(__DIR__).'/src/app.php';
require dirname(__DIR__).'/src/school.php';
if(!extension_loaded('pdo_sqlite')) {fwrite(STDERR,"pdo_sqlite missing: tests require PDO SQLite.\n");exit(2);}
$pdo=db();install_schema($pdo);
$asserts=0;
function check(bool $cond,string $label):void {global $asserts;if(!$cond)throw new RuntimeException('FAIL: '.$label);$asserts++;echo "OK $label\n";}
try {
    $prof=create_user($pdo,'PROF-TEST','Enseignant fictif','teacher','LocalPassword-Teachers#123');
    $prof2=create_user($pdo,'PROF-OTHER','Autre enseignant fictif','teacher','LocalPassword-Teachers#456');
    $stu=create_user($pdo,'STU-TEST','Étudiant fictif','student','LocalPassword-Students#123');
    $stu2=create_user($pdo,'STU-OTHER','Autre étudiant fictif','student','LocalPassword-Students#456');
    $pdo->exec("INSERT INTO courses(code,title,cycle) VALUES ('TST1','Cours fictif','BTS'),('TST2','Autre cours','BTS')");
    $c1=(int)$pdo->query("SELECT id FROM courses WHERE code='TST1'")->fetchColumn();
    $c2=(int)$pdo->query("SELECT id FROM courses WHERE code='TST2'")->fetchColumn();
    $pdo->prepare('INSERT INTO teaching_assignments VALUES (?,?)')->execute([$prof,$c1]);
    $pdo->prepare('INSERT INTO enrollments VALUES (?,?)')->execute([$stu,$c1]);
    $pdo->prepare('INSERT INTO enrollments VALUES (?,?)')->execute([$stu2,$c2]);
    check(assigned_to($pdo,$prof,$c1),'Teacher assigned');
    check(!assigned_to($pdo,$prof2,$c1),'Other teacher forbidden');
    check(enrolls_in($pdo,$stu,$c1),'Student enrolled');
    check(!enrolls_in($pdo,$stu2,$c1),'Other student not enrolled');
    save_grade($pdo,$prof,$c1,$stu,'2026-S1',15.5);
    check(count(student_results($pdo,$stu))===0,'Draft never visible to student');
    check(count(student_results($pdo,$stu2))===0,'Other student cannot see results');
    $denied=false;try{save_grade($pdo,$prof2,$c1,$stu,'2026-S1',18);}catch(DomainException){$denied=true;}
    check($denied,'Unassigned teacher cannot change grades');
    $denied=false;try{save_grade($pdo,$prof,$c1,$stu2,'2026-S1',18);}catch(DomainException){$denied=true;}
    check($denied,'Non-enrolled student cannot be graded');
    foreach([-1,20.1,'not-number',INF] as $bad){$denied=false;try{save_grade($pdo,$prof,$c1,$stu,'2026-S1',$bad);}catch(DomainException){$denied=true;}check($denied,'Invalid grades rejected '.(is_scalar($bad)?(string)$bad:'NaN'));}
    $denied=false;try{publish_grades($pdo,$prof2,$c1,'2026-S1');}catch(DomainException){$denied=true;}
    check($denied,'Unassigned teacher cannot publish');
    check(publish_grades($pdo,$prof,$c1,'2026-S1')===1,'Teacher publishes exactly one grade');
    check(count(student_results($pdo,$stu))===1,'Published grade visible to owner');
    check(count(student_results($pdo,$stu2))===0,'Student data isolation after publication');
    save_grade($pdo,$prof,$c1,$stu,'2026-S1',18);
    check(count(student_results($pdo,$stu))===0,'Editing repends publication');
    check(publish_grades($pdo,$prof,$c1,'2026-S1')===1,'Republish edited grade');
    check((float)student_results($pdo,$stu)[0]['grade']===18.0,'New grade visible');
    $hash=$pdo->query("SELECT password_hash FROM users WHERE id=$stu")->fetchColumn();
    check($hash!=='LocalPassword-Students#123'&&password_verify('LocalPassword-Students#123',$hash),'Passwords hashed and verified');
    check((int)$pdo->query('SELECT COUNT(*) FROM audit_events')->fetchColumn()>=4,'Audit events recorded');
    echo "PASS: $asserts assertions\n";
} finally {
  $pdo=null;
  foreach(glob($testDir.'/*')?:[] as $file)@unlink($file);
  @rmdir($testDir);
}

