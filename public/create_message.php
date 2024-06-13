<?php
session_start();
require_once __DIR__ . '/../config/pdo.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'] ?? '';
    $create_at = date('Y-m-d');
    $id_users = $_SESSION['id_users'] ?? '';

    if (empty($id_users)) {
        $error_message = 'User not logged in.';
    } elseif (!empty($message)) {
        try {
            $sql = "INSERT INTO messages (message, create_at, id_users) VALUES (:message, :create_at, :id_users)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":message", $message, PDO::PARAM_STR);
            $stmt->bindValue(":create_at", $create_at, PDO::PARAM_STR);
            $stmt->bindValue(":id_users", $id_users, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: chat.php');
            exit;
        } catch (PDOException $e) {
            $error_message = 'Error: ' . $e->getMessage();
        }
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
        </div>
        <div class="chat-messages" id="chat-messages">
            <!-- Fetch and display messages from the database -->
            <?php
            try {
                $stmt = $pdo->query("SELECT messages.id, messages.message, messages.create_at, users.pseudo FROM messages JOIN users ON messages.id_users = users.id ORDER BY create_at DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="message">';
                    echo '<span>' . htmlspecialchars($row['pseudo']) . ': ' . htmlspecialchars($row['message']) . '</span>';
                    echo '<button class="delete-button" onclick="deleteMessage(' . $row['id'] . ')">Delete</button>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
            ?>
        </div>
        <div class="chat-input">
            <form method="POST" class="mb-4">
                <textarea class="chat-messages" id="message" name="message" placeholder="Type a message..." required></textarea>
                <button class="chat-input" type="submit">Send</button>
            </form>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function deleteMessage(id) {
            if (confirm('Are you sure you want to delete this message?')) {
                fetch('delete_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert('Error deleting message.');
                    }
                });
            }
        }
    </script>
</body>
</html>
