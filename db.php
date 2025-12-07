<?php
// Simple PDO wrapper for SQLite. Creates DB and tables automatically.
$DB_FILE = __DIR__ . '/resumes.db';
try {
    $pdo = new PDO('sqlite:' . $DB_FILE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Enable foreign keys
    $pdo->exec('PRAGMA foreign_keys = ON;');

    // Create tables if not exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS Profile (
        profile_id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name TEXT,
        last_name TEXT,
        email TEXT,
        headline TEXT,
        summary TEXT
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS Position (
        position_id INTEGER PRIMARY KEY AUTOINCREMENT,
        profile_id INTEGER,
        rank INTEGER,
        year INTEGER,
        description TEXT,
        FOREIGN KEY(profile_id) REFERENCES Profile(profile_id) ON DELETE CASCADE
    );");

} catch (Exception $e) {
    die('DB Error: ' . $e->getMessage());
}
?>
