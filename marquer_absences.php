<?php
include_once 'includes/header.php';

if (!isset($_GET['classe']) || empty($_GET['classe'])) {
    header('Location: dashboard.php');
    exit();
}

$classe_selectionnee = trim(urldecode($_GET['classe']));
$fichier_etudiants = "data/etudiants.json";
$etudiants_classe = array();

if (file_exists($fichier_etudiants)) {
    $contenu_json = file_get_contents($fichier_etudiants);
    $etudiants = json_decode($contenu_json, true);
    
    if (is_array($etudiants)) {
        foreach ($etudiants as $etudiant) {
            if (isset($etudiant['classe'])) {
                $classe_etudiant = trim($etudiant['classe']);
                
                // Comparaison robuste (stricte ou via nettoyage des caractères spéciaux)
                if ($classe_etudiant === $classe_selectionnee || 
                    slugify_simple($classe_etudiant) === slugify_simple($classe_selectionnee)) {
                    
                    $etudiants_classe[] = $etudiant;
                }
            }
        }
    }
}

function slugify_simple($text) {
    $search  = array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'û', 'ù');
    $replace = array('e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u');
    $text = str_replace($search, $replace, $text);
    
    $text = preg_replace('/[^A-Za-z0-9]/', '', $text); 
    return strtolower($text);
}
?>

<style>
    .appel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .btn-back { background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; }
    .btn-back:hover { background-color: #5a6268; }
    
    table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #dee2e6; }
    th { background-color: #007bff; color: white; font-weight: bold; }
    tr:hover { background-color: #f8f9fa; }
    
    .radio-group { display: flex; gap: 15px; }
    .radio-label { display: flex; align-items: center; gap: 5px; cursor: pointer; font-weight: normal; }
    .input-motif { width: 90%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
    
    .btn-submit { background-color: #28a745; color: white; border: none; padding: 12px 25px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; float: right; margin-top: 20px; }
    .btn-submit:hover { background-color: #218838; }
</style>

<div class="appel-header">
    <h2>Feuille d'appel - <?php echo htmlentities($classe_selectionnee); ?></h2>
    <a href="dashboard.php" class="btn-back">⬅ Retour</a>
</div>

<p>Date du jour : <strong><?php echo date('d/m/Y'); ?></strong></p>

<form action="sauvegarder.php" method="POST">
    <input type="hidden" name="classe" value="<?php echo htmlentities($classe_selectionnee); ?>">

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 25%;">Nom</th>
                <th style="width: 25%;">Prénom</th>
                <th style="width: 20%;">Statut</th>
                <th style="width: 20%;">Motif (Si Absent/Retard)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($etudiants_classe)): ?>
                <?php foreach ($etudiants_classe as $etudiant): ?>
                    <tr>
                        <td><?php echo htmlentities($etudiant['id']); ?></td>
                        <td><strong><?php echo htmlentities($etudiant['nom']); ?></strong></td>
                        <td><?php echo htmlentities($etudiant['prenom']); ?></td>
                        <td>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="statut[<?php echo $etudiant['id']; ?>]" value="Present" checked> Présent
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="statut[<?php echo $etudiant['id']; ?>]" value="Absent"> Absent
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="statut[<?php echo $etudiant['id']; ?>]" value="Retard"> Retard
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="motif[<?php echo $etudiant['id']; ?>]" class="input-motif" placeholder="Ex: Retard train...">
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #666; padding: 30px;">Aucun étudiant inscrit dans cette filière.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

<?php if (!empty($etudiants_classe)): ?>
    <button type="submit" name="enregistrer_appel" class="btn-submit">💾 Enregistrer l'appel (JSON)</button>
<?php endif; ?>
</form>

</div> </body>
</html>