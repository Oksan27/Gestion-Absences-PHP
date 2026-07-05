<?php
include_once 'includes/header.php';

if (isset($_POST['enregistrer_appel'])) {
    
    $classe = isset($_POST['classe']) ? trim($_POST['classe']) : 'Inconnue';
    $statuts = isset($_POST['statut']) ? $_POST['statut'] : array();
    $motifs = isset($_POST['motif']) ? $_POST['motif'] : array();
    
    $fichier_absences = "data/absences.json";
    $date_aujourdhui = date('Y-m-d H:i');

    $absences_existantes = array();
    if (file_exists($fichier_absences)) {
        $contenu_actuel = file_get_contents($fichier_absences);
        $absences_existantes = json_decode($contenu_actuel, true);
        if (!is_array($absences_existantes)) {
            $absences_existantes = array();
        }
    }

    foreach ($statuts as $id_etudiant => $statut) {
        if ($statut === 'Absent' || $statut === 'Retard') {
            
            $motif = isset($motifs[$id_etudiant]) ? trim($motifs[$id_etudiant]) : '';
            if (empty($motif)) {
                $motif = "Aucun motif fourni";
            }
            $motif = strip_tags($motif);

            $nouvelle_absence = array(
                "date" => $date_aujourdhui,
                "id_etudiant" => $id_etudiant,
                "statut" => $statut,
                "motif" => $motif
            );
            
            $absences_existantes[] = $nouvelle_absence;
        }
    }

    $json_final = json_encode($absences_existantes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($fichier_absences, $json_final) !== false) {
        ?>
        <div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
            <div style="color: #28a745; font-size: 48px; margin-bottom: 20px;">✓</div>
            <h2 style="color: #333;">L'appel pour la classe <?php echo htmlentities($classe); ?> a été enregistré !</h2>
            <p style="color: #666;">Les données ont été correctement sauvegardées dans le registre structuré <code>data/absences.json</code>.</p>
            <p>Redirection vers votre tableau de bord en cours...</p>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'dashboard.php';
            }, 3000);
        </script>
        <?php
    } else {
        echo "<div style='color: red; padding: 20px;'>Erreur système : Impossible d'écrire dans le fichier JSON.</div>";
        echo "<br><a href='dashboard.php'>Retour au tableau de bord</a>";
    }
} else {
    header('Location: dashboard.php');
    exit();
}
?>