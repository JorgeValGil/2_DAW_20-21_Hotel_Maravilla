/**DATOS BBDD HOTEL - Adrián y Jorge*/
USE teis2_hotel;


/**Datos tabla roles*/
INSERT INTO roles(ID,NOMBRE_ROL)
VALUES(1,'Administrador'),
(2,'Registrado'),
(3,'Anonimo');


/**Datos tabla usuarios*/
INSERT INTO usuarios(NOMBRE,EMAIL,TELF,DIRECCION,PASSWORD,ROL_USUARIO)
VALUES('Jorge Val Gil','jorge@hotmail.com','679123789','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',1),
('Adrián Apellido Apellido','adrian@gmail.com','666000111','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',1),
('Carlos Apellido Apellido','carlos@gmail.com','636914762','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',2),
('Brais Apellido Apellido','brais@gmail.com','632456789','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',2),
('Javier Apellido Apellido','javi@gmail.com','623453243','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',2),
('Marta Apellido Apellido','marta@admin.com','666111225','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',1),
('Marta Apellido Apellido','marta@user.com','666111226','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',2),
('Patricia Apellido Apellido','patricia@admin.com','666111221','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',1),
('Patricia Apellido Apellido','patricia@user.com','666111222','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',2),
('Beatriz Apellido Apellido','bea@admin.com','666111223','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',1),
('Beatriz Apellido Apellido','bea@user.com','666111224','Vigo','$2y$10$SRRxNP9fud.dL2EBCMGDJ.eHIxn0FbsGeGXzpe4LDPTR3tF6Lgmh2',2);


/**Datos tabla habitacion_tipo*/
INSERT INTO habitacion_tipo(TIPO_HABITACION,DESCRIPCION)
VALUES('Single','A room for a guest with a single bed.'),
('Double','Room for two people, but in this case with a double bed.'),
('Triple','Room for three people with three single beds.'),
('Quadruple','Room prepared to house 4 guests.'),
('Family','Room made for a whole family.'),
('Suite','High category room.');


/**Datos tabla habitaciones*/
INSERT INTO habitaciones(M2,VENTANA,TIPO_DE_HABITACION,SERVICIO_LIMPIEZA,INTERNET,PRECIO)
VALUES('15',0,'Single',0,0,30.00),
('17',1,'Single',1,0,40.00),
('20',1,'Single',1,1,47.50),
('23',1,'Single',1,1,52.50),
('25',1,'Single',1,1,55.00),
('30',1,'Single',1,1,59.00),
('20',0,'Double',0,0,45.50),
('24',1,'Double',1,0,55.50),
('26',1,'Double',1,1,68.50),
('30',1,'Double',1,1,70.50),
('35',1,'Double',1,1,75.50),
('40',1,'Double',1,1,80.00),
('25',0,'Triple',0,0,50.00),
('30',1,'Triple',1,0,55.00),
('32',1,'Triple',1,1,70.00),
('35',1,'Triple',1,1,80.50),
('40',1,'Triple',1,1,88.50),
('50',1,'Triple',1,1,95.50),
('28',0,'Quadruple',0,0,60.00),
('35',1,'Quadruple',1,1,70.00),
('35',1,'Quadruple',1,1,79.50),
('40',1,'Quadruple',1,1,90.50),
('45',1,'Quadruple',1,1,105.50),
('55',1,'Quadruple',1,1,110.50),
('35',0,'Family',0,0,80.00),
('45',1,'Family',1,1,100.50),
('50',1,'Family',1,1,110.50),
('50',1,'Family',1,1,120.50),
('60',1,'Family',1,1,130.50),
('65',1,'Family',1,1,150.50),
('50',1,'Suite',1,1,100.00),
('60',1,'Suite',1,1,120.00),
('70',1,'Suite',1,1,140.00),
('80',1,'Suite',1,1,160.00),
('100',1,'Suite',1,1,180.00),
('120',1,'Suite',1,1,200.00);


/**Datos tabla servicios*/
INSERT INTO servicios(NOMBRE_SERVICIO,PRECIO_SERVICIO,DESCRIPCION,DISPONIBILIDAD)
VALUES('Kindergarten',15.50,'We will take care of your children for as long as necessary.',1),
('Breakfast',20,'We will bring breakfast to your room.',1),
('Pajamas',10,'Will have a set of pajamas.',1),
('Movies and audiovisual content on demand',15,'You will have access to the movies that we have available.',1),
('Aesthetic treatments',25,'Treatments for the aesthetics of your body will be available.',1),
('Healthy products to snack between meals',5,'Have healthy snacks available.',1),
('Medications without a prescription',5,'There will be certain medications that you can access.',1),
('Smartphone chargers',3,'You will have access to the charger you need for your smartphone.',1),
('Adult games kit',20,'Erotic kit: Cava, glass of cava, eye mask, scent candle, condom and 2 dice game.',1);


/**Datos tabla habitacion_servicio*/
INSERT INTO habitacion_servicio(ID_HABITACION,ID_SERVICIO,FECHA_SERVICIO,FECHA_FIN_SERVICIO)
VALUES (1,3,'2021-03-20 10:10:10','2020-03-21 10:10:10'),
(2,2,'2021-03-12 22:35:15','2020-03-14 14:15:13'),
(3,4,'2021-02-20 10:10:10','2020-02-21 10:10:10'),
(4,1,'2021-01-20 10:10:10','2020-03-21 10:10:10'),
(5,1,'2021-03-12 22:35:15','2020-03-14 14:15:13'),
(6,9,'2021-03-12 10:10:10','2020-03-12 12:55:00');


/**Datos tabla reservas*/
INSERT INTO reservas(ID_USUARIO,FECHA_ENTRADA,FECHA_SALIDA,FECHA_RESERVA,ESTADO)
VALUES(1,'2021-03-21','2021-03-25','2021-03-20 10:10:10',1),
(2,'2021-03-15','2021-03-25','2021-02-27 10:10:10',1),
(3,'2021-03-12','2021-03-20','2021-03-05 10:10:10',1),
(4,'2021-03-15','2021-03-20','2021-03-11 10:10:10',1),
(5,'2021-03-05','2021-03-15','2021-02-14 10:10:10',1),
(6,'2021-03-03','2021-03-07','2021-02-28 10:10:10',1),
(7,'2021-03-16','2021-03-17','2021-03-15 10:10:10',1),
(8,'2021-03-10','2021-03-21','2021-02-20 10:10:10',1);

INSERT INTO reservas(ID_USUARIO,FECHA_ENTRADA,FECHA_SALIDA)
VALUES(9,'2021-04-01','2021-04-03'),
(10,'2021-03-16','2021-03-17'),
(11,'2021-03-18','2021-03-25');



/**Datos tabla habitaciones_reservas*/
INSERT INTO habitaciones_reservas(NUM_RESERVA,ID_HABITACION)
VALUES(1,5),
(2,4),
(3,3),
(4,2),
(5,1),
(6,6),
(7,7),
(8,13),
(9,19),
(10,25),
(11,31);