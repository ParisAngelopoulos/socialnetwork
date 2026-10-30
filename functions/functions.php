<?php
// Establish Connection to Database
function connect() {
    static $conn;
    if ($conn === NULL){ 
        $conn = mysqli_connect('localhost','root','','socialnetwork');
    }
    return $conn;
}

// Run a query with ? placeholders (prepared statement), so user input never ends up in the SQL itself.
// Returns a result for SELECT, true for INSERT/UPDATE/DELETE, false on an error.
function db_query($sql, $params = []) {
    try {
        $stmt = mysqli_prepare(connect(), $sql);
        if (!$stmt) {
            return false;
        }
        if ($params) {
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        }
        if (!mysqli_stmt_execute($stmt)) {
            return false;
        }
    } catch (mysqli_sql_exception $e) { // PHP 8.1+ throws instead of returning false
        return false;
    }
    $result = mysqli_stmt_get_result($stmt);
    return $result === false ? true : $result;
}

// Escape text before it goes into the HTML, so nobody can inject scripts via a post or a name.
function h($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

?>
