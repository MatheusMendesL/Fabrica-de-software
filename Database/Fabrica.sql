-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para loja_games
CREATE DATABASE IF NOT EXISTS `loja_games` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `loja_games`;

-- Copiando estrutura para tabela loja_games.carrinho
CREATE TABLE IF NOT EXISTS `carrinho` (
  `ID_carrinho` int NOT NULL AUTO_INCREMENT,
  `ID_usuario` int NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`ID_carrinho`),
  KEY `Cliente_id` (`ID_usuario`) USING BTREE,
  CONSTRAINT `ID_usuario` FOREIGN KEY (`ID_usuario`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_games.carrinho: ~4 rows (aproximadamente)
INSERT INTO `carrinho` (`ID_carrinho`, `ID_usuario`, `created_at`) VALUES
	(1, 6, '2024-11-03'),
	(3, 7, '2024-11-04'),
	(7, 8, '2024-11-10'),
	(17, 5, '2024-11-13');

-- Copiando estrutura para tabela loja_games.categoria_produto
CREATE TABLE IF NOT EXISTS `categoria_produto` (
  `ID_categoria` int NOT NULL AUTO_INCREMENT,
  `Nome_categoria` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_games.categoria_produto: ~2 rows (aproximadamente)
INSERT INTO `categoria_produto` (`ID_categoria`, `Nome_categoria`) VALUES
	(1, 'Consoles'),
	(2, 'Acessórios');

-- Copiando estrutura para tabela loja_games.cliente
CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Senha` varchar(100) DEFAULT NULL,
  `Telefone` varchar(100) DEFAULT NULL,
  `Data_nascimento` date DEFAULT NULL,
  `CPF` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_games.cliente: ~2 rows (aproximadamente)
INSERT INTO `cliente` (`id`, `Nome`, `Email`, `Senha`, `Telefone`, `Data_nascimento`, `CPF`, `created_at`) VALUES
	(5, 'Matheus', 'matheus@gmail.com', '$2y$10$0SH6n579axfJ5B2wUZr/4eErsqscekgho.9G7N6AVwSidLeVVZZBW', '+55(11)95939-1788', '2008-06-11', '123.456.789-01', '2024-11-02 00:00:00'),
	(6, 'Matheus', 'teste@123', '$2y$10$gC4dwEM/SCue6OC6S08nSu1Ryx/1S9sgILB90ptUCrxGmLRQiuyLi', '+55(11)95939-1788', '2024-11-03', '123.456.789-02', '2024-11-02 00:00:00'),
	(7, 'Matheus ', '123@gmail.com', '$2y$10$Fkai59b8L6Z.l8DR5A0epeKmp4.LLMuqMMPugnnrC8clP2N.yDMJi', '+55(11)95939-1788', '2024-10-30', '123.456.789-07', '2024-11-04 22:19:08'),
	(8, 'messi', 'messi@gmail.com', '$2y$10$hpXj.LbK4hKEpswg5WHpleXEpJT2LwMq8ZviO3HaFW3SLP9I3VYiS', '+55(11)95939-1788', '2024-10-29', '123.456.789-03', '2024-11-10 19:16:27');

-- Copiando estrutura para tabela loja_games.endereco
CREATE TABLE IF NOT EXISTS `endereco` (
  `ID_endereco` int NOT NULL AUTO_INCREMENT,
  `CEP` varchar(25) NOT NULL,
  `Endereco` varchar(100) NOT NULL,
  `Numero` int NOT NULL,
  `Bairro` varchar(50) NOT NULL,
  `Complemento` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Estado` varchar(100) NOT NULL,
  `Cidade` varchar(100) NOT NULL,
  `ID_cliente` int NOT NULL,
  PRIMARY KEY (`ID_endereco`) USING BTREE,
  KEY `ID_cliente` (`ID_cliente`),
  CONSTRAINT `ID_cliente` FOREIGN KEY (`ID_cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_games.endereco: ~1 rows (aproximadamente)

-- Copiando estrutura para tabela loja_games.items_carrinho
CREATE TABLE IF NOT EXISTS `items_carrinho` (
  `ID_item` int NOT NULL AUTO_INCREMENT,
  `ID_carrinho` int NOT NULL,
  `ID_produto` int NOT NULL,
  `Quantidade` int NOT NULL,
  PRIMARY KEY (`ID_item`),
  KEY `ID_carrinho` (`ID_carrinho`),
  KEY `ID_produto` (`ID_produto`),
  CONSTRAINT `ID_carrinho` FOREIGN KEY (`ID_carrinho`) REFERENCES `carrinho` (`ID_carrinho`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ID_produto` FOREIGN KEY (`ID_produto`) REFERENCES `produto` (`ID_produto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_games.items_carrinho: ~5 rows (aproximadamente)
INSERT INTO `items_carrinho` (`ID_item`, `ID_carrinho`, `ID_produto`, `Quantidade`) VALUES
	(3, 1, 5, 1),
	(4, 1, 6, 1),
	(5, 3, 5, 1),
	(6, 3, 3, 1),
	(7, 3, 7, 1);

-- Copiando estrutura para tabela loja_games.produto
CREATE TABLE IF NOT EXISTS `produto` (
  `ID_produto` int NOT NULL AUTO_INCREMENT,
  `Nome_produto` varchar(100) DEFAULT NULL,
  `Descricao` text,
  `Preco` float DEFAULT NULL,
  `Marca_produto` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `img_produto` varchar(255) DEFAULT NULL,
  `Disponivel` tinyint(1) DEFAULT NULL,
  `ID_categoria` int DEFAULT NULL,
  PRIMARY KEY (`ID_produto`),
  KEY `ID_categoria` (`ID_categoria`),
  CONSTRAINT `ID_categoria` FOREIGN KEY (`ID_categoria`) REFERENCES `categoria_produto` (`ID_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_games.produto: ~6 rows (aproximadamente)
INSERT INTO `produto` (`ID_produto`, `Nome_produto`, `Descricao`, `Preco`, `Marca_produto`, `img_produto`, `Disponivel`, `ID_categoria`) VALUES
	(3, 'PS4 Slim 1tb', 'PS4 ', 24.5, 'Sony', 'https://cdn.awsli.com.br/2500x2500/118/118222/produto/28598269/d7c774cb5e.jpg', 1, 1),
	(5, 'Xbox Series S 500gb', 'Xbox', 3000, 'Microsoft', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIcMBseeQ2i91yBmJ_3Gzu708ahxfdopiH6g&s', 1, 1),
	(6, 'PS3 500GB', 'PS3', 2500, 'Sony', 'https://i5.walmartimages.com/seo/Restored-Sony-Playstation-3-PS3-Slim-160GB-Video-Game-Console-Black-Controller-HDMI-Refurbished_20c4061c-ec66-4b9b-bc2b-86f2bf1b637b_1.a35496f038a185fdf0f67ebfb5d6ff8b.jpeg', 1, 1),
	(7, 'Xbox 360 240gb', 'Xbox', 1200, 'Microsoft', 'https://images.tcdn.com.br/img/img_prod/1211726/xbox_360_4gb_seminovo_1259_1_d6bae2d4ea0fcfa7bb3ee1b2bad1224d.png', 1, 1),
	(8, 'Nintendo switch 200gb', 'Nintendo', 2000, 'Nintendo', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR7OWin3psxf-WhgBivIAFXyg8H41__nHP0vg&s', 1, 1),
	(9, 'Controle PS5', 'controle', 300, 'Sony', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1M8eucIoZvtrGP7bxy7HcAYEWgWfIRsFmeQ&s', 1, 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
