-- Fix pour la table Absence
USE renard_data_time;

-- Supprimer la contrainte de clé étrangère temporairement
ALTER TABLE Absence DROP FOREIGN KEY IF EXISTS Absence_ibfk_1;

-- Modifier la colonne id_absence pour ajouter AUTO_INCREMENT
ALTER TABLE Absence MODIFY COLUMN id_absence INT AUTO_INCREMENT;

-- Remettre la contrainte de clé étrangère
ALTER TABLE Absence ADD CONSTRAINT Absence_ibfk_1 
FOREIGN KEY (id_salarie) REFERENCES Salarie(id_salarie);

-- Réinitialiser l'auto-increment à 1
ALTER TABLE Absence AUTO_INCREMENT = 1;
