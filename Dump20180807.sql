CREATE DATABASE  IF NOT EXISTS `gvdb` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gvdb`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: gvdb
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `atividades`
--

DROP TABLE IF EXISTS `atividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `atividades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_processo` int(10) unsigned NOT NULL,
  `usuario` int(10) unsigned NOT NULL,
  `data_conciliacao` date NOT NULL,
  `hora_inicio` timestamp NULL DEFAULT NULL,
  `hora_fim` timestamp NULL DEFAULT NULL,
  `data_meta` date DEFAULT NULL,
  `data_conciliada` date DEFAULT NULL,
  `ultima_data` date DEFAULT NULL,
  `coop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assunto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solicitante` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_atividades_id_processo` (`id_processo`),
  KEY `fk_atividades_usuario` (`usuario`),
  CONSTRAINT `fk_atividades_id_processo` FOREIGN KEY (`id_processo`) REFERENCES `processos` (`id`),
  CONSTRAINT `fk_atividades_usuario` FOREIGN KEY (`usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atividades`
--

LOCK TABLES `atividades` WRITE;
/*!40000 ALTER TABLE `atividades` DISABLE KEYS */;
INSERT INTO `atividades` VALUES (62113,526,1,'2018-08-01','2018-08-01 11:00:00','2018-08-01 12:00:00','2018-02-02','2018-02-02',NULL,NULL,NULL,NULL,NULL,'2018-08-06 14:30:24','2018-08-06 14:30:24'),(62114,526,1,'2018-08-01','2018-08-01 11:00:00','2018-08-01 12:00:00','2018-02-02','2018-02-02',NULL,NULL,NULL,NULL,NULL,'2018-08-06 14:32:17','2018-08-06 14:32:17'),(62115,526,1,'2018-08-08','2018-08-08 11:08:08','2018-08-08 11:08:09','2018-08-08','2018-08-08',NULL,NULL,NULL,NULL,NULL,'2018-08-06 14:33:31','2018-08-06 14:33:31');
/*!40000 ALTER TABLE `atividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificacoes`
--

DROP TABLE IF EXISTS `classificacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classificacoes` (
  `id` int(10) unsigned NOT NULL,
  `id_processo` int(10) unsigned NOT NULL,
  `opcao` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_processo_fk` (`id_processo`),
  CONSTRAINT `id_processo_fk` FOREIGN KEY (`id_processo`) REFERENCES `processos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificacoes`
--

