<?php
session_start();
require_once __DIR__ . '/../config/pdo.php';

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'] ?? '';
    $create_at = date('Y-m-d');
    $id_users = $_POST['id_users'] ?? '';

    if (!empty($message)) {
   
            $sql = "INSERT INTO messages (message, create_at, id_users) VALUES (:message, :create_at, :id_users)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":message", $message, PDO::PARAM_STR);
            $stmt->bindValue(":create_at", $create_at, PDO::PARAM_STR);
            $stmt->bindValue(":id_users", $id_users, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['message'] = $message;
            $_SESSION['isLoggedIn'] = true;
            header('Location: chat.php');
            exit;
        
    } else {
        $error_message = 'Le commentaire ne peut pas être vide.';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chat</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <div class="chat-container">
           
            <div class="chat-header">
                <h2>Chat Room</h2>
                <button class="delete-button" onclick="window.location.href='../security/logout.php'">Log out</button>

       
            <!-- Si l'utilisateur est connecté, afficher le bouton de déconnexion -->
            
            
            </div>
            <div class="chat-messages" id="chat-messages">
                <!-- Les messages apparaîtront ici -->
            
                <!-- Start Message -->
                <!-- Ci-dessous un exemple de structure HTML & CSS d'un message -->
                <div class="message">
                    <span name="message"></span>   
                    <button class="delete-button" name="submit">Delete</button>
                </div>
                <!-- End Message -->

            </div>
            <div class="chat-input">
                <!-- Le formulaire pour envoyer des messages doit se trouver ci-dessous -->
                <form method="POST" class="mb-4">
                <textarea class="chat-messages" id="message" name="message" placeholder="Type a message..." required></textarea>
                <button class="chat-input" type="submit">Send</button>
            </form>
            </div>
        </div>
    </body>
</html>


 