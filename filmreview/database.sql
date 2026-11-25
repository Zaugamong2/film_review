-- Reel Verdict database. Import in phpMyAdmin.
CREATE DATABASE IF NOT EXISTS filmreview CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE filmreview;

DROP TABLE IF EXISTS contact_message;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS film;
DROP TABLE IF EXISTS reviewer;
DROP TABLE IF EXISTS genre;

-- Login accounts. Separate from reviewer: this table only controls who can sign in.
CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE genre (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE reviewer (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL
) ENGINE=InnoDB;

-- film replaces the lab "joke" table: joketext -> reviewtext, jokedate -> reviewdate
CREATE TABLE film (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  release_year SMALLINT NOT NULL,
  score TINYINT UNSIGNED NOT NULL,
  reviewtext TEXT NOT NULL,
  reviewdate DATE NOT NULL,
  poster VARCHAR(100) NULL,
  genreid INT NOT NULL,
  reviewerid INT NOT NULL,
  FOREIGN KEY (genreid) REFERENCES genre(id),
  FOREIGN KEY (reviewerid) REFERENCES reviewer(id)
) ENGINE=InnoDB;

CREATE TABLE contact_message (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  sentdate DATETIME NOT NULL,
  emailed TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO genre (name) VALUES ('Action'),('Animation'),('Comedy'),('Crime'),('Drama'),('Musical'),('Thriller');

INSERT INTO reviewer (name, email) VALUES
('Linh Tran','linh@example.com'),
('Sam Okafor','sam@example.com'),
('Maria Gomez','maria@example.com'),
('Daniel Kim','daniel@example.com');

INSERT INTO film (title, release_year, score, reviewtext, reviewdate, genreid, reviewerid) VALUES
('The Godfather', 1972, 97, 'A patient, powerful family tragedy. Every scene feels deliberate and the performances still hold up after fifty years.', '2026-10-02', 4, 1),
('Spirited Away', 2001, 96, 'Inventive, strange and warm. The bathhouse world rewards a second viewing for the background detail alone.', '2026-10-09', 2, 3),
('Parasite', 2019, 95, 'A sharp social thriller that changes tone without losing control. The final act is unforgettable.', '2026-10-15', 7, 2),
('Mad Max: Fury Road', 2015, 90, 'Almost one long chase, yet the practical stunts and clear staging keep it exciting from start to finish.', '2026-10-21', 1, 4),
('Cats', 2019, 20, 'The digital fur effects distract from the songs, and the story never settles into anything coherent.', '2026-11-03', 6, 2),
('The Room', 2003, 24, 'Wooden acting and baffling plot turns. Entertaining as a group watch, but hard to defend as a drama.', '2026-11-11', 5, 4);

-- Demo login: demo@example.com / ReelDemo2026!
INSERT INTO user (name, email, password_hash) VALUES
('Demo User','demo@example.com','$2y$10$S2CSVrhLi7SOU2V9hhFuqulzQmOA7xmhW5KHilj4Jm7jwUDg55DZu');
