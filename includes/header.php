<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['autoriser']) || $_SESSION['autoriser'] !== 'oui') {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        .navbar {
            background-color: #343a40;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar .brand {
            font-size: 20px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }
        .navbar .user-info {
            font-size: 16px;
        }
        .navbar .logout-btn {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 15px;
            font-size: 14px;
            transition: background 0.2s;
        }
        .navbar .logout-btn:hover {
            background-color: #bd2130;
        }
        .main-content {
            padding: 40px 30px;
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="dashboard.php" class="brand">🎓 ENSA Fès - Gestion des Absences</a>
    <div class="user-info">
        Enseignant : <strong><?php echo htmlentities($_SESSION['nom_prof']); ?></strong>
        <a href="deconnexion.php" class="logout-btn">Déconnexion</a>
    </div>
</div>

<div class="main-content"></div>