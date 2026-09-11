USE mydb;

INSERT INTO utilisateurs (nom, prenom, courriel, mot_de_passe, ROLE, date_creation) VALUES
('Tremblay', 'Jean', 'jean.tremblay@example.com', 'motdepasse_hache_1', 'MEMBRE', '2026-01-10 10:00:00'),
('Gagnon', 'Marie', 'marie.gagnon@example.com', 'motdepasse_hache_2', 'MEMBRE', '2026-01-12 11:30:00'),
('Roy', 'Martin', 'martin.roy@example.com', 'motdepasse_hache_3', 'COACH', '2026-01-15 09:15:00'),
('Côté', 'Julie', 'julie.cote@example.com', 'motdepasse_hache_4', 'ADMIN', '2026-01-20 14:20:00'),
('Bouchard', 'Luc', 'luc.bouchard@example.com', 'motdepasse_hache_5', 'MEMBRE', '2026-02-05 16:45:00'),
('Gauthier', 'Sophie', 'sophie.gauthier@example.com', 'motdepasse_hache_6', 'MEMBRE', '2026-02-18 08:30:00'),
('Morin', 'Alexandre', 'alex.morin@example.com', 'motdepasse_hache_7', 'MEMBRE', '2026-03-01 19:10:00'),
('Lavoie', 'Isabelle', 'isa.lavoie@example.com', 'motdepasse_hache_8', 'COACH', '2026-03-10 12:00:00'),
('Fortin', 'Nicolas', 'nic.fortin@example.com', 'motdepasse_hache_9', 'MEMBRE', '2026-04-22 15:55:00'),
('Pelletier', 'Émilie', 'emilie.pelletier@example.com', 'motdepasse_hache_10', 'MEMBRE', '2026-05-30 07:40:00');