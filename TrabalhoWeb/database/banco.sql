-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Nov-2026 às 15:10
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `data_agendamento` date NOT NULL,
  `horario` varchar(5) NOT NULL,
  `pessoas` int(11) NOT NULL DEFAULT 1,
  `valor_total` decimal(10,2) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servicos`
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
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` varchar(10) NOT NULL DEFAULT 'cliente',
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `data_cadastro`) VALUES
(1, 'Administrador', 'admin@supernaregua.com', '$2y$10$thg5SCSwZQrBzfaXEBkZ/OheVLkiIOfqY3X1OcrBXJ2Ruxl5iy4.W', 'admin', '2026-11-21 11:09:13'),
(2, 'João Cliente', 'joao@email.com', '$2y$10$thg5SCSwZQrBzfaXEBkZ/OheVLkiIOfqY3X1OcrBXJ2Ruxl5iy4.W', 'cliente', '2026-11-21 11:09:13'),
(3, 'Maria Cliente', 'maria@email.com', '$2y$10$thg5SCSwZQrBzfaXEBkZ/OheVLkiIOfqY3X1OcrBXJ2Ruxl5iy4.W', 'cliente', '2026-11-21 11:09:13');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `servico_id` (`servico_id`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
