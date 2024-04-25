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

CREATE TABLE statuses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    status VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
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
    status_id INT REFERENCES statuses(id),
    total_score INT NULL,
    created_at TIMESTAMP default now(),
    updated_at TIMESTAMP default now(),
    deleted_at TIMESTAMP NULL
);

CREATE TABLE events (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_name VARCHAR(255) NOT NULL,
    desktop_photo VARCHAR(255) NULL,
    mobile_photo VARCHAR(255) NULL,
    header_footer_name VARCHAR(255) NOT NULL,
    selection_phase VARCHAR(255) NOT NULL,
    date DATETIME NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP default now(),
    updated_at TIMESTAMP default now()
);