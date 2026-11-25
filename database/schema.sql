CREATE DATABASE IF NOT EXISTS sama_billet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sama_billet;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30) NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('client','admin') NOT NULL DEFAULT 'client',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(180) NOT NULL,
  event_date DATETIME NOT NULL,
  image VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30',
  price DECIMAL(10,2) NOT NULL,
  capacity INT UNSIGNED NOT NULL,
  sold INT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('published','draft','cancelled') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  CHECK (price >= 0),
  CHECK (capacity >= 0)
);

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  event_id INT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(30) NOT NULL,
  payment_status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  reference VARCHAR(30) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE tickets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  code VARCHAR(30) NOT NULL UNIQUE,
  is_used TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

INSERT INTO categories (name) VALUES ('Concert'), ('Festival'), ('Sport'), ('Conférence'), ('Théâtre');
INSERT INTO users (name,email,phone,password,role) VALUES
('Administrateur','admin@sama-billet.sn','771000000','$2y$10$u0u6nP8iI0k8gH4Xc1gS2e1FvQ9QvQJ7M9oZ6O1NqYVnE3Vb3dHhO','admin'),
('Client Démo','client@sama-billet.sn','778000000','$2y$10$u0u6nP8iI0k8gH4Xc1gS2e1FvQ9QvQJ7M9oZ6O1NqYVnE3Vb3dHhO','client');

INSERT INTO events (category_id,title,description,location,event_date,image,price,capacity,sold,status) VALUES
(1,'Nuit des étoiles','Une soirée exceptionnelle avec les meilleurs artistes africains. Spectacle, musique et émotions garanties.','Arène Nationale, Dakar','2026-12-20 20:00:00','https://images.unsplash.com/photo-1506157786151-b8491531f063',25000,800,320,'published'),
(2,'Dak’Art Festival','Un festival immersif célébrant les arts visuels, la mode et la créativité contemporaine.','Village des Arts, Dakar','2027-01-16 10:00:00','https://images.unsplash.com/photo-1540575467063-178a50c2df87',15000,600,145,'published'),
(3,'Grande Finale 2026','Assistez à la finale du championnat dans une ambiance électrique.','Stade du Sénégal, Diamniadio','2026-12-28 17:30:00','https://images.unsplash.com/photo-1522778119026-d647f0596c20',12000,5000,2100,'published'),
(4,'Tech & Innovation Africa','Rencontre avec les entrepreneurs, développeurs et investisseurs qui façonnent l’Afrique de demain.','King Fahd Palace, Dakar','2027-02-05 09:00:00','https://images.unsplash.com/photo-1497366754035-f200968a6e72',8000,400,75,'published'),
(5,'Le Roi Lion : Live','Une production théâtrale spectaculaire pour toute la famille.','Théâtre National, Dakar','2027-02-14 19:00:00','https://images.unsplash.com/photo-1503095396549-807759245b35',10000,300,90,'published'),
(1,'Sama Live Session','Une session live intime en plein air avec des sons nouveaux et des invités surprise.','Terrou-Bi, Dakar','2027-03-07 21:00:00','https://images.unsplash.com/photo-1501386761578-eac5c94b800a',18000,500,40,'published');
