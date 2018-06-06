-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         10.1.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para db_selfiehouse
CREATE DATABASE IF NOT EXISTS `db_selfiehouse` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `db_selfiehouse`;

-- Volcando estructura para tabla db_selfiehouse.acceso_codigo
CREATE TABLE IF NOT EXISTS `acceso_codigo` (
  `NRO` int(11) NOT NULL,
  `PERMISO` int(11) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  KEY `NRO` (`NRO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.acceso_codigo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso_codigo` DISABLE KEYS */;
/*!40000 ALTER TABLE `acceso_codigo` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.acceso_solicitud
CREATE TABLE IF NOT EXISTS `acceso_solicitud` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA` datetime NOT NULL,
  `FOTO` varchar(80) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.acceso_solicitud: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso_solicitud` DISABLE KEYS */;
REPLACE INTO `acceso_solicitud` (`ID`, `FECHA`, `FOTO`, `ESTADO`) VALUES
	(1, '2018-06-06 04:37:50', 'Webserver/src/images/2018-06-06-04-37-50.jpg', 1),
	(2, '2018-06-06 04:39:30', 'Webserver/src/images/2018-06-06-04-39-30.jpg', 1),
	(3, '2018-06-06 04:39:30', 'Webserver/src/images/2018-06-06-04-39-30.jpg', 1),
	(4, '2018-06-06 04:39:30', 'Webserver/src/images/2018-06-06-04-39-30.jpg', 1),
	(5, '2018-06-06 04:39:30', 'Webserver/src/images/2018-06-06-04-39-30.jpg', 1),
	(6, '2018-06-06 04:39:30', 'Webserver/src/images/2018-06-06-04-39-30.jpg', 1),
	(7, '2018-06-06 04:39:31', 'Webserver/src/images/2018-06-06-04-39-31.jpg', 1),
	(8, '2018-06-06 04:39:31', 'Webserver/src/images/2018-06-06-04-39-31.jpg', 1),
	(9, '2018-06-06 04:41:07', 'Webserver/src/images/2018-06-06-04-41-07.jpg', 1),
	(10, '2018-06-06 04:42:51', 'Webserver/src/images/2018-06-06-04-42-51.jpg', 1),
	(11, '2018-06-06 04:46:53', 'Webserver/src/images/2018-06-06-04-46-53.jpg', 1);
/*!40000 ALTER TABLE `acceso_solicitud` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LATITUD` double DEFAULT '0',
  `LONGITUD` double DEFAULT '0',
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.configuracion: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
REPLACE INTO `configuracion` (`ID`, `LATITUD`, `LONGITUD`) VALUES
	(1, 1, 1);
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.estado_componente
CREATE TABLE IF NOT EXISTS `estado_componente` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(50) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  `FECHA` datetime DEFAULT NULL,
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.estado_componente: ~12 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_componente` DISABLE KEYS */;
REPLACE INTO `estado_componente` (`ID`, `NOMBRE`, `ESTADO`, `FECHA`) VALUES
	(1, 'Traba', 0, '2018-05-02 13:25:38'),
	(2, 'Buzzer', 1, '2018-05-02 13:25:51'),
	(3, 'Ventilador', 0, '2018-05-02 13:26:09'),
	(4, 'Led Amarillo', 0, '2018-05-02 13:26:30'),
	(6, 'Led Rojo', 0, '2018-05-02 13:26:38'),
	(5, 'Led Azul', 0, '2018-05-02 13:26:38'),
	(7, 'Led Verde', 0, '2018-05-02 13:27:01'),
	(1, 'Traba', 0, '2018-05-02 13:25:38'),
	(2, 'Buzzer', 1, '2018-05-02 13:25:51'),
	(3, 'Ventilador', 0, '2018-05-02 13:26:09'),
	(4, 'Led Rojo', 0, '2018-05-02 13:26:38'),
	(5, 'Led Verde', 0, '2018-05-02 13:27:01');
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
	(14, '2018-05-12 23:22:33', 'Se activÃƒÂ³ la alarma buzzer. Disparador: DetecciÃƒÂ³n de movimiento', 1),
	(15, '2018-05-12 23:22:41', 'Se activÃƒÂ³ la alarma buzzer. Disparador: DetecciÃƒÂ³n de movimiento', 1),
	(16, '2018-05-12 23:28:23', 'Se activÃƒÂ³ la alarma buzzer. Disparador: DetecciÃƒÂ³n de movimiento', 1);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
