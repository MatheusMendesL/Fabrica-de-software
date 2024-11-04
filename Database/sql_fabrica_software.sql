CREATE DATABASE IF NOT EXISTS `loja_games`;
USE `loja_games`;


CREATE TABLE IF NOT EXISTS `categoria_produto` (
  `ID_categoria` int NOT NULL AUTO_INCREMENT,
  `Nome_categoria` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_categoria`)
);


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
);

CREATE TABLE IF NOT EXISTS `carrinho` (
  `ID_carrinho` int NOT NULL AUTO_INCREMENT,
  `ID_usuario` int NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`ID_carrinho`),
  KEY `Cliente_id` (`ID_usuario`) USING BTREE,
  CONSTRAINT `ID_usuario` FOREIGN KEY (`ID_usuario`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS `endereco` (
  `ID_endereco` int NOT NULL AUTO_INCREMENT,
  `CEP` varchar(25) NOT NULL,
  `Endereco` varchar(100) NOT NULL,
  `Numero` int NOT NULL,
  `Bairro` varchar(50) NOT NULL,
  `Complemento` varchar(50),
  `Estado` varchar(100) NOT NULL,
  `Cidade` varchar(100) NOT NULL,
  `ID_cliente` int NOT NULL,
  PRIMARY KEY (`ID_endereco`) USING BTREE,
  KEY `ID_cliente` (`ID_cliente`),
  CONSTRAINT `ID_cliente` FOREIGN KEY (`ID_cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS `produto` (
  `ID_produto` int NOT NULL AUTO_INCREMENT,
  `Nome_produto` varchar(100) DEFAULT NULL,
  `Descricao` text,
  `Preco` float DEFAULT NULL,
  `Marca_produto` longtext,
  `img_produto` varchar(255) DEFAULT NULL,
  `Disponivel` tinyint(1) DEFAULT NULL,
  `ID_categoria` int DEFAULT NULL,
  PRIMARY KEY (`ID_produto`),
  KEY `ID_categoria` (`ID_categoria`),
  CONSTRAINT `ID_categoria` FOREIGN KEY (`ID_categoria`) REFERENCES `categoria_produto` (`ID_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
);


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
);
