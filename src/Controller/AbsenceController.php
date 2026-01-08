<?php

namespace Controller;

use Core\Request;
use Core\Response;
use PDO;

final class AbsenceController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(Request $request, Response $response): void
    {
        $repository = new \Repository\AbsenceRepository($this->pdo);
        $absences = $repository->findAll();
        $response->json(['success' => true, 'data' => $absences]);
    }

    public function show(Request $request, Response $response, int $id): void
    {
        $repository = new \Repository\AbsenceRepository($this->pdo);
        $absence = $repository->findById($id);
        
        if (!$absence) {
            $response->json(['success' => false, 'message' => 'Absence not found'], 404);
            return;
        }
        
        $response->json(['success' => true, 'data' => $absence]);
    }

    public function store(Request $request, Response $response): void
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        
        $required = ['type', 'debut', 'id_salarie'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $response->json(['success' => false, 'message' => "Missing required field: $field"], 400);
                return;
            }
        }
        
        $validTypes = ['conge', 'maladie'];
        if (!in_array($data['type'], $validTypes)) {
            $response->json(['success' => false, 'message' => 'Invalid type. Must be conge or maladie'], 400);
            return;
        }
        
        $repository = new \Repository\AbsenceRepository($this->pdo);
        $success = $repository->create($data);
        
        if ($success) {
            $response->json(['success' => true, 'message' => 'Absence created'], 201);
        } else {
            $response->json(['success' => false, 'message' => 'Failed to create absence'], 500);
        }
    }

    public function update(Request $request, Response $response, int $id): void
    {
        $repository = new \Repository\AbsenceRepository($this->pdo);
        $absence = $repository->findById($id);
        
        if (!$absence) {
            $response->json(['success' => false, 'message' => 'Absence not found'], 404);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        
        if (!empty($data['type'])) {
            $validTypes = ['conge', 'maladie'];
            if (!in_array($data['type'], $validTypes)) {
                $response->json(['success' => false, 'message' => 'Invalid type. Must be conge or maladie'], 400);
                return;
            }
        }
        
        // Merge with existing data
        $updateData = array_merge($absence, $data);
        
        $success = $repository->update($id, $updateData);
        
        if ($success) {
            $response->json(['success' => true, 'message' => 'Absence updated']);
        } else {
            $response->json(['success' => false, 'message' => 'Failed to update absence'], 500);
        }
    }

    public function delete(Request $request, Response $response, int $id): void
    {
        $repository = new \Repository\AbsenceRepository($this->pdo);
        $absence = $repository->findById($id);
        
        if (!$absence) {
            $response->json(['success' => false, 'message' => 'Absence not found'], 404);
            return;
        }
        
        $success = $repository->delete($id);
        
        if ($success) {
            $response->json(['success' => true, 'message' => 'Absence deleted']);
        } else {
            $response->json(['success' => false, 'message' => 'Failed to delete absence'], 500);
        }
    }

    public function findBySalarie(Request $request, Response $response, int $salarieId): void
    {
        $repository = new \Repository\AbsenceRepository($this->pdo);
        $absences = $repository->findBySalarieId($salarieId);
        $response->json(['success' => true, 'data' => $absences]);
    }
}
