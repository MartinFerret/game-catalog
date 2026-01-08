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
    public function updateSalarie(Request $request, Response $response, int $id): void
    {



        // 1) Vérifier que la ressource existe
        $existing = $this->salarieRepository->findById($id);
        if ($existing === null) {
            $response->json(['error' => 'Salarie not found'], 404);
            return;
        }

        // 2) Construire $data uniquement avec les champs fournis
        $data = [];
        $errors = [];

        // Helpers: on considère "non fourni" si string vide (car Request::post retourne '' si absent)
        $nom = trim((string) $request->post('nom'));
        if ($nom !== '') {
            $data['nom'] = $nom;
        }

        $prenom = trim((string) $request->post('prenom'));
        if ($prenom !== '') {
            $data['prenom'] = $prenom;
        }

        $poste = trim((string) $request->post('poste'));
        if ($poste !== '') {
            $data['poste'] = $poste;
        }

        $contrat = trim((string) $request->post('contrat'));
        if ($contrat !== '') {
            $data['contrat'] = $contrat;
        }

        // DECIMAL(15,2)
        $tjmRaw = trim((string) $request->post('taux_journalier_moyen'));
        if ($tjmRaw !== '') {
            $tjmNorm = str_replace(',', '.', $tjmRaw);

            if (!preg_match('/^\d{1,13}(?:\.\d{1,2})?$/', $tjmNorm)) {
                $errors['taux_journalier_moyen'] = 'taux_journalier_moyen must be DECIMAL(15,2) (e.g. 450.50)';
            } else {
                $data['taux_journalier_moyen'] = $tjmNorm; // string "450.50"
            }
        }

        // role int (>=0)
        $roleRaw = trim((string) $request->post('role'));
        if ($roleRaw !== '') {
            $data['role'] = $roleRaw;

        }

        // ✅ 3) Validation globale
        if (!empty($errors)) {
            $response->json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
            return;
        }

        if (empty($data)) {
            $response->json([
                'message' => 'No data to update'
            ], 400);
            return;
        }


        // 4) Update
        $ok = $this->salarieRepository->update($id, $data);

        if (!$ok) {
            // update() retourne false si rien à faire, mais on a déjà filtré empty($data)
            $response->json(['message' => 'Update failed'], 500);
            return;
        }

        // 5) Retourner la ressource mise à jour (optionnel mais pratique)
        $updated = $this->salarieRepository->findById($id);

        $response->json([
            'message' => 'Salarie updated',
            'data' => $updated ?? array_merge($existing, $data),
        ], 200);


    }
    public function deleteSalarie(Request $request, Response $response, int $id): void
    {
        // $response->json([
        //     'hit' => 'deleteSalarie',
        //     'method' => $request->method(),
        //     'path' => $request->path()
        // ], 418);
        // return;

        $existing = $this->salarieRepository->findById($id);
        if ($existing === null) {
            $response->json(['error' => 'Salarie not found'], 404);
            return;
        }

        $this->salarieRepository->deleteById($id);

        // 204 = No Content (propre en REST)
        http_response_code(204);
    }


}
