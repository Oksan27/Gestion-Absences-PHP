<?php
include_once 'includes/header.php';

$fichier_etudiants = "data/etudiants.json";
$classes = array();

if (file_exists($fichier_etudiants)) {
    $contenu_json = file_get_contents($fichier_etudiants);
    
    $etudiants = json_decode($contenu_json, true);

    if (is_array($etudiants)) {
        foreach ($etudiants as $etudiant) {
            // Utilisation directe des clés nommées du JSON
            if (isset($etudiant['classe'])) {
                $nom_classe = trim($etudiant['classe']);
                // Éviter les doublons dans notre liste (Chapitre 8)
                if (!in_array($nom_classe, $classes)) {
                    $classes[] = $nom_classe;
                }
            }
        }
    }
}
?>

<style>
    .welcome-card { background: #e9ecef; padding: 20px; border-radius: 6px; margin-bottom: 30px; }
    
    .class-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
    .class-card { background: white; border: 1px solid #dee2e6; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center; }
    .class-card h3 { margin-top: 0; color: #333; }
    .class-card .btn { display: inline-block; background: #28a745; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; margin-top: 10px; }
    .class-card .btn:hover { background: #218838; }
    
    .report-section { margin-top: 40px; }
    .dashboard-card {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #dee2e6;
        border-left: 5px solid #28a745; /* Ligne verte pour le côté statistiques */
        max-width: 600px; /* Aligne proprement la boîte sans l'étirer trop sur grand écran */
    }
    .dashboard-card h3 { margin-top: 0; }

    .btn-dashboard-report {
        display: inline-block;
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        margin-top: 10px;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-dashboard-report:hover {
        background-color: #218838;
        transform: translateY(-1px);
    }
</style>

<div class="welcome-card">
    <h2>Ravi de vous revoir, <?php echo htmlentities($_SESSION['nom_prof']); ?> !</h2>
    <p>Sélectionnez une filière ci-dessous pour effectuer l'appel d'aujourd'hui ou consulter le registre (Format JSON).</p>
</div>

<h3>Vos Classes / Filières :</h3>
<div class="class-grid">
    <?php if (!empty($classes)): ?>
        <?php foreach ($classes as $classe): ?>
            <div class="class-card">
                <h3><?php echo htmlentities($classe); ?></h3>
                <p>Statut : Prêt pour l'appel</p>
                <a href="marquer_absences.php?classe=<?php echo urlencode($classe); ?>" class="btn">Faire l'appel</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune filière trouvée dans la base de données JSON.</p>
    <?php endif; ?>
</div>

<div class="report-section">
    <div class="dashboard-card">
        <h3>📊 Gestion des Rapports</h3>
        <p>Consultez la liste en temps réel de tous les étudiants absents et gérez les statistiques globales.</p>
        <a href="consulter_absences.php" class="btn-dashboard-report">Voir le Rapport d'Absences</a>
    </div>
</div>

</div> </body>
</html>