-- Création de la base de données
USE datatime;

-- Table Salarie (nécessaire pour la foreign key)
CREATE TABLE IF NOT EXISTS Salarie (
  id_salarie INT AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  PRIMARY KEY(id_salarie)
);

-- Table Absence
CREATE TABLE IF NOT EXISTS Absence (
  id_absence INT AUTO_INCREMENT,
  type ENUM('conge', 'maladie') NOT NULL,
  debut DATE NOT NULL,
  fin DATE,
  motif VARCHAR(50),
  id_salarie INT NOT NULL,
  PRIMARY KEY(id_absence),
  FOREIGN KEY(id_salarie) REFERENCES Salarie(id_salarie)
);

-- Insertion de quelques salariés pour les tests
INSERT INTO Salarie (nom, prenom, email) VALUES 
('Dupont', 'Jean', 'jean.dupont@example.com'),
('Martin', 'Marie', 'marie.martin@example.com'),
('Bernard', 'Paul', 'paul.bernard@example.com');
