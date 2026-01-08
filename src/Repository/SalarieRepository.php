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
        $stmt->bindValue('id',$id,PDO::PARAM_INT);
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
    

}