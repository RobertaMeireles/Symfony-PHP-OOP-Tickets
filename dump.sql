CREATE DATABASE flag_tickets;
USE flag_tickets;

CREATE TABLE tickets (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  description TEXT,
  severity INT(11),
  created_at TIMESTAMP,
  done BIT
);
