-- Database of Social Network (SQLite). Created automatically by functions/functions.php
-- the first time private/database.sqlite does not exist yet.
-- All example accounts have the password "welkom123".

CREATE TABLE users (
  user_id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_firstname VARCHAR(20) NOT NULL,
  user_lastname VARCHAR(20) NOT NULL,
  user_nickname VARCHAR(20) DEFAULT NULL,
  user_password VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL,
  user_gender CHAR(1) NOT NULL,
  user_birthdate DATE NOT NULL,
  user_status CHAR(1) DEFAULT NULL,
  user_about TEXT,
  user_hometown VARCHAR(255) DEFAULT NULL
);

CREATE TABLE friendship (
  user1_id INTEGER NOT NULL REFERENCES users (user_id),
  user2_id INTEGER NOT NULL REFERENCES users (user_id),
  friendship_status INTEGER NOT NULL
);
CREATE INDEX friendship_user1 ON friendship (user1_id);
CREATE INDEX friendship_user2 ON friendship (user2_id);

CREATE TABLE posts (
  post_id INTEGER PRIMARY KEY AUTOINCREMENT,
  post_caption TEXT NOT NULL,
  post_time TIMESTAMP NOT NULL,
  post_public CHAR(1) NOT NULL,
  post_by INTEGER NOT NULL REFERENCES users (user_id)
);
CREATE INDEX posts_by ON posts (post_by);

CREATE TABLE user_phone (
  user_id INTEGER REFERENCES users (user_id),
  user_phone VARCHAR(20) DEFAULT NULL
);

INSERT INTO users (user_id, user_firstname, user_lastname, user_nickname, user_password, user_email, user_gender, user_birthdate, user_status, user_about, user_hometown) VALUES
(1, 'Sanne', 'de Vries', 'sanne', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'sanne@voorbeeld.nl', 'F', '2003-04-12', 'S', 'Ik studeer marketing en hou van fotografie.', 'Veenendaal'),
(2, 'Daan', 'Bakker', 'daanb', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'daan@voorbeeld.nl', 'M', '2002-09-23', 'E', 'Fanatiek sporter en koffieliefhebber.', 'Ede'),
(3, 'Youssef', 'El Amrani', 'youssef', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'youssef@voorbeeld.nl', 'M', '2004-01-30', 'S', 'Gamer en reiziger.', 'Utrecht'),
(4, 'Lisa', 'Jansen', 'lisaj', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'lisa@voorbeeld.nl', 'F', '2003-11-05', 'M', 'Op zoek naar de beste pizza van Ede.', 'Ede'),
(5, 'Paris', 'Angelopoulos', 'paris', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'paris@voorbeeld.nl', 'M', '2005-06-15', 'S', 'Web developer in opleiding. Ik heb deze site gebouwd.', 'Veenendaal');

INSERT INTO friendship (user1_id, user2_id, friendship_status) VALUES
(1, 2, 1),
(1, 3, 1),
(2, 4, 1),
(3, 5, 1),
(1, 5, 1),
(4, 5, 0),
(2, 5, 0);

INSERT INTO posts (post_id, post_caption, post_time, post_public, post_by) VALUES
(100, 'Hallo allemaal! Mijn eerste bericht op Pynch.', '2025-03-03 09:12:00', 'Y', 1),
(101, 'Vandaag mijn eerste Laravel-project opgezet. Migraties zijn echt handig.', '2025-03-04 16:45:00', 'Y', 5),
(102, 'Wie gaat er zaterdag mee naar de sportschool?', '2025-03-05 19:30:00', 'N', 2),
(103, 'Net terug uit Griekenland, wat een mooi weer daar.', '2025-03-07 12:05:00', 'Y', 3),
(104, 'Tip: gebruik altijd prepared statements, dan kan niemand SQL in je zoekveld typen.', '2025-03-08 10:20:00', 'Y', 5),
(105, 'Eindelijk mijn stageplek rond!', '2025-03-10 14:42:00', 'N', 1),
(106, 'Heeft iemand een goede pizzeria in Ede?', '2025-03-12 18:10:00', 'Y', 4),
(107, 'Weekend! Tijd om te gamen.', '2025-03-14 20:00:00', 'N', 3),
(108, 'Mijn portfolio staat online, laat maar weten wat jullie ervan vinden.', '2025-03-16 11:15:00', 'Y', 5),
(109, 'Koffie en code, het perfecte begin van de dag.', '2025-03-18 08:30:00', 'Y', 2),
(110, 'Iemand zin om samen te leren voor het examen?', '2025-03-19 15:50:00', 'N', 4),
(111, 'Wat een mooie zonsondergang vanavond in Veenendaal.', '2025-03-20 21:05:00', 'Y', 1);

INSERT INTO user_phone (user_id, user_phone) VALUES
(1, '0612345678'),
(5, '0687654321');
