<?php
declare(strict_types=1);
require_once __DIR__ . '/app.php';

/** Only a teacher explicitly assigned to a course can save/publish its grades. */
function assigned_to(PDO $pdo, int $teacherId, int $courseId): bool {
    $s = $pdo->prepare('SELECT 1 FROM teaching_assignments WHERE teacher_id=? AND course_id=?');
    $s->execute([$teacherId,$courseId]);
    return (bool) $s->fetchColumn();
}
function enrolls_in(PDO $pdo, int $studentId, int $courseId): bool {
    $s=$pdo->prepare('SELECT 1 FROM enrollments WHERE student_id=? AND course_id=?');
    $s->execute([$studentId,$courseId]);
    return (bool) $s->fetchColumn();
}
function save_grade(PDO $pdo, int $teacherId, int $courseId, int $studentId, string $semester, mixed $score): void {
    if (!is_numeric($score) || !is_finite((float)$score) || (float)$score<0 || (float)$score>20) {
        throw new DomainException('La note doit être comprise entre 0 et 20.');
    }
    $semester = trim($semester);
    if ($semester==='' || strlen($semester)>30) throw new DomainException('Semestre incorrect.');
    if (!assigned_to($pdo,$teacherId,$courseId)) throw new DomainException('Ce cours ne vous est pas attribué.');
    if (!enrolls_in($pdo,$studentId,$courseId)) throw new DomainException('Cet étudiant ne suit pas ce cours.');
    $pdo->beginTransaction();
    try {
        $s=$pdo->prepare(<<<'SQL'
INSERT INTO grades(student_id,course_id,semester,grade,status,updated_by)
VALUES(?,?,?,?,'draft',?)
ON CONFLICT(student_id,course_id,semester) DO UPDATE SET
 grade=excluded.grade,status='draft',updated_by=excluded.updated_by,updated_at=CURRENT_TIMESTAMP
SQL);
        $s->execute([$studentId,$courseId,$semester,(float)$score,$teacherId]);
        audit($pdo,$teacherId,'grade_saved',"course=$courseId student=$studentId semester=$semester");
        $pdo->commit();
    } catch (Throwable $e) { $pdo->rollBack(); throw $e; }
}
function publish_grades(PDO $pdo,int $teacherId,int $courseId,string $semester): int {
    if (!assigned_to($pdo,$teacherId,$courseId)) throw new DomainException('Ce cours ne vous est pas attribué.');
    $semester=trim($semester);
    if ($semester===''||strlen($semester)>30) throw new DomainException('Semestre incorrect.');
    $pdo->beginTransaction();
    try {
        $s=$pdo->prepare("UPDATE grades SET status='published',updated_at=CURRENT_TIMESTAMP WHERE course_id=? AND semester=? AND status='draft'");
        $s->execute([$courseId,$semester]);
        $count=$s->rowCount();
        audit($pdo,$teacherId,'grades_published',"course=$courseId semester=$semester count=$count");
        $pdo->commit(); return $count;
    } catch (Throwable $e) { $pdo->rollBack(); throw $e; }
}
function student_results(PDO $pdo, int $studentId): array {
    $s=$pdo->prepare(<<<'SQL'
SELECT c.code,c.title,c.cycle,g.semester,g.grade,g.updated_at
FROM grades g JOIN courses c ON c.id=g.course_id
WHERE g.student_id=? AND g.status='published'
ORDER BY g.semester DESC,c.title
SQL);
    $s->execute([$studentId]); return $s->fetchAll(PDO::FETCH_ASSOC);
}
function create_user(PDO $pdo, string $identifier, string $name, string $role, string $password): int {
    $identifier=trim($identifier); $name=trim($name);
    if (!preg_match('/^[A-Za-z0-9._-]{3,40}$/D',$identifier) || $name==='' || strlen($name)>150 ||
        !in_array($role,['student','teacher','admin'],true) || strlen($password)<12)
        throw new DomainException('Identifiant, nom, rôle ou mot de passe invalide (12 caractères minimum).');
    $s=$pdo->prepare('INSERT INTO users(identifier,full_name,role,password_hash) VALUES(?,?,?,?)');
    $s->execute([$identifier,$name,$role,password_hash($password,PASSWORD_DEFAULT)]);
    return (int)$pdo->lastInsertId();
}

