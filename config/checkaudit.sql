-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: checkaudit
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_projeto` varchar(255) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `data_realizacao` date DEFAULT NULL,
  `objetivo` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `email_responsavel` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_auditoria`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'Auditoria Teste','Auditoria Teste','2025-09-09','Auditoria Teste',NULL,NULL),(2,'Auditoria Teste 01','Auditoria Teste 01','2025-09-01','Auditoria Teste 01',4,NULL),(3,'Auditoria Teste 02','Auditoria Teste 02','2025-09-02','Auditoria Teste 02',4,NULL),(4,'Teste','Teste','2025-09-03','Teste',5,NULL),(5,'Corte de Grama','Corte de Grama','2025-09-04','Corte de Grama',5,NULL),(6,'Blá Blá Blá Blá','Blá Blá Blá Blá','2025-09-22','Blá Blá Blá Blá',5,'Bla@gmail.com'),(7,'Teste','juliana','2025-09-30','data-realizacao',5,'julianaaparecidavecchi@gmail.com');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist`
--

DROP TABLE IF EXISTS `checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_auditoria` int(11) NOT NULL,
  `pergunta` varchar(255) NOT NULL,
  `resultado` varchar(10) DEFAULT 'N/A',
  `responsavel` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `classificacao_nc` varchar(50) DEFAULT '',
  `acao_corretiva` text DEFAULT '',
  `situacao_nc` varchar(50) DEFAULT 'Pendente',
  `escalonamento` int(11) DEFAULT NULL,
  `prazo_resolucao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_auditoria` (`id_auditoria`),
  CONSTRAINT `fk_checklist_auditoria` FOREIGN KEY (`id_auditoria`) REFERENCES `auditoria` (`id_auditoria`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist`
--

LOCK TABLES `checklist` WRITE;
/*!40000 ALTER TABLE `checklist` DISABLE KEYS */;
INSERT INTO `checklist` VALUES (1,1,'arthur','OK','leonadoa','dadda','2025-09-06 19:20:12','','','pendente',NULL,NULL),(2,1,'arthur','OK','leonado','dadada','2025-09-06 19:20:12','','dadadad','pendente',3,NULL),(3,2,'Pergunta teste?','OK','','','2025-09-06 19:25:04','Maior','','pendente',NULL,NULL),(4,2,'Pergunta teste 02?','NC','saca','ascaca','2025-09-06 19:45:41','','csacas','pendente',NULL,NULL),(6,3,'Pergunta teste 02.1?','N/A',NULL,NULL,'2025-09-06 19:54:10','','','pendente',NULL,NULL),(7,4,'Pergunta teste','NC','','','2025-09-08 03:13:53','','','resolvida',2,NULL),(8,4,'vieuvde','NC','','','2025-09-08 03:47:38','','','pendente',2,NULL),(9,4,'nvnvrs','NC','','','2025-09-08 03:47:43','','','pendente',NULL,NULL),(10,5,'vd','NC','','','2025-09-08 15:46:58','','','Pendente',NULL,NULL),(11,6,'Pergunta teste 02?','NC','','','2025-09-09 19:40:43','','','Pendente',NULL,NULL),(12,7,'Pergunta teste 02?','NC','','','2025-09-09 20:17:55','','','Pendente',1,NULL);
/*!40000 ALTER TABLE `checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escalonamento`
--

DROP TABLE IF EXISTS `escalonamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `escalonamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nc` int(11) NOT NULL,
  `responsavel_imediato` varchar(255) NOT NULL,
  `email_responsavel_imediato` varchar(255) NOT NULL,
  `data_escalonamento` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_nc` (`id_nc`),
  CONSTRAINT `escalonamento_ibfk_1` FOREIGN KEY (`id_nc`) REFERENCES `checklist` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escalonamento`
--

LOCK TABLES `escalonamento` WRITE;
/*!40000 ALTER TABLE `escalonamento` DISABLE KEYS */;
INSERT INTO `escalonamento` VALUES (1,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:07:21'),(2,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:08:20'),(3,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:10:12'),(4,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:11:00'),(5,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:12:57'),(6,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:14:08'),(7,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:14:20'),(8,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:14:45'),(9,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:22:42'),(10,8,'Julianaaa','julianaaparecidavecchi@gmail.com','2025-09-10 00:22:58'),(11,12,'Julianaaa','julianaaparecidavecchi@gmail.comx','2025-09-10 00:32:36'),(12,7,'s','julianaaparecidavecchi@gmail.comx','2025-09-10 00:35:22'),(13,7,'k','julianaaparecidavecchi@gmail.com','2025-09-10 00:36:07');
/*!40000 ALTER TABLE `escalonamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4,'JulianaVecchiTeste','julianateste@gmail.com','$2y$10$cMKGg2RNeK9qEeoxnBo5..aPegBW85MDezrgZ0tNP3s6nGwlUG.m2','2025-09-06 19:24:18','2025-09-06 19:24:18'),(5,'Juliana','juliana@gmail.com','$2y$10$IZ04iKMAFMyPW2dSqA2wn.JGQZwIxHtQ/7VIXa5uMkZjh74EQbMmS','2025-09-08 02:40:12','2025-09-08 02:40:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-10  0:38:17
