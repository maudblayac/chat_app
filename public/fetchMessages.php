<?php
require_once __DIR__ . '/../config/pdo.php';

try {
    $stmt = $pdo->query("SELECT messages.id, messages.message, messages.create_at, users.pseudo FROM messages JOIN users ON messages.id_users = users.id ORDER BY create_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $messages = [];
    echo 'Error: ' . $e->getMessage();
}
?>
