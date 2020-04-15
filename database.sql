CREATE DATABASE IF NOT EXISTS api_rest_frontman;
USE api_rest_frontman;

CREATE TABLE users(
id              int(255) auto_increment not null,
name            varchar(50) NOT NULL,
surname         varchar(100),
role            varchar(20),
email           varchar(255) NOT NULL,
password        varchar(255) NOT NULL,
description     text,
image           varchar(255),
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
remember_token  varchar(255),
CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE categories(
id              int(255) auto_increment not null,
name            varchar(100),
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_categories PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE propiedades(
id              int(255) auto_increment not null,
category_id     int(255) not null,
codigo          varchar(50) DEFAULT NULL,
titulo          varchar(255) NOT NULL,
operacion       varchar(100) NOT NULL,
ciudad          varchar(100) NOT NULL,
precio          varchar(100) NOT NULL,
mtstotales      varchar(100) DEFAULT NULL,
mtsconstruidos  varchar(100) DEFAULT NULL,
dormitorios     varchar(50) DEFAULT NULL,
banos           varchar(50) DEFAULT NULL,
direccion       varchar(100) DEFAULT NULL,
piscina         varchar(100) DEFAULT NULL,
bodega          varchar(100) DEFAULT NULL,
logia           varchar(100) DEFAULT NULL,
content         text not null,
image           varchar(255) NOT NULL,   
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_propiedades PRIMARY KEY(id),
CONSTRAINT fk_propiedad_category FOREIGN KEY(category_id) REFERENCES categories(id)
)ENGINE=InnoDb;

CREATE TABLE propiedades_imagenes(
id              int(255) auto_increment not null,
propiedad_id    int(255) not null,
file_name       Varchar(255) NOT NULL,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_propiedades_imagenes PRIMARY KEY(id),
CONSTRAINT fk_propiedad_imagen_propiedad FOREIGN KEY(propiedad_id) REFERENCES propiedades(id)
)ENGINE=InnoDb;