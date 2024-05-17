<?php
session_start();
require_once __DIR__ . '/../../config/pdo.php';

if (!empty($_SESSION['isLoggedIn']) && $_SERVER['REQUEST_METHOD'] === "GET" && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM messages WHERE id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetch();

    if (!empty($messages) && $_SESSION['id'] === $messages['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } 

    header("Location: /chat.php");
    exit();
} else {
    header("Location: /chat.php");
}