-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.6.32-log - MySQL Community Server (GPL)
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para db_selfiehouse
CREATE DATABASE IF NOT EXISTS `db_selfiehouse` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `db_selfiehouse`;

-- Volcando estructura para tabla db_selfiehouse.acceso
CREATE TABLE IF NOT EXISTS `acceso` (
  `ID` int(11) NOT NULL,
  `FECHA` datetime NOT NULL,
  `USUARIO` varchar(10) NOT NULL,
  `FOTO` varchar(80) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.acceso: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso` DISABLE KEYS */;
/*!40000 ALTER TABLE `acceso` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LATITUD` double DEFAULT '0',
  `LONGITUD` double DEFAULT '0',
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.configuracion: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.estado_componente
CREATE TABLE IF NOT EXISTS `estado_componente` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(50) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  `FECHA` datetime DEFAULT NULL,
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.estado_componente: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_componente` DISABLE KEYS */;
REPLACE INTO `estado_componente` (`ID`, `NOMBRE`, `ESTADO`, `FECHA`) VALUES
	(1, 'Traba', 0, '2018-05-02 13:25:38'),
	(2, 'Buzzer', 1, '2018-05-02 13:25:51'),
	(3, 'Ventilador', 0, '2018-05-02 13:26:09'),
	(4, 'Led Amarillo', 0, '2018-05-02 13:26:30'),
	(6, 'Led Rojo', 0, '2018-05-02 13:26:38'),
	(5, 'Led Azul', 0, '2018-05-02 13:26:38'),
	(7, 'Led Verde', 0, '2018-05-02 13:27:01');
/*!40000 ALTER TABLE `estado_componente` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.notificacion
CREATE TABLE IF NOT EXISTS `notificacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA` datetime DEFAULT NULL,
  `COMENTARIO` varchar(255) DEFAULT NULL,
  `PENDIENTE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.notificacion: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
REPLACE INTO `notificacion` (`ID`, `FECHA`, `COMENTARIO`, `PENDIENTE`) VALUES
	(14, '2018-05-12 23:22:33', 'Se activÃ³ la alarma buzzer. Disparador: DetecciÃ³n de movimiento', 1),
	(15, '2018-05-12 23:22:41', 'Se activÃ³ la alarma buzzer. Disparador: DetecciÃ³n de movimiento', 1),
	(16, '2018-05-12 23:28:23', 'Se activÃ³ la alarma buzzer. Disparador: DetecciÃ³n de movimiento', 1);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.perfil
CREATE TABLE IF NOT EXISTS `perfil` (
  `ID` char(10) NOT NULL,
  `NOMBRE` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.perfil: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.permiso
CREATE TABLE IF NOT EXISTS `permiso` (
  `ID` varchar(15) NOT NULL,
  `NOMBRE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.permiso: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.permiso_asigna
CREATE TABLE IF NOT EXISTS `permiso_asigna` (
  `PERMISO` varchar(15) NOT NULL,
  `PERFIL` varchar(10) NOT NULL,
  PRIMARY KEY (`PERMISO`,`PERFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.permiso_asigna: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permiso_asigna` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso_asigna` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `ID` varchar(15) NOT NULL,
  `NOMBRE` varchar(60) NOT NULL,
  `APELLIDO` varchar(100) DEFAULT NULL,
  `PERFIL` char(10) DEFAULT NULL,
  `ESTADO` int(1) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `SALT` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `USUARIO` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.usuario: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
