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
  PRIMARY KEY (`nro`),
  KEY `NRO` (`nro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.acceso_codigo: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso_codigo` DISABLE KEYS */;
REPLACE INTO `acceso_codigo` (`nro`, `permiso`, `estado`) VALUES
	(111111, 222, 0),
	(111112, 222, 0),
	(434723, 777, 1),
	(595027, 777, 1),
	(777777, 777, 1),
	(840379, 777, 1);
/*!40000 ALTER TABLE `acceso_codigo` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.acceso_solicitud
CREATE TABLE IF NOT EXISTS `acceso_solicitud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `foto` varchar(80) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla db_selfiehouse.acceso_solicitud: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `acceso_solicitud` DISABLE KEYS */;
REPLACE INTO `acceso_solicitud` (`id`, `fecha`, `foto`, `estado`) VALUES
	(1, '2018-06-06 04:37:50', 'Webserver/src/images/2018-06-06-04-37-50.jpg', 1);
/*!40000 ALTER TABLE `acceso_solicitud` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitud` double DEFAULT '0',
  `longitud` double DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ID` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.configuracion: ~1 rows (aproximadamente)
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
  PRIMARY KEY (`id`),
  KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.estado_componente: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_componente` DISABLE KEYS */;
REPLACE INTO `estado_componente` (`id`, `nombre`, `estado`, `fecha`) VALUES
	(1, 'Traba', 1, '2018-06-12 04:56:28'),
	(2, 'Buzzer', 0, '2018-06-12 04:56:28'),
	(3, 'Ventilador', 0, '2018-06-12 04:56:28'),
	(4, 'Led Rojo', 0, '2018-06-12 04:56:28'),
	(5, 'Led Verde', 1, '2018-06-12 04:56:28'),
	(6, 'SelfieHouse', 0, '2018-06-12 04:56:28'),
	(7, 'Debug', 0, '2018-06-12 04:56:28');
/*!40000 ALTER TABLE `estado_componente` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.log
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `detalle` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='tipo = 1 -> INFO\r\ntipo = 2 -> ERROR\r\ntipo = 3 -> WARN';

-- Volcando datos para la tabla db_selfiehouse.log: ~16 rows (aproximadamente)
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
REPLACE INTO `log` (`id`, `tipo`, `fecha`, `detalle`) VALUES
	(1, 1, '2018-06-13 09:51:04', 'asdasd'),
	(2, 2, '2018-06-13 09:51:46', 'Conexion::verificarCodigoAcceso() - No hay datos'),
	(3, 2, '2018-06-13 09:51:59', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(4, 2, '2018-06-13 09:52:00', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(5, 2, '2018-06-13 09:52:11', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(6, 2, '2018-06-13 09:54:20', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(7, 2, '2018-06-13 09:54:37', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(8, 2, '2018-06-13 09:55:40', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(9, 2, '2018-06-13 09:55:40', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(10, 2, '2018-06-13 09:55:41', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(11, 2, '2018-06-13 09:55:51', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(12, 2, '2018-06-13 09:56:03', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(13, 2, '2018-06-13 09:56:08', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(14, 2, '2018-06-13 09:56:09', 'Conexion::verificarCodigoAcceso() - 00000 - Array'),
	(15, 2, '2018-06-13 10:04:27', 'Conexion::verificarCodigoAcceso() - No hay datos'),
	(16, 2, '2018-06-13 10:04:31', 'Conexion::verificarCodigoAcceso() - No hay datos');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- Volcando estructura para tabla db_selfiehouse.notificacion
CREATE TABLE IF NOT EXISTS `notificacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comentario` varchar(255) DEFAULT NULL,
  `pendiente` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_selfiehouse.notificacion: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
REPLACE INTO `notificacion` (`id`, `fecha`, `comentario`, `pendiente`) VALUES
	(1, '2018-06-11 00:03:33', 'asdas', 1),
	(2, '2018-06-11 00:04:17', 'Se activo el ventilador. Disparador: Acciï¿½n Manual', 1),
	(3, '2018-06-11 00:05:03', 'Se destrabo la puerta. Disparador: Acciï¿½n Manual', 1),
	(14, '2018-05-12 23:22:33', 'Se activo la alarma buzzer. Disparador: Deteccion de movimiento', 1),
	(15, '2018-05-12 23:22:41', 'Se activo la alarma buzzer. Disparador: Deteccion de movimiento', 1),
	(16, '2018-05-12 23:28:23', 'Se activo la alarma buzzer. Disparador: Deteccion de movimiento', 1);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
