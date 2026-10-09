<?php
declare(strict_types=1);

const APP_NAME = 'Portail IUSTE — Projet de démonstration';

function data_dir(): string {
    $path = getenv('IUSTE_DATA_DIR') ?: dirname(__DIR__, 2) . '/iuste_private';
    if (!is_dir($path) && !mkdir($path, 0700, true) && !is_dir($path)) {
        throw new RuntimeException('Répertoire privé inaccessible : ' . $path);
    }
    return $path;
}

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    if (!extension_loaded('pdo_sqlite')) throw new RuntimeException('Activer pdo_sqlite pour utiliser le portail.');
    $pdo = new PDO('sqlite:' . data_dir() . '/iuste.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON; PRAGMA busy_timeout = 5000;');
    return $pdo;
}

function install_schema(PDO $pdo): void {
    $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY, identifier TEXT NOT NULL UNIQUE,
  full_name TEXT NOT NULL, password_hash TEXT NOT NULL,
  role TEXT NOT NULL CHECK(role IN ('student','teacher','admin')),
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS courses (
  id INTEGER PRIMARY KEY, code TEXT NOT NULL UNIQUE, title TEXT NOT NULL,
  cycle TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS enrollments (
  student_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
  PRIMARY KEY (student_id,course_id)
);
CREATE TABLE IF NOT EXISTS teaching_assignments (
  teacher_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
  PRIMARY KEY (teacher_id,course_id)
);
CREATE TABLE IF NOT EXISTS grades (
  id INTEGER PRIMARY KEY,
  student_id INTEGER NOT NULL REFERENCES users(id),
  course_id INTEGER NOT NULL REFERENCES courses(id),
  semester TEXT NOT NULL CHECK(length(semester) BETWEEN 1 AND 30),
  grade REAL NOT NULL CHECK(grade BETWEEN 0 AND 20),
  status TEXT NOT NULL DEFAULT 'draft' CHECK(status IN ('draft','published')),
  updated_by INTEGER NOT NULL REFERENCES users(id),
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(student_id,course_id,semester)
);
CREATE TABLE IF NOT EXISTS audit_events (
  id INTEGER PRIMARY KEY, actor_id INTEGER REFERENCES users(id),
  action TEXT NOT NULL, details TEXT NOT NULL DEFAULT '',
  occurred_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS grade_student_status ON grades(student_id,status);
SQL);
}

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function path(string $path = '/'): string { return $path; }
function redirect(string $to): never { header('Location: ' . $to, true, 303); exit; }
function session_start_safe(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly'=>true, 'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite'=>'Lax', 'path'=>'/'
    ]);
    session_start();
}
function csrf_token(): string {
    session_start_safe();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void {
    session_start_safe();
    if (!isset($_POST['csrf']) || !is_string($_POST['csrf']) || !hash_equals(csrf_token(), $_POST['csrf'])) {
        http_response_code(419);
        throw new DomainException('Session expirée. Rechargez la page et réessayez.');
    }
}
function user(): ?array {
    session_start_safe();
    return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
}
function require_role(string ...$roles): array {
    $person = user();
    if ($person === null) redirect('/connexion');
    if (!in_array($person['role'], $roles, true)) { http_response_code(403); throw new DomainException('Accès interdit.'); }
    return $person;
}
function login(string $identifier, string $password): bool {
    session_start_safe();
    $attempts = $_SESSION['login_attempts'] ?? ['since'=>time(), 'count'=>0];
    if (time() - $attempts['since'] > 900) $attempts = ['since'=>time(), 'count'=>0];
    if ($attempts['count'] >= 7) throw new DomainException('Trop de tentatives. Réessayez dans 15 minutes.');
    $statement = db()->prepare('SELECT id,identifier,full_name,role,password_hash FROM users WHERE identifier = ?');
    $statement->execute([$identifier]);
    $record = $statement->fetch();
    if (!$record || !password_verify($password, $record['password_hash'])) {
        $attempts['count']++;
        $_SESSION['login_attempts'] = $attempts;
        return false;
    }
    session_regenerate_id(true);
    unset($record['password_hash'], $_SESSION['login_attempts']);
    $_SESSION['user'] = $record;
    return true;
}
function logout(): void {
    session_start_safe();
    $_SESSION = [];
    session_regenerate_id(true);
}
function notice(string $message, string $kind='success'): void {
    session_start_safe(); $_SESSION['flash']=['message'=>$message,'kind'=>$kind];
}
function flash_notice(): ?array {
    session_start_safe(); $item=$_SESSION['flash']??null; unset($_SESSION['flash']); return $item;
}
function audit(PDO $db, int $actorId, string $action, string $details=''): void {
    $statement=$db->prepare('INSERT INTO audit_events(actor_id,action,details) VALUES (?,?,?)');
    $statement->execute([$actorId,$action,$details]);
}
function sql_all(string $query, array $params=[]): array {
    $stmt=db()->prepare($query); $stmt->execute($params); return $stmt->fetchAll();
}
function sql_one(string $query,array $params=[]): ?array {
    $stmt=db()->prepare($query); $stmt->execute($params); $row=$stmt->fetch(); return $row ?: null;
}

