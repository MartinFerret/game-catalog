<?php
namespace Controller;
use Core\Response;
use Core\Request;

use Repository\SalarieRepository;
use Core\Session;

final class SalarieApiController
{
    public function __construct(
        private Response $response,
        private SalarieRepository $salarieRepository,
        private Session $session,
        private Request $request
    ) {
    }

    public function salaries(Request $request, Response $response): void
    {
        $salaries = $this->salarieRepository->findAll();
        $this->response->json($salaries);
    }
    public function salarieById(Request $request, Response $response, int $id): void
    {
        $salarie = $this->salarieRepository->findById($id);
        if ($salarie === null) {
            $this->response->json(['error' => 'Salarie not found'], 404);
            return;
        }
        $this->response->json($salarie);
    }
    public function createSalarie(): void
    {
        $nom = trim((string) $this->request->post('nom'));
        $prenom = trim((string) $this->request->post('prenom'));
        $poste = trim((string) $this->request->post('poste'));
        $contrat = trim((string) $this->request->post('contrat'));

        // DECIMAL(15,2) : on garde une string normalisée (pas int)
        $tjmRaw = trim((string) $this->request->post('taux_journalier_moyen'));
        $tjmNorm = str_replace(',', '.', $tjmRaw);

        $roleRaw = trim((string) $this->request->post('role'));

        $errors = [];

        if ($nom === '')
            $errors['nom'] = 'nom should not be empty';
        if ($prenom === '')
            $errors['prenom'] = 'prenom should not be empty';
        if ($poste === '')
            $errors['poste'] = 'poste should not be empty';
        if ($contrat === '')
            $errors['contrat'] = 'contrat should not be empty';

        // Validation DECIMAL(15,2) => max 13 chiffres avant, 2 après
        if ($tjmNorm === '') {
            $errors['taux_journalier_moyen'] = 'taux_journalier_moyen should not be empty';
        } elseif (!preg_match('/^\d{1,13}(?:\.\d{1,2})?$/', $tjmNorm)) {
            $errors['taux_journalier_moyen'] = 'taux_journalier_moyen must be DECIMAL(15,2) (e.g. 450.50)';
        }

        // role entier
        if ($roleRaw === '') {
            $errors['role'] = 'role should not be empty';
        }

        if (!empty($errors)) {
            $this->response->json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
            return;
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'poste' => $poste,
            'contrat' => $contrat,
            'taux_journalier_moyen' => $tjmNorm, // string "450.50"
            'role' => $roleRaw,
        ];

        $newSalarieId = $this->salarieRepository->insert($data);

        $this->response->json([
            'message' => 'Salarie created',
            'data' => [
                'id_salarie' => (int) $newSalarieId,
                ...$data,
            ],
        ], 201);
    }

}
