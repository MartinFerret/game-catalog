<?php

/**
 * Script pour remplir la base de données avec des absences de test
 */

$baseUrl = 'http://localhost:8000';

function makeRequest($method, $url, $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

echo "=== REMPLISSAGE DE LA BDD AVEC DES ABSENCES ===\n\n";

// Données de test variées
$absences = [
    // Congés
    [
        'type' => 'conge',
        'debut' => '2026-01-20',
        'fin' => '2026-01-24',
        'motif' => 'Vacances scolaires',
        'id_salarie' => 1
    ],
    [
        'type' => 'conge',
        'debut' => '2026-02-10',
        'fin' => '2026-02-14',
        'motif' => 'Vacances d\'hiver',
        'id_salarie' => 2
    ],
    [
        'type' => 'conge',
        'debut' => '2026-03-15',
        'fin' => '2026-03-22',
        'motif' => 'Voyage en famille',
        'id_salarie' => 3
    ],
    [
        'type' => 'conge',
        'debut' => '2026-04-06',
        'fin' => '2026-04-17',
        'motif' => 'Vacances de Pâques',
        'id_salarie' => 1
    ],
    [
        'type' => 'conge',
        'debut' => '2026-05-01',
        'fin' => '2026-05-03',
        'motif' => 'Pont du 1er mai',
        'id_salarie' => 2
    ],
    [
        'type' => 'conge',
        'debut' => '2026-07-01',
        'fin' => '2026-07-31',
        'motif' => 'Congés d\'été',
        'id_salarie' => 3
    ],
    [
        'type' => 'conge',
        'debut' => '2026-08-03',
        'fin' => '2026-08-21',
        'motif' => 'Vacances estivales',
        'id_salarie' => 1
    ],
    [
        'type' => 'conge',
        'debut' => '2026-12-21',
        'fin' => '2026-12-31',
        'motif' => 'Vacances de Noël',
        'id_salarie' => 2
    ],
    
    // Maladies
    [
        'type' => 'maladie',
        'debut' => '2026-01-10',
        'fin' => '2026-01-12',
        'motif' => 'Grippe',
        'id_salarie' => 1
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-01-15',
        'fin' => '2026-01-15',
        'motif' => 'Gastro-entérite',
        'id_salarie' => 3
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-02-05',
        'fin' => '2026-02-07',
        'motif' => 'Angine',
        'id_salarie' => 2
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-03-10',
        'fin' => '2026-03-11',
        'motif' => 'Migraine sévère',
        'id_salarie' => 1
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-04-20',
        'fin' => null, // absence en cours
        'motif' => 'Bronchite',
        'id_salarie' => 3
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-05-12',
        'fin' => '2026-05-14',
        'motif' => 'Rhume',
        'id_salarie' => 2
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-06-08',
        'fin' => '2026-06-09',
        'motif' => 'Mal de dos',
        'id_salarie' => 1
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-09-15',
        'fin' => '2026-09-16',
        'motif' => 'Fièvre',
        'id_salarie' => 3
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-10-22',
        'fin' => '2026-10-25',
        'motif' => 'Grippe saisonnière',
        'id_salarie' => 2
    ],
    [
        'type' => 'maladie',
        'debut' => '2026-11-05',
        'fin' => '2026-11-06',
        'motif' => 'Allergie',
        'id_salarie' => 1
    ],
    
    // Absences courtes
    [
        'type' => 'conge',
        'debut' => '2026-06-15',
        'fin' => '2026-06-15',
        'motif' => 'Rendez-vous médical',
        'id_salarie' => 2
    ],
    [
        'type' => 'conge',
        'debut' => '2026-09-01',
        'fin' => '2026-09-03',
        'motif' => 'Week-end prolongé',
        'id_salarie' => 3
    ],
];

$success = 0;
$failed = 0;

foreach ($absences as $index => $absence) {
    $num = $index + 1;
    echo "[$num/" . count($absences) . "] Création absence : {$absence['type']} - {$absence['debut']} ";
    
    $result = makeRequest('POST', "$baseUrl/api/absences", $absence);
    
    if ($result['code'] === 201 || $result['code'] === 200) {
        echo "✓ OK\n";
        $success++;
    } else {
        echo "✗ ERREUR (Code {$result['code']})\n";
        if (isset($result['body']['message'])) {
            echo "  Message: {$result['body']['message']}\n";
        }
        $failed++;
    }
    
    // Pause pour ne pas surcharger le serveur
    usleep(100000); // 100ms
}

echo "\n=== RÉSUMÉ ===\n";
echo "Total: " . count($absences) . "\n";
echo "Succès: $success ✓\n";
echo "Échecs: $failed ✗\n";

// Afficher les statistiques
echo "\n=== STATISTIQUES ===\n";
$result = makeRequest('GET', "$baseUrl/api/absences");
if ($result['code'] === 200 && isset($result['body']['data'])) {
    $total = count($result['body']['data']);
    $conges = count(array_filter($result['body']['data'], fn($a) => $a['type'] === 'conge'));
    $maladies = count(array_filter($result['body']['data'], fn($a) => $a['type'] === 'maladie'));
    
    echo "Total d'absences en BDD: $total\n";
    echo "  - Congés: $conges\n";
    echo "  - Maladies: $maladies\n";
}

echo "\n✓ Remplissage terminé !\n";
