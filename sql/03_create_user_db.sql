/**Creación usuarios de BBDD*/
CREATE USER 'teis2_root'@'localhost' IDENTIFIED BY 'abc123.';
CREATE USER 'teis2_conexion'@'localhost' IDENTIFIED BY 'abc123';
CREATE USER 'teis2_estandar'@'localhost' IDENTIFIED BY 'abc123';
/**Permisos a usuarios de BBDD*/
GRANT SELECT, INSERT, UPDATE, DELETE ON teis2_hotel.* TO 'teis2_root'@'localhost';
GRANT SELECT ON teis2_hotel.* TO 'teis2_conexion'@'localhost';
GRANT SELECT ON teis2_hotel.* TO 'teis2_estandar'@'localhost';