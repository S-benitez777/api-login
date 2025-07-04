CREATE DATABASE api_usuarios;
USE api_usuarios;

CREATE TABLE usuarios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    usuario NVARCHAR(50) NOT NULL UNIQUE,
    contrasena NVARCHAR(255) NOT NULL
);
