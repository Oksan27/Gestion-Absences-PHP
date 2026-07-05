<?php
$file_path = 'data/absences.json';   
$students_file = 'data/etudiants.json'; // Vu tes onglets, il est à la racine de ton projet

$absence_records = [];
$students_lookup = [];

if (file_exists($students_file)) {
    $students_data = json_decode(file_get_contents($students_file), true) ?? [];
    foreach ($students_data as $student) {
        if (isset($student['id'])) {
            $students_lookup[$student['id']] = ($student['nom'] ?? '') . ' ' . ($student['prenom'] ?? '');
        }
    }
}

if (file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $absence_records = json_decode($json_data, true) ?? [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Registre des Absences</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #0056b3; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .badge-absent { color: #d9534f; background-color: #fdf7f7; padding: 5px 10px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>

    <h2>📋 Registre des Étudiants Absents</h2>
    <p>Ce tableau affiche l'historique des personnes absentes récupéré depuis le fichier JSON.</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Nom de l'Étudiant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <tbody>
            <?php 
            $compteur_absences = 0;
            
            if (!empty($absence_records)): 
                foreach ($absence_records as $record): 
                    // strtolower() permet d'accepter "absent" ET "Absent" sans faire de jaloux
                    if (isset($record['statut']) && strtolower($record['statut']) === 'absent'): 
                        $compteur_absences++;
                        
                        // Correspondance exacte avec ta clé "id_etudiant"
                        $student_id = $record['id_etudiant'] ?? '';
                        $nom_complet = $students_lookup[$student_id] ?? "Étudiant ID: " . htmlspecialchars($student_id);
            ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['date'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($nom_complet); ?></td>
                            <td><span class="badge-absent">❌ Absent</span></td>
                        </tr>
            <?php 
                    endif;
                endforeach; 
            endif; 

            if ($compteur_absences === 0): 
            ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #777;">Aucune absence enregistrée pour le moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
            
          
    </table>

    <p><strong>Total des absences constatées :</strong> <?php echo $compteur_absences; ?></p>

</body>
</html>