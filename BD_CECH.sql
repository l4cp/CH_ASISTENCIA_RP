drop database if exists intranet_cech;
-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS intranet_cech;
USE intranet_cech;

-- Tabla Aulas
CREATE TABLE Aulas (
    aula_id 						int				not null auto_increment,
    nombre_aula 					varchar(50)		not null,
    aforo 							int				not null,
    primary key(aula_id)
);

INSERT INTO `Aulas` (nombre_aula, aforo) 
VALUES ('A - 101', 20),('A - 102',25),('A - 201',30),('A - 202',20),('A - 203',25),('A - 301',20),('A - 302', 20);



-- Tabla Docentes
CREATE TABLE Docentes (
    docente_id 						int				not null auto_increment,
    nombre 							varchar(100) 	not null,
    especializacion 				VARCHAR(100)	not null,
    contacto						varchar(100),
    primary key(docente_id)
);

INSERT INTO `Docentes` (nombre, especializacion, contacto)
VALUES ('Antony Jimenez', 'Streaming', '999888777'),('Yajiro Sanchez', 'OBS Studio', '999345678'),('Yhony Aranibar', 'Mainkra', '923489189');

-- Tabla Usuarios
CREATE TABLE Usuarios (
    usuario_id 						int				not null auto_increment,
    nombre_usuario 					VARCHAR(50) 	not null,
    apellido_usuario				varchar(50)		not null,
    usuarme							varchar(50)		not null,
    password						varchar(50) 	not null,
    celular							varchar(50)		not null,
    estado							int				not null,
    primary key (usuario_id)
    );
 
-- Insertar un único registro en la tabla usuario
INSERT INTO `usuarios` (nombre_usuario, apellido_usuario, usuarme, password,celular,estado) 
VALUES ('Gustavo', 'Pachas', 'Admin', MD5('asd123'), 936583944, 1);

-- Tabla Cursos
CREATE TABLE Cursos (
    curso_id 						int				not null auto_increment,
    usuario_id						int,
    docente								int,
    nombre_curso 					varchar(100) 	not null,
    duracion 						ENUM('1 MES', '3 MESES', '6 MESES','12 MESES','18 MESES') not null,
    vacantes_ocup					int				not null,
    vacantes_max 					int 			not null,
    costo 							DECIMAL(10,2) 	not null,
    primary key	(curso_id),
    foreign key (docente) references Docentes (docente_id),
    foreign key (usuario_id) references Usuarios(usuario_id)
);

CREATE TABLE Descuentos (
    descuento_id 					int				not null auto_increment,
    tipo_descuento 					ENUM('Porcentaje', 'Monto Fijo') not null,
    valor_descuento 				decimal(10,2) 					 not null,
    motivo_descuento 				varchar(255),
    fecha_vigencia 					date,
    primary key (descuento_id)
);
INSERT INTO `Descuentos` (tipo_descuento, valor_descuento, motivo_descuento, fecha_vigencia)
VALUES ('Monto Fijo', 0, 'Precio por defecto', '2024/12/31');


-- Tabla Estudiantes
CREATE TABLE Estudiantes (
    estudiante_id 					int				not null auto_increment,
    nombre 							varchar(100) 	not null,
    apellido 						varchar(100) 	not null,
    tipo_doc						ENUM('Carnet de Extranjería', 'DNI') not null,
    Doc_ide 						varchar(50) 	unique not null,
    celular							varchar(50)		not null,
    fecha_registro 					date			not null,
    sede							ENUM('CHINCHA', 'CAÑETE', 'HUARAL', 'PISCO')	not null,
    primary key (estudiante_id)
);
INSERT INTO `Estudiantes` (nombre, apellido, tipo_doc, Doc_ide, celular, fecha_registro, sede)
VALUES ('Mauricio', 'Saravia Mesias', 'DNI', '98877665', '989876769', '2024/05/12', 'CHINCHA'),
	   ('Paul', 'Toralva', 'DNI', '99119911', '777776769', '2024/05/15', 'CHINCHA'),
       ('Henry', 'Alejos', 'DNI', '98828501', '986925849', '2024/07/21', 'CHINCHA'),
       ('Enzo', 'Castillo', 'DNI', '99999999', '960583958', '2024/10/15', 'CHINCHA'),
       ('Enzo', 'Ormeño', 'DNI', '99998888', '999983958', '2024/09/15', 'CHINCHA');

-- Tabla Matriculas
CREATE TABLE Matriculas (
    matricula_id 					int				not null auto_increment,
    estudiante_id					int,
    curso_id 						int,
    descuento_id 					int,
    costo_matricula					decimal(10,2),
    monto_final 					decimal(10,2),
    fecha_matricula					date			not null,
    primary key (matricula_id),
    foreign key (curso_id) references Cursos(curso_id),
    foreign key (estudiante_id) references Estudiantes(estudiante_id),
    foreign key (descuento_id) references Descuentos(descuento_id)
);

-- Tabla Horarios
CREATE TABLE Horarios (
    horario_id 						int				not null auto_increment,
    curso						int,
    aula						    int,
    dia_semana 						ENUM ('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado') NOT NULL,
    hora_inicio 					TIME 			NOT NULL,
    hora_fin 						TIME 			NOT NULL,
    primary key (horario_id),
    foreign key (curso) references Cursos(curso_id),
    foreign key (aula) references Aulas(aula_id)
);

-- Tabla Pagos
CREATE TABLE Pagos (
    pago_id 						int				not null auto_increment,
    estudiante_id 					int,
    curso_id 						int,
    monto_total 					decimal(10,2) 	not null,
    monto_pagado 					decimal(10,2) 	not null,
    estado_pago 					ENUM('Pendiente', 'Pagado') not null,
    fecha_pago 						date			not null,
    metodo_pago 					ENUM('Efectivo', 'Transferencia','Yape') not null,
    primary key (pago_id),
    foreign key (estudiante_id) references Estudiantes(estudiante_id),
    foreign key (curso_id) references Cursos(curso_id)
);


