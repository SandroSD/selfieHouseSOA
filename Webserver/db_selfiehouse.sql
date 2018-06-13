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
  `nro` int(11) NOT NULL,
  `permiso` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  KEY `NRO` (`nro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.acceso_codigo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso_codigo` DISABLE KEYS */;
/*!40000 ALTER TABLE `acceso_codigo` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.acceso_solicitud
CREATE TABLE IF NOT EXISTS `acceso_solicitud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `foto` varchar(80) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.acceso_solicitud: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso_solicitud` DISABLE KEYS */;
REPLACE INTO `acceso_solicitud` (`id`, `fecha`, `foto`, `estado`) VALUES
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitud` double DEFAULT '0',
  `longitud` double DEFAULT '0',
  KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.configuracion: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
REPLACE INTO `configuracion` (`id`, `latitud`, `longitud`) VALUES
	(1, -34.6693, -58.487012);
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.estado_componente
CREATE TABLE IF NOT EXISTS `estado_componente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.estado_componente: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_componente` DISABLE KEYS */;
REPLACE INTO `estado_componente` (`id`, `nombre`, `estado`, `fecha`) VALUES
	(1, 'Traba', 1, '2018-06-11 05:12:20'),
	(2, 'Buzzer', 0, '2018-06-11 05:12:20'),
	(3, 'Ventilador', 0, '2018-06-11 05:12:20'),
	(4, 'Led Rojo', 0, '2018-06-11 05:12:20'),
	(5, 'Led Verde', 1, '2018-06-11 05:12:20'),
	(6, 'SelfieHouse', 0, '2018-06-11 05:12:20'),
	(7, 'Debug', 0, '2018-06-11 05:12:20');
/*!40000 ALTER TABLE `estado_componente` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.notificacion
CREATE TABLE IF NOT EXISTS `notificacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comentario` varchar(255) DEFAULT NULL,
  `pendiente` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.notificacion: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
REPLACE INTO `notificacion` (`id`, `fecha`, `comentario`, `pendiente`) VALUES
	(1, '2018-06-11 00:03:33', 'asdas', 1),
	(2, '2018-06-11 00:04:17', 'Se activo el ventilador. Disparador: Acciï¿½n Manual', 1),
	(3, '2018-06-11 00:05:03', 'Se destrabo la puerta. Disparador: Acciï¿½n Manual', 1);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
