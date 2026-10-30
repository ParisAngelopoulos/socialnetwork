<?php
// The database is a SQLite file in private/ (not reachable from the web). The first time it is
// missing, it is created from private/database.sql with the example data, so no setup is needed.
function connect() {
    static $conn;
    if ($conn === NULL){
        $file = __DIR__ . '/../private/database.sqlite';
        $new = !is_file($file);
        $conn = new PDO('sqlite:' . $file, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $conn->exec('PRAGMA foreign_keys = ON');
        if ($new) {
            $conn->exec(file_get_contents(__DIR__ . '/../private/database.sql'));
        }
    }
    return $conn;
}

// The rows of a SELECT, read one by one with db_fetch() (like mysqli_fetch_assoc did)
class DbResult {
    public $rows;
    public $pos = 0;
    public function __construct($rows) { $this->rows = $rows; }
}

// Run a query with ? placeholders (prepared statement), so user input never ends up in the SQL itself.
// Returns a DbResult for SELECT, true for INSERT/UPDATE/DELETE, false on an error.
function db_query($sql, $params = []) {
    try {
        $stmt = connect()->prepare($sql);
        $stmt->execute(array_values($params));
    } catch (PDOException $e) {
        db_error($e->getMessage());
        return false;
    }
    return $stmt->columnCount() > 0 ? new DbResult($stmt->fetchAll()) : true;
}

function db_fetch($result) {
    if (!$result instanceof DbResult || $result->pos >= count($result->rows)) {
        return null;
    }
    return $result->rows[$result->pos++];
}

function db_count($result) {
    return $result instanceof DbResult ? count($result->rows) : 0;
}

function db_rewind($result) {
    if ($result instanceof DbResult) {
        $result->pos = 0;
    }
}

function db_insert_id() {
    return (int)connect()->lastInsertId();
}

// The last database error (set by db_query), to show when a query fails
function db_error($message = null) {
    static $last = '';
    if ($message !== null) {
        $last = $message;
    }
    return $last;
}

// Escape text before it goes into the HTML, so nobody can inject scripts via a post or a name.
function h($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

?>
