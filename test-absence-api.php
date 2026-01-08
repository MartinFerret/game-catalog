<?php

/**
 * Script de test pour le CRUD des absences
 * Usage: php test-absence-api.php
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

echo "=== TEST CRUD ABSENCES ===\n\n";

// 1. Test GET /api/ping
echo "1. Test API Ping...\n";
$result = makeRequest('GET', "$baseUrl/api/ping");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 2. Test GET /api/absences (liste vide au début)
echo "2. Liste des absences (devrait être vide)...\n";
$result = makeRequest('GET', "$baseUrl/api/absences");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 3. Test POST /api/absences (création)
echo "3. Création d'une absence (congé)...\n";
$newAbsence = [
    'type' => 'conge',
    'debut' => '2026-02-01',
    'fin' => '2026-02-05',
    'motif' => 'Vacances d\'hiver',
    'id_salarie' => 1
];
$result = makeRequest('POST', "$baseUrl/api/absences", $newAbsence);
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 4. Test POST /api/absences (création maladie)
echo "4. Création d'une absence (maladie)...\n";
$newAbsence2 = [
    'type' => 'maladie',
    'debut' => '2026-01-15',
    'fin' => '2026-01-17',
    'motif' => 'Grippe',
    'id_salarie' => 2
];
$result = makeRequest('POST', "$baseUrl/api/absences", $newAbsence2);
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 5. Test POST avec données invalides (type invalide)
echo "5. Test création avec type invalide...\n";
$invalidAbsence = [
    'type' => 'vacances', // invalide
    'debut' => '2026-03-01',
    'id_salarie' => 1
];
$result = makeRequest('POST', "$baseUrl/api/absences", $invalidAbsence);
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 6. Test POST avec champs manquants
echo "6. Test création avec champs manquants...\n";
$incompleteAbsence = [
    'type' => 'conge',
    // manque debut et id_salarie
];
$result = makeRequest('POST', "$baseUrl/api/absences", $incompleteAbsence);
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 7. Test GET /api/absences (liste après créations)
echo "7. Liste des absences (après créations)...\n";
$result = makeRequest('GET', "$baseUrl/api/absences");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// Récupérer l'ID de la première absence créée
$result = makeRequest('GET', "$baseUrl/api/absences");
$firstAbsenceId = $result['body']['data'][0]['id_absence'] ?? null;
$secondAbsenceId = $result['body']['data'][1]['id_absence'] ?? null;

// 8. Test GET /api/absences/{id} (une absence spécifique)
echo "8. Récupération de l'absence ID $firstAbsenceId...\n";
$result = makeRequest('GET', "$baseUrl/api/absences/$firstAbsenceId");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 9. Test PUT /api/absences/{id} (modification)
echo "9. Modification de l'absence ID $firstAbsenceId...\n";
$updateData = [
    'fin' => '2026-02-10', // prolongation
    'motif' => 'Vacances d\'hiver prolongées',
];
$result = makeRequest('PUT', "$baseUrl/api/absences/$firstAbsenceId", $updateData);
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 10. Test GET /api/absences/{id} (vérifier la modification)
echo "10. Vérification de la modification...\n";
$result = makeRequest('GET', "$baseUrl/api/absences/$firstAbsenceId");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 11. Test GET /api/salaries/1/absences (absences d'un salarié)
echo "11. Absences du salarié ID 1...\n";
$result = makeRequest('GET', "$baseUrl/api/salaries/1/absences");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 12. Test DELETE /api/absences/999 (absence inexistante)
echo "12. Suppression d'une absence inexistante (ID 999)...\n";
$result = makeRequest('DELETE', "$baseUrl/api/absences/999");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 13. Test DELETE /api/absences/{id} (suppression)
echo "13. Suppression de l'absence ID $secondAbsenceId...\n";
$result = makeRequest('DELETE', "$baseUrl/api/absences/$secondAbsenceId");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

// 14. Test GET /api/absences/{id} (vérifier la suppression)
echo "14. Vérification de la suppression (devrait retourner 404)...\n";
$result = makeRequest('GET', "$baseUrl/api/absences/$secondAbsenceId");
echo "Status: {$result['code']}\n";
echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTS TERMINÉS ===\n";
