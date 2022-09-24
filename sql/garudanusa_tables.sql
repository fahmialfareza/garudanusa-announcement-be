CREATE DATABASE garudanusa;

USE garudanusa;

CREATE TABLE users (
	id BIGINT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TIMESTAMP default now(),
    updated_at TIMESTAMP default now()
);

CREATE TABLE announcements (
	id BIGINT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    city_of_birth VARCHAR(255) NULL, 
    date_of_birth VARCHAR(255) NULL, 
    address_from VARCHAR(255) NULL, 
    school VARCHAR(255) NULL,
    result VARCHAR(255) NULL,
    total_score INT NULL,
    created_at TIMESTAMP default now(),
    updated_at TIMESTAMP default now(),
    deleted_at TIMESTAMP NULL
);

CREATE TABLE countdowns (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    date DATETIME NOT NULL,
    created_at TIMESTAMP default now(),
    updated_at TIMESTAMP default now()
);

DROP TABLE users;
DROP TABLE announcements;
DROP TABLE countdowns;