-- =====================================================================
--  esport.sql — Base de donnees du fil rouge « Competition e-sport »
--  BTS SIO 1re annee, semestre 1 — Parties 22 a 30
--
--  Import : phpMyAdmin > onglet « Importer » > choisir ce fichier.
--  Ce script cree la base, les tables et les donnees. Il peut etre
--  reimporte a tout moment : la base est recreee de zero.
--
--  ATTENTION : reimporter ce fichier efface toutes les modifications
--  faites depuis le dernier import.
-- =====================================================================

DROP DATABASE IF EXISTS esport;
CREATE DATABASE esport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE esport;

-- ---------------------------------------------------------------------
--  Table equipe — cote « 1 » de la relation
-- ---------------------------------------------------------------------
CREATE TABLE equipe (
  id_equipe    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nom          VARCHAR(60)  NOT NULL,
  pays         VARCHAR(40)  NOT NULL,
  annee_creation SMALLINT UNSIGNED NOT NULL,
  logo         VARCHAR(120) DEFAULT NULL,
  PRIMARY KEY (id_equipe),
  UNIQUE KEY uq_equipe_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table joueur — cote « N » de la relation
--  id_equipe est la cle etrangere : elle contient l'id_equipe de
--  l'equipe a laquelle le joueur appartient.
-- ---------------------------------------------------------------------
CREATE TABLE joueur (
  id_joueur    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  pseudo       VARCHAR(40)  NOT NULL,
  nom          VARCHAR(60)  NOT NULL,
  prenom       VARCHAR(60)  NOT NULL,
  date_naissance DATE       NOT NULL,
  poste        VARCHAR(30)  NOT NULL,
  score_saison INT UNSIGNED NOT NULL DEFAULT 0,
  date_arrivee DATE         NOT NULL,
  id_equipe    INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_joueur),
  UNIQUE KEY uq_joueur_pseudo (pseudo),
  KEY idx_joueur_equipe (id_equipe),
  CONSTRAINT fk_joueur_equipe
    FOREIGN KEY (id_equipe) REFERENCES equipe (id_equipe)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table utilisateur — comptes de connexion (Partie 29)
--  Le mot de passe n'est jamais stocke en clair : la colonne contient
--  une empreinte calculee par password_hash().
-- ---------------------------------------------------------------------
CREATE TABLE utilisateur (
  id_utilisateur INT UNSIGNED NOT NULL AUTO_INCREMENT,
  identifiant    VARCHAR(40)  NOT NULL,
  mot_de_passe   VARCHAR(255) NOT NULL,
  nom_affiche    VARCHAR(60)  NOT NULL,
  PRIMARY KEY (id_utilisateur),
  UNIQUE KEY uq_utilisateur_identifiant (identifiant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  Donnees
-- =====================================================================

INSERT INTO equipe (id_equipe, nom, pays, annee_creation, logo) VALUES
  (1, 'Nova Sentinels',   'France',    2018, 'nova-sentinels.png'),
  (2, 'Iron Phoenix',     'Allemagne', 2015, 'iron-phoenix.png'),
  (3, 'Blue Horizon',     'Canada',    2021, 'blue-horizon.png'),
  (4, 'Silent Vipers',    'Coree du Sud', 2012, 'silent-vipers.png'),
  (5, 'Atlas Rangers',    'Bresil',    2019, NULL),
  (6, 'Northern Lynx',    'Suede',     2020, NULL);

INSERT INTO joueur (pseudo, nom, prenom, date_naissance, poste, score_saison, date_arrivee, id_equipe) VALUES
  -- Nova Sentinels
  ('Nyx',        'Bertrand', 'Lucas',   '2003-04-11', 'Duelliste',   4820, '2022-01-15', 1),
  ('Marlow',     'Chevalier','Emma',    '2001-09-02', 'Support',     3110, '2021-07-01', 1),
  ('Grim',       'Fontaine', 'Hugo',    '2004-12-27', 'Sentinelle',  2740, '2023-02-20', 1),
  ('Vex',        'Moreau',   'Camille', '2002-03-18', 'Initiateur',  3960, '2022-09-05', 1),
  -- Iron Phoenix
  ('Ashen',      'Keller',   'Jonas',   '2000-06-30', 'Duelliste',   5210, '2019-03-11', 2),
  ('Brandt',     'Neumann',  'Lena',    '2002-11-14', 'Controleur',  3450, '2021-01-08', 2),
  ('Halberd',    'Weiss',    'Tobias',  '1999-08-23', 'Sentinelle',  2980, '2018-05-30', 2),
  -- Blue Horizon
  ('Frostbyte',  'Tremblay', 'Olivier', '2005-01-09', 'Duelliste',   3620, '2023-08-14', 3),
  ('Maple',      'Gagnon',   'Sophie',  '2003-07-21', 'Support',     2510, '2022-11-03', 3),
  ('Quill',      'Roy',      'Nathan',  '2004-05-16', 'Initiateur',  1870, '2024-01-22', 3),
  -- Silent Vipers
  ('Serpent',    'Park',     'Jisoo',   '1998-02-05', 'Duelliste',   6140, '2016-04-02', 4),
  ('Onyx',       'Kim',      'Minho',   '2001-10-30', 'Controleur',  4470, '2020-06-18', 4),
  ('Whisper',    'Lee',      'Haeun',   '2003-12-12', 'Sentinelle',  3890, '2022-03-27', 4),
  ('Tempest',    'Choi',     'Junseo',  '2000-04-08', 'Initiateur',  5030, '2019-09-09', 4),
  -- Atlas Rangers
  ('Solaris',    'Almeida',  'Rafael',  '2002-08-19', 'Duelliste',   2260, '2023-05-06', 5),
  ('Verde',      'Costa',    'Beatriz', '2004-02-28', 'Support',     1540, '2024-02-12', 5),
  -- Northern Lynx
  ('Frostwalk',  'Lindberg', 'Elias',   '2001-11-07', 'Controleur',  2890, '2022-06-24', 6),
  ('Aurora',     'Nilsson',  'Maja',    '2003-03-03', 'Sentinelle',  2130, '2023-10-01', 6);

-- ---------------------------------------------------------------------
--  Comptes de connexion
--    identifiant « admin »  / mot de passe « admin2026 »
--    identifiant « coach »  / mot de passe « coach2026 »
--  Les empreintes ci-dessous ont ete produites par password_hash().
--  Il est normal qu'elles soient illisibles : c'est le but.
-- ---------------------------------------------------------------------
INSERT INTO utilisateur (identifiant, mot_de_passe, nom_affiche) VALUES
  ('admin', '$2b$10$nQDJSPU5ue65dtDbLTTP5O21Z3daxRlCuG2Af/m6R6N87n5inpjtO', 'Administration'),
  ('coach', '$2b$10$bZ2NkSQV1Ubsn2VlnH/zLue2wKZUg9B4BA2toVk66PnUXletZevvW', 'Entraineur');
