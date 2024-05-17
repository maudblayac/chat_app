<?php
session_start();
require_once __DIR__.'/../config/pdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération de l'mail et du mot de passe depuis le formulaire
    $pseudo = $_POST['pseudo'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    // Requête SQL pour sélectionner l'utilisateur correspondant à l'mail fourni
    $stmt = $pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo AND mdp=:mdp" );
    $stmt->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
    $stmt->bindValue(":mdp", $mdp, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();

    
    if (!empty($result)){
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['isLoggedIn'] = true;
        header('Location: /../public/chat.php');
        exit;

    }
    else {
        $error = "Identifiants incorrects!";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-200 flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-xl mb-4">Connexion</h1>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="pseudo">Nom d'utilisateur</label>
                    <input type="text" id="pseudo" name="pseudo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Mot de passe</label>
                    <input type="password" id="mdp" name="mdp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <?php if (!empty($error)): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $error; ?></p>
                <?php endif; ?>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>