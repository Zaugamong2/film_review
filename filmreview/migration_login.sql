-- Keep your existing data: run in phpMyAdmin instead of re-importing database.sql.
USE filmreview;
CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
-- Demo login: demo@example.com / ReelDemo2026!
INSERT INTO user (name, email, password_hash) VALUES ('Demo User','demo@example.com','"""+H+"""');
-- If you ran the earlier login update, reviewer has an unused password_hash column. It is harmless; ignore it.
