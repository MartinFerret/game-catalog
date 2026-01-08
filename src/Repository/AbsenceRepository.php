<?php

namespace Repository;

use PDO;

final class AbsenceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM Absence ORDER BY debut DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM Absence WHERE id_absence = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBySalarieId(int $salarieId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM Absence WHERE id_salarie = :salarieId ORDER BY debut DESC');
        $stmt->execute(['salarieId' => $salarieId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO Absence (type, debut, fin, motif, id_salarie) VALUES (:type, :debut, :fin, :motif, :id_salarie)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'type' => $data['type'],
            'debut' => $data['debut'],
            'fin' => $data['fin'] ?? null,
            'motif' => $data['motif'] ?? null,
            'id_salarie' => $data['id_salarie']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE Absence SET type = :type, debut = :debut, fin = :fin, motif = :motif, id_salarie = :id_salarie WHERE id_absence = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'type' => $data['type'],
            'debut' => $data['debut'],
            'fin' => $data['fin'] ?? null,
            'motif' => $data['motif'] ?? null,
            'id_salarie' => $data['id_salarie'],
            'id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM Absence WHERE id_absence = :id');
        return $stmt->execute(['id' => $id]);
    }
}
