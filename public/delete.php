<?php
session_start();
require_once __DIR__ . '/../config/pdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $id_users = $_SESSION['id_users'] ?? '';

    if (empty($id_users)) {
        echo 'User not logged in.';
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id AND id_users = :id_users");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':id_users', $id_users, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error';
}
?>