LOCK TABLES `classificacoes` WRITE;
/*!40000 ALTER TABLE `classificacoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conclusoes`
--

DROP TABLE IF EXISTS `conclusoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conclusoes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_processo` int(10) unsigned NOT NULL,
  `data_conciliacao` date NOT NULL,
  `data_conciliada` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_processo_FK_idx` (`id_processo`),
  CONSTRAINT `fk_conclusoes_id_processo` FOREIGN KEY (`id_processo`) REFERENCES `processos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conclusoes`
--

LOCK TABLES `conclusoes` WRITE;
/*!40000 ALTER TABLE `conclusoes` DISABLE KEYS */;
INSERT INTO `conclusoes` VALUES (68,526,'2018-08-06','2018-08-01','2018-08-06 14:25:52','2018-08-06 14:25:52'),(69,526,'2018-08-06','2018-08-02','2018-08-06 14:26:09','2018-08-06 14:26:09');
/*!40000 ALTER TABLE `conclusoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordenacaos`
--

DROP TABLE IF EXISTS `coordenacaos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coordenacaos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordenacaos`
--

LOCK TABLES `coordenacaos` WRITE;
/*!40000 ALTER TABLE `coordenacaos` DISABLE KEYS */;
INSERT INTO `coordenacaos` VALUES (1,'Demais Contas','2017-09-19 00:40:52','2018-06-22 20:20:00'),(2,'GCT Legado','2018-03-08 00:01:39','2018-04-05 00:42:52'),(3,'Atendimento e Encerramento Contábil','2018-04-05 00:43:12','2018-04-05 00:43:12'),(4,'GCT Empresas Centralizadoras','2018-04-05 00:43:21','2018-04-05 00:43:21'),(5,'Especialistas/Excelência','2018-04-05 00:43:29','2018-04-05 00:43:29');
/*!40000 ALTER TABLE `coordenacaos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandas`
--

DROP TABLE IF EXISTS `demandas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demandas` (
  `id` int(10) unsigned NOT NULL,
  `id_processo` int(10) unsigned NOT NULL,
  `data_final` date NOT NULL,
  `id_responsavel` int(10) unsigned NOT NULL,
  `data_conclusao` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_responsavel` (`id_responsavel`),
  KEY `fk_id_processo` (`id_processo`),
  CONSTRAINT `fk_id_processo` FOREIGN KEY (`id_processo`) REFERENCES `processos` (`id`),
  CONSTRAINT `fk_id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandas`
--

LOCK TABLES `demandas` WRITE;
/*!40000 ALTER TABLE `demandas` DISABLE KEYS */;
/*!40000 ALTER TABLE `demandas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feriados`
--

DROP TABLE IF EXISTS `feriados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feriados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FERIADO_DATA` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FERIADO_DATA_IDX` (`FERIADO_DATA`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feriados`
--

LOCK TABLES `feriados` WRITE;
/*!40000 ALTER TABLE `feriados` DISABLE KEYS */;
INSERT INTO `feriados` VALUES (1,'2017-12-25',NULL,NULL);
/*!40000 ALTER TABLE `feriados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historico_indic`
--

DROP TABLE IF EXISTS `historico_indic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historico_indic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `processo_id` int(10) unsigned NOT NULL,
  `data_informada` date NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ultima_data` date DEFAULT NULL,
  `data_meta` date NOT NULL,
  `periodicidade_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_processo_FK_idx` (`processo_id`),
  KEY `id_user_idx` (`user_id`),
  CONSTRAINT `fk_processo_id` FOREIGN KEY (`processo_id`) REFERENCES `processos` (`id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico_indic`
--

LOCK TABLES `historico_indic` WRITE;
/*!40000 ALTER TABLE `historico_indic` DISABLE KEYS */;
INSERT INTO `historico_indic` VALUES (74,526,'2018-08-02',1,'2018-08-02','2018-07-31',1,'2018-08-06 16:40:12','2018-08-06 16:40:12','No Prazo');
/*!40000 ALTER TABLE `historico_indic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_responsavels`
--

DROP TABLE IF EXISTS `logs_responsavels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_responsavels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_PROCESSO` int(10) unsigned NOT NULL,
  `USUARIO` int(10) unsigned NOT NULL,
  `DATA_ALTERACAO` timestamp NOT NULL,
  `TIPO` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_logs_responsavels_usuario` (`USUARIO`),
  KEY `fk_logs_responsavels_ID_PROCESSO` (`ID_PROCESSO`),
  CONSTRAINT `fk_logs_responsavels_ID_PROCESSO` FOREIGN KEY (`ID_PROCESSO`) REFERENCES `processos` (`id`),
  CONSTRAINT `fk_logs_responsavels_usuario` FOREIGN KEY (`USUARIO`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_responsavels`
--

LOCK TABLES `logs_responsavels` WRITE;
/*!40000 ALTER TABLE `logs_responsavels` DISABLE KEYS */;
INSERT INTO `logs_responsavels` VALUES (1,526,1,'2018-08-06 14:15:30','INCLUSAO'),(2,526,1,'2018-08-06 14:22:30','EXCLUSAO'),(3,526,1,'2018-08-06 14:23:20','INCLUSAO');
/*!40000 ALTER TABLE `logs_responsavels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2017_08_08_231435_create_processos_table',1),(4,'2017_08_14_031736_create_tipos_table',1),(5,'2017_08_14_031750_create_periodicidades_table',1),(6,'2017_08_14_031802_create_coordenacaos_table',1),(7,'2017_08_24_012415_create_responsavels_table',1),(8,'2017_08_26_181432_create_atividades_table',1),(9,'2017_10_13_203515_create_conclusao_table',2),(10,'2017_10_13_205502_create_conclusoes_table',3),(11,'2017_10_21_120052_create_feriados',4),(12,'2018_01_14_112811_create_historico_indic',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observacoes`
--

DROP TABLE IF EXISTS `observacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observacoes` (
  `id` int(10) unsigned NOT NULL,
  `id_atividade` int(10) unsigned NOT NULL,
  `observacao` varchar(300) DEFAULT NULL,
  `classificacao` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_observacoes_ID_atividade` (`id_atividade`),
  KEY `fk_observacoes_classificacao` (`classificacao`),
  CONSTRAINT `fk_observacoes_ID_atividade` FOREIGN KEY (`id_atividade`) REFERENCES `atividades` (`id`),
  CONSTRAINT `fk_observacoes_classificacao` FOREIGN KEY (`classificacao`) REFERENCES `classificacoes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observacoes`
--

LOCK TABLES `observacoes` WRITE;
/*!40000 ALTER TABLE `observacoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `observacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periodicidades`
--

DROP TABLE IF EXISTS `periodicidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `periodicidades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dias` int(11) DEFAULT NULL,
  `uteis` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodicidades`
--

LOCK TABLES `periodicidades` WRITE;
/*!40000 ALTER TABLE `periodicidades` DISABLE KEYS */;
INSERT INTO `periodicidades` VALUES (1,'D-2','2018-03-21 23:44:57','2018-06-22 20:18:24',-2,'S'),(4,'D-3','2018-04-04 23:45:15','2018-04-04 23:45:15',-3,'S'),(5,'D-5','2018-04-04 23:45:31','2018-04-04 23:45:31',-5,'S'),(6,'Semanal','2018-04-04 23:45:58','2018-04-04 23:46:22',-7,'N'),(7,'Quinzenal','2018-04-04 23:53:05','2018-04-04 23:53:05',-12,'N'),(8,'Mensal','2018-04-04 23:53:25','2018-04-04 23:53:25',-23,'N');
/*!40000 ALTER TABLE `periodicidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `processos`
--

DROP TABLE IF EXISTS `processos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `processos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` int(10) unsigned NOT NULL,
  `periodicidade` int(10) unsigned DEFAULT NULL,
  `coordenacao` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `volumetria` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_processos_tipo` (`tipo`),
  KEY `fk_processos_periodicidade` (`periodicidade`),
  KEY `fk_processos_coordenacao` (`coordenacao`),
  CONSTRAINT `fk_processos_coordenacao` FOREIGN KEY (`coordenacao`) REFERENCES `coordenacaos` (`id`),
  CONSTRAINT `fk_processos_periodicidade` FOREIGN KEY (`periodicidade`) REFERENCES `periodicidades` (`id`),
  CONSTRAINT `fk_processos_tipo` FOREIGN KEY (`tipo`) REFERENCES `tipos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=527 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processos`
--

LOCK TABLES `processos` WRITE;
/*!40000 ALTER TABLE `processos` DISABLE KEYS */;
INSERT INTO `processos` VALUES (526,'TESTE',3,1,3,'2018-08-06 13:54:00','2018-08-06 13:54:00',NULL);
/*!40000 ALTER TABLE `processos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responsavels`
--

DROP TABLE IF EXISTS `responsavels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `responsavels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_processo` int(10) unsigned DEFAULT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_responsavels_id_processo_idx` (`id_processo`),
  CONSTRAINT `fk_responsavels_id_processo` FOREIGN KEY (`id_processo`) REFERENCES `processos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responsavels`
--

LOCK TABLES `responsavels` WRITE;
/*!40000 ALTER TABLE `responsavels` DISABLE KEYS */;
INSERT INTO `responsavels` VALUES (6,526,'1','2018-08-06 14:23:20','2018-08-06 14:23:20');
/*!40000 ALTER TABLE `responsavels` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER LOG_AFTER_INSERT
AFTER INSERT
   ON responsavels FOR EACH ROW

BEGIN

   INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( NEW.ID_PROCESSO,
	 NEW.USUARIO,
     SYSDATE(),
     'INCLUSAO' );

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `gvdb`.`responsavels_AFTER_UPDATE` AFTER UPDATE ON `responsavels` FOR EACH ROW
BEGIN

	INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( OLD.ID_PROCESSO,
	 OLD.USUARIO,
     SYSDATE(),
     'EXCLUSAO' );

   INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( NEW.ID_PROCESSO,
	 NEW.USUARIO,
     SYSDATE(),
     'INCLUSAO' );

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `gvdb`.`responsavels_AFTER_DELETE` AFTER DELETE ON `responsavels` FOR EACH ROW
BEGIN

	INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( OLD.ID_PROCESSO,
	 OLD.USUARIO,
     SYSDATE(),
     'EXCLUSAO' );

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `tipos`
--

DROP TABLE IF EXISTS `tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos`
--

LOCK TABLES `tipos` WRITE;
/*!40000 ALTER TABLE `tipos` DISABLE KEYS */;
INSERT INTO `tipos` VALUES (1,'Apoio','2017-09-19 00:40:10','2018-08-02 19:27:26'),(2,'Fechamento','2017-09-19 00:40:27','2018-06-08 13:22:54'),(3,'Conciliação','2018-04-04 23:36:40','2018-04-04 23:36:40'),(4,'Demanda','2018-06-26 20:34:04','2018-06-26 20:34:04');
/*!40000 ALTER TABLE `tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ricardo','ricardo_vargas','$2a$06$rgXkfRONoUcPKRQgattwk.hKpz71XlgkIACdowjFGIt.DDwImDRuS','p3gTeOAf8r9mBBWxnTKqnhebg0g854fZKphwwWVv5OEJDizv2OvRofPQv7xg',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volumetrias`
--

DROP TABLE IF EXISTS `volumetrias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volumetrias` (
  `id` int(10) unsigned NOT NULL,
  `id_atividade` int(10) unsigned NOT NULL,
  `volumetria` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_volumetrias_id_atividade` (`id_atividade`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volumetrias`
--

LOCK TABLES `volumetrias` WRITE;
/*!40000 ALTER TABLE `volumetrias` DISABLE KEYS */;
/*!40000 ALTER TABLE `volumetrias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'gvdb'
--
/*!50003 DROP FUNCTION IF EXISTS `FLOAT_DIAS_UTEIS` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `FLOAT_DIAS_UTEIS`( DATA_INFORMADA DATE,DIAS_FLOAT INTEGER) RETURNS date
BEGIN
	DECLARE DIA_SEMANA CHAR(20);
    DECLARE FERIADO CHAR(20);

WHILE  (DIAS_FLOAT >= 0) DO  
    SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA)C ; 
    SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
     
    WHILE (DIA_SEMANA = '5' OR DIA_SEMANA = '6' OR FERIADO IS NOT NULL) DO
		SET DATA_INFORMADA:=DATE_ADD(DATA_INFORMADA, INTERVAL 1 DAY);
		SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA )C ; 
		SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
    END WHILE;
    
	SET DATA_INFORMADA:=DATE_ADD(DATA_INFORMADA, INTERVAL 1 DAY);
  
	SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA )C ; 
	SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
   
	WHILE (DIA_SEMANA = '5' OR DIA_SEMANA = '6' OR FERIADO IS NOT NULL) DO
		SET DATA_INFORMADA:=DATE_ADD(DATA_INFORMADA, INTERVAL 1 DAY);
		SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA )C ; 
		SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
	END WHILE;
  
  
	SET DIAS_FLOAT := DIAS_FLOAT - 1;
END WHILE;

WHILE ( DIAS_FLOAT < 0)  DO
    SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA)C ; 
    SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
     
    WHILE (DIA_SEMANA = '5' OR DIA_SEMANA = '6' OR FERIADO IS NOT NULL) DO
		SET DATA_INFORMADA:=DATE_SUB(DATA_INFORMADA, INTERVAL 1 DAY);
		SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA )C ; 
		SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
    END WHILE;
    
	SET DATA_INFORMADA:= DATE_SUB(DATA_INFORMADA, INTERVAL 1 DAY);
  
	SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA )C ; 
	SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
   
	WHILE (DIA_SEMANA = '5' OR DIA_SEMANA = '6' OR FERIADO IS NOT NULL) DO
		SET DATA_INFORMADA:=DATE_SUB(DATA_INFORMADA, INTERVAL 1 DAY);
		SELECT FERIADO_DATA INTO FERIADO FROM (SELECT FERIADO_DATA FROM (SELECT DATA_INFORMADA DATA FROM DUAL) A LEFT JOIN feriados B ON A.DATA = B.FERIADO_DATA )C ; 
		SELECT weekday(DATA_INFORMADA) INTO DIA_SEMANA FROM DUAL;
	END WHILE;
  
  
	SET DIAS_FLOAT := DIAS_FLOAT + 1;
END WHILE;

RETURN DATA_INFORMADA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-07 13:27:07
