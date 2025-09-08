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
  PRIMARY KEY (`id_auditoria`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'Auditoria Teste','Auditoria Teste','2025-09-09','Auditoria Teste',NULL),(2,'Auditoria Teste 01','Auditoria Teste 01','2025-09-01','Auditoria Teste 01',4),(3,'Auditoria Teste 02','Auditoria Teste 02','2025-09-02','Auditoria Teste 02',4),(4,'Teste','Teste','2025-09-03','Teste',5);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist`
--

LOCK TABLES `checklist` WRITE;
/*!40000 ALTER TABLE `checklist` DISABLE KEYS */;
INSERT INTO `checklist` VALUES (1,1,'arthur','OK','leonadoa','dadda','2025-09-06 19:20:12','','','Concluída',NULL,NULL),(2,1,'arthur','OK','leonado','dadada','2025-09-06 19:20:12','','dadadad','Concluída',NULL,NULL),(3,2,'Pergunta teste?','OK','','','2025-09-06 19:25:04','Maior','','Pendente',NULL,NULL),(4,2,'Pergunta teste 02?','NC','saca','ascaca','2025-09-06 19:45:41','','csacas','Pendente',NULL,NULL),(6,3,'Pergunta teste 02.1?','N/A',NULL,NULL,'2025-09-06 19:54:10','','','Pendente',NULL,NULL),(7,4,'Pergunta teste','NC','','','2025-09-08 03:13:53','','','Pendente',NULL,NULL),(8,4,'vieuvde','NC','','','2025-09-08 03:47:38','','','Pendente',NULL,NULL),(9,4,'nvnvrs','NC','','','2025-09-08 03:47:43','','','Pendente',NULL,NULL);
/*!40000 ALTER TABLE `checklist` ENABLE KEYS */;
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

-- Dump completed on 2025-09-08 12:09:15
