<?php
session_start();

if (isset($_SESSION['autoriser']) && $_SESSION['autoriser'] === 'oui') {
    header('Location: dashboard.php');
    exit();
}

$erreur = "";

if (isset($_POST['valider'])) {
    $login_saisi = trim($_POST['login']);
    $password_saisi = trim($_POST['password']);

    if (empty($login_saisi) || empty($password_saisi)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $fichier_auth = "data/utilisateurs.txt";

        if (file_exists($fichier_auth)) {
            $lignes = file($fichier_auth, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $authentification_reussie = false;

            foreach ($lignes as $ligne) {
                $donnees = explode('|', $ligne);
                
                if (count($donnees) >= 3) {
                    $login_txt = $donnees[0];
                    $password_txt = $donnees[1];
                    $nom_prof = $donnees[2];

                    if ($login_saisi === $login_txt && $password_saisi === $password_txt) {
                        $authentification_reussie = true;
                        
                        $_SESSION['autoriser'] = 'oui';
                        $_SESSION['nom_prof'] = $nom_prof;
                        break;
                    }
                }
            }

            if ($authentification_reussie) {
                header('Location: dashboard.php');
                exit();
            } else {
                $erreur = "Identifiants ou mot de passe incorrects.";
            }
        } else {
            $erreur = "Erreur système : Fichier de configuration introuvable.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Gestion des Absences</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 380px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; border: none; color: white; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #0056b3; }
        .error-msg { color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Portail Enseignant</h2>
    
    <!-- Affichage de l'erreur si elle existe -->
    <?php if (!empty($erreur)): ?>
        <div class="error-msg"><?php echo htmlentities($erreur); ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="login">Identifiant (Login)</label>
            <input type="text" id="login" name="login" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" name="valider">Se connecter</button>
    </form>
</div>

</body>
</html>