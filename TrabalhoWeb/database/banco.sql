-- Banco de dados do projeto Super na Régua
-- WEB2 / LWEB2 - CEFET-MG Contagem

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` varchar(10) NOT NULL DEFAULT 'cliente',
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados de teste para `usuarios` (senha padrao: 123456)
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `data_cadastro`) VALUES
(1, 'Administrador', 'admin@supernaregua.com', '$2y$10$thg5SCSwZQrBzfaXEBkZ/OheVLkiIOfqY3X1OcrBXJ2Ruxl5iy4.W', 'admin', '2026-11-21 11:09:13'),
(2, 'João Cliente', 'joao@email.com', '$2y$10$thg5SCSwZQrBzfaXEBkZ/OheVLkiIOfqY3X1OcrBXJ2Ruxl5iy4.W', 'cliente', '2026-11-21 11:09:13'),
(3, 'Maria Cliente', 'maria@email.com', '$2y$10$thg5SCSwZQrBzfaXEBkZ/OheVLkiIOfqY3X1OcrBXJ2Ruxl5iy4.W', 'cliente', '2026-11-21 11:09:13');

-- --------------------------------------------------------

--
-- Tabela `servicos`
--

CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados de teste para `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `tipo`, `valor`, `descricao`, `imagem`, `ativo`) VALUES
(1, 'Corte degradê', 'Corte', 30.00, 'O corte degradê é uma técnica moderna que cria uma transição suave entre os comprimentos do cabelo, deixando o visual limpo e estiloso.', 'assets/degradee.jpg', 1),
(2, 'Corte social', 'Corte', 25.00, 'O corte social é clássico e elegante, ideal para ambientes formais e do dia a dia. Acabamento preciso e bem definido.', 'assets/social.jpg', 1),
(3, 'Barba completa', 'Barba', 20.00, 'Modelagem e aparamento completo da barba com toalha quente, produtos hidratantes e acabamento impecável.', 'assets/barba.jpg', 1),
(4, 'Corte + barba', 'Combo', 45.00, 'Combo completo unindo corte de cabelo e barba completa com desconto especial. O melhor custo-benefício da barbearia.', 'assets/combo.jpg', 1),
(5, 'Sobrancelha', 'Extra', 10.00, 'Design e alinhamento de sobrancelha com pinça e navalha para um visual mais definido e harmonioso.', 'assets/sobrancelha.jpg', 1),
(6, 'Pigmentação', 'Premium', 35.00, 'Pigmentação capilar para disfarçar falhas e uniformizar a cor do cabelo e barba, com resultado natural.', 'assets/pigmentacao.jpg', 1),
(7, 'Relaxamento capilar', 'Premium', 50.00, 'Tratamento para alisar e reduzir o volume dos fios, proporcionando mais maciez e facilidade no dia a dia.', 'assets/relaxamento.jpg', 1);

-- --------------------------------------------------------

--
-- Tabela `horarios`
--

CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servico_id` int(11) NOT NULL,
  `hora` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `servico_id` (`servico_id`),
  CONSTRAINT `fk_horarios_servicos` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados de teste para `horarios`
--

INSERT INTO `horarios` (`id`, `servico_id`, `hora`) VALUES
(1, 1, '09:00'),
(2, 1, '10:00'),
(3, 1, '11:00'),
(4, 1, '14:00'),
(5, 1, '16:00'),
(6, 2, '10:00'),
(7, 2, '11:00'),
(8, 2, '13:00'),
(9, 2, '15:00'),
(10, 2, '17:00'),
(11, 3, '09:30'),
(12, 3, '11:30'),
(13, 3, '14:30'),
(14, 3, '16:30'),
(15, 4, '10:00'),
(16, 4, '14:00'),
(17, 4, '16:00'),
(18, 4, '18:00'),
(19, 5, '09:00'),
(20, 5, '11:00'),
(21, 5, '13:00'),
(22, 5, '15:00'),
(23, 5, '17:00'),
(24, 6, '10:30'),
(25, 6, '14:30'),
(26, 6, '16:30'),
(27, 7, '09:00'),
(28, 7, '13:30'),
(29, 7, '15:30');

-- --------------------------------------------------------

--
-- Tabela `agendamentos`
--

CREATE TABLE IF NOT EXISTS `agendamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `data_agendamento` date NOT NULL,
  `horario` varchar(5) NOT NULL,
  `pessoas` int(11) NOT NULL DEFAULT 1,
  `valor_total` decimal(10,2) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `servico_id` (`servico_id`),
  CONSTRAINT `fk_agendamentos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_agendamentos_servicos` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados de teste para `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `usuario_id`, `servico_id`, `data_agendamento`, `horario`, `pessoas`, `valor_total`, `status`) VALUES
(1, 2, 1, '2026-11-25', '10:00', 1, 30.00, 'confirmado'),
(2, 3, 4, '2026-11-26', '14:00', 1, 45.00, 'pendente');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
