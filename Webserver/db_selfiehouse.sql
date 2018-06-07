CREATE DATABASE  IF NOT EXISTS `db_selfiehouse` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `db_selfiehouse`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_selfiehouse
-- ------------------------------------------------------
-- Server version	5.7.19-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acceso`
--

DROP TABLE IF EXISTS `acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acceso` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA` datetime NOT NULL,
  `USUARIO` varchar(10) NOT NULL,
  `FOTO` varchar(80) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acceso`
--

LOCK TABLES `acceso` WRITE;
/*!40000 ALTER TABLE `acceso` DISABLE KEYS */;
INSERT INTO `acceso` VALUES (1,'2018-06-03 19:48:29','usuario','Webserver/src/images/2018-06-03-19-48-29.jpg',1),(2,'2018-06-03 19:59:21','usuario','Webserver/src/images/2018-06-03-19-59-21.jpg',1),(3,'2018-06-03 19:59:22','usuario','Webserver/src/images/2018-06-03-19-59-22.jpg',1),(4,'2018-06-03 20:00:02','usuario','Webserver/src/images/2018-06-03-20-00-02.jpg',1),(5,'2018-06-03 20:07:56','usuario','Webserver/src/images/2018-06-03-20-07-56.jpg',1),(6,'2018-06-03 20:08:29','usuario','Webserver/src/images/2018-06-03-20-08-29.jpg',1),(7,'2018-06-03 20:11:17','usuario','Webserver/src/images/2018-06-03-20-11-17.jpg',1),(8,'2018-06-03 20:11:39','usuario','Webserver/src/images/2018-06-03-20-11-39.jpg',1),(9,'2018-06-03 20:11:41','usuario','Webserver/src/images/2018-06-03-20-11-41.jpg',1),(10,'2018-06-03 20:13:35','usuario','Webserver/src/images/2018-06-03-20-13-35.jpg',1),(11,'2018-06-03 20:15:08','usuario','Webserver/src/images/2018-06-03-20-15-08.jpg',1);
/*!40000 ALTER TABLE `acceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LATITUD` double DEFAULT '0',
  `LONGITUD` double DEFAULT '0',
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion`
--

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_componente`
--

DROP TABLE IF EXISTS `estado_componente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado_componente` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(50) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  `FECHA` datetime DEFAULT NULL,
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_componente`
--

LOCK TABLES `estado_componente` WRITE;
/*!40000 ALTER TABLE `estado_componente` DISABLE KEYS */;
INSERT INTO `estado_componente` VALUES (1,'Traba',0,'2018-05-02 13:25:38'),(2,'Buzzer',1,'2018-05-02 13:25:51'),(3,'Ventilador',0,'2018-05-02 13:26:09'),(4,'Led Amarillo',0,'2018-05-02 13:26:30'),(6,'Led Rojo',0,'2018-05-02 13:26:38'),(5,'Led Azul',0,'2018-05-02 13:26:38'),(7,'Led Verde',0,'2018-05-02 13:27:01');
/*!40000 ALTER TABLE `estado_componente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA` datetime DEFAULT NULL,
  `COMENTARIO` varchar(255) DEFAULT NULL,
  `PENDIENTE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
INSERT INTO `notificacion` VALUES (14,'2018-05-12 23:22:33','Se activÃ³ la alarma buzzer. Disparador: DetecciÃ³n de movimiento',1),(15,'2018-05-12 23:22:41','Se activÃ³ la alarma buzzer. Disparador: DetecciÃ³n de movimiento',1),(16,'2018-05-12 23:28:23','Se activÃ³ la alarma buzzer. Disparador: DetecciÃ³n de movimiento',1);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `ID` char(10) NOT NULL,
  `NOMBRE` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso` (
  `ID` varchar(15) NOT NULL,
  `NOMBRE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_asigna`
--

DROP TABLE IF EXISTS `permiso_asigna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso_asigna` (
  `PERMISO` varchar(15) NOT NULL,
  `PERFIL` varchar(10) NOT NULL,
  PRIMARY KEY (`PERMISO`,`PERFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_asigna`
--

LOCK TABLES `permiso_asigna` WRITE;
/*!40000 ALTER TABLE `permiso_asigna` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso_asigna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-03 15:17:48
