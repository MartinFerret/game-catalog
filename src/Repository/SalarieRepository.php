<?php
namespace Repository;
use PDO;

readonly final class SalarieRepository
{
    public function __construct(private PDO $pdo)
    {
    }
    public function findAll(): array
    {
        $sql = $this->pdo->query('SELECT * FROM Salarie');
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM Salarie WHERE id_salarie = :id');
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $salarie = $stmt->fetch(PDO::FETCH_ASSOC);
        return $salarie === false ? null : $salarie;
    }
    public function insert(array $data): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO Salarie (nom, prenom, poste, contrat, taux_journalier_moyen, role) 
        VALUES (:nom, :prenom, :poste, :contrat, :taux_journalier_moyen, :role)');
        $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'poste' => $data['poste'],
            'contrat' => $data['contrat'],
            'taux_journalier_moyen' => $data['taux_journalier_moyen'],
            'role' => $data['role']
        ]);
        return $this->pdo->lastInsertId();
    }
    public function update(int $id, array $data): bool
{
    $fields = [];
    $params = ['id' => $id];
    if(empty($data)) {
        return false;
    }

    

    foreach ($data as $field => $value) {
        if ($field === 'role') {
            $fields[] = "`role` = :role";
        } else {
            $fields[] = "$field = :$field";
        }
        $params[$field] = $value;
    }

    if (empty($fields)) {
        return false;
    }

    $sql = sprintf(
        'UPDATE Salarie SET %s WHERE id_salarie = :id',
        implode(', ', $fields)
    );

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->rowCount() > 0;
}


public function deleteById(int $id): void
{
    $stmt = $this->pdo->prepare('DELETE FROM Salarie WHERE id_salarie = :id');
    $stmt->execute(['id' => $id]);
}





}