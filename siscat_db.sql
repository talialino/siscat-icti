-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny9
-- http://www.phpmyadmin.net
--
-- Servidor: mysql_desenv02.intranet.ufba.br
-- Tempo de Geração: Set 14, 2022 as 10:29 AM
-- Versão do Servidor: 5.7.23
-- Versão do PHP: 5.2.6-1+lenny16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `siscatdsvdb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item_sisrh_setor`
--

CREATE TABLE IF NOT EXISTS `auth_item_sisrh_setor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `id_setor` int(11) DEFAULT NULL,
  `funcao` int(4) NOT NULL,
  `id_comissao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_auth_item_has_sisrh_setor_sisrh_setor1_idx` (`id_setor`),
  KEY `fk_auth_item_has_sisrh_setor_auth_item1_idx` (`name`),
  KEY `fk_comissao` (`id_comissao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `timezone` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_aluno`
--

CREATE TABLE IF NOT EXISTS `sisai_aluno` (
  `id_aluno` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `matricula` int(11) NOT NULL,
  `email` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_setor` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_semestre` int(11) DEFAULT NULL,
  `nivel_escolaridade` tinyint(1) DEFAULT '1' COMMENT '1 - graduacao; 2 - especializacao; 3- mestrado; 4 - doutorado; 5- residencia;',
  PRIMARY KEY (`id_aluno`),
  UNIQUE KEY `un_matricula` (`matricula`),
  KEY `fk_sisai_aluno_sisrh_setor1_idx` (`id_setor`),
  KEY `fk_semestre_entrada` (`id_semestre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_aluno_componente_curricular`
--

CREATE TABLE IF NOT EXISTS `sisai_aluno_componente_curricular` (
  `id_aluno_componente_curricular` int(11) NOT NULL AUTO_INCREMENT,
  `id_avaliacao` int(11) NOT NULL,
  `id_componente_curricular` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `concluida` tinyint(1) NOT NULL DEFAULT '0',
  `id_setor` int(11) NOT NULL,
  PRIMARY KEY (`id_aluno_componente_curricular`),
  KEY `fk_colegiado` (`id_setor`),
  KEY `fk_componente` (`id_componente_curricular`),
  KEY `fk_pessoa` (`id_pessoa`),
  KEY `fk_avaliacao` (`id_avaliacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_aluno_resposta_objetiva`
--

CREATE TABLE IF NOT EXISTS `sisai_aluno_resposta_objetiva` (
  `id_aluno_resposta_objetiva` int(11) NOT NULL AUTO_INCREMENT,
  `resposta` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  `id_aluno_componente_curricular` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_aluno_resposta_objetiva`),
  KEY `fk_sisai_resposta_objetiva_sisai_pergunta1_idx` (`id_pergunta`),
  KEY `fk_sisai_resposta_objetiva_sisai_avaliacao1_idx` (`id_avaliacao`),
  KEY `fk_sisai_resposta_objetiva_sisai_avaliacao_professor_turma__idx` (`id_aluno_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_aluno_resposta_subjetiva`
--

CREATE TABLE IF NOT EXISTS `sisai_aluno_resposta_subjetiva` (
  `id_aluno_resposta_subjetiva` int(11) NOT NULL AUTO_INCREMENT,
  `resposta` text COLLATE utf8_unicode_ci,
  `id_pergunta` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  `id_aluno_componente_curricular` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_aluno_resposta_subjetiva`),
  KEY `fk_sisai_resposta_sujetiva_sisai_pergunta1_idx` (`id_pergunta`),
  KEY `fk_sisai_resposta_sujetiva_sisai_avaliacao1_idx` (`id_avaliacao`),
  KEY `fk_sisai_resposta_sujetiva_sisai_avaliacao_professor_turma__idx` (`id_aluno_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_avaliacao`
--

CREATE TABLE IF NOT EXISTS `sisai_avaliacao` (
  `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_semestre` int(11) NOT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `tipo_avaliacao` int(4) DEFAULT NULL COMMENT '0 - discente; 1 - docente; 2 - técnico',
  `situacao` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_avaliacao`),
  KEY `fk_sisai_avaliacao_sisai_aluno1_idx` (`id_aluno`),
  KEY `fk_sisai_avaliacao_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_sisai_avaliacao_siscc_semestre1_idx` (`id_semestre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_colegiado_semestre_atuv`
--

CREATE TABLE IF NOT EXISTS `sisai_colegiado_semestre_atuv` (
  `id_colegiado_semestre_atuv` int(4) NOT NULL AUTO_INCREMENT,
  `colegiados_liberados` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_semestre` int(11) NOT NULL,
  PRIMARY KEY (`id_colegiado_semestre_atuv`),
  KEY `fk_sisai_colegiado_semestre_atuv_siscc_semestre1_idx` (`id_semestre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Colegiados liberados para aparecerem na lista da atuv';

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_grupo_perguntas`
--

CREATE TABLE IF NOT EXISTS `sisai_grupo_perguntas` (
  `id_grupo_perguntas` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_questionario` int(4) NOT NULL,
  PRIMARY KEY (`id_grupo_perguntas`),
  KEY `fk_sisai_grupo_perguntas_sisai_questionario1_idx` (`id_questionario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_pergunta`
--

CREATE TABLE IF NOT EXISTS `sisai_pergunta` (
  `id_pergunta` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text COLLATE utf8_unicode_ci,
  `tipo_pergunta` int(4) DEFAULT NULL,
  `id_grupo_perguntas` int(11) NOT NULL,
  `nsa` int(1) DEFAULT '0' COMMENT '1 - Não se aplica; 2 - Não se aplica/não sabe opinar; 3 - Não se aplica - supervisão direta; 4 - Não se aplica - supervisão indireta; 5 - Desconheço',
  PRIMARY KEY (`id_pergunta`),
  KEY `fk_grupo_perguntas` (`id_grupo_perguntas`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_periodo_avaliacao`
--

CREATE TABLE IF NOT EXISTS `sisai_periodo_avaliacao` (
  `id_semestre` int(11) NOT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `questionarios` text COLLATE utf8_unicode_ci,
  `componentes_estagio` text COLLATE utf8_unicode_ci,
  `componentes_online` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_semestre`),
  KEY `fk_sisai_periodo_avaliacao_siscc_semestre1_idx` (`id_semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_professor_componente_curricular`
--

CREATE TABLE IF NOT EXISTS `sisai_professor_componente_curricular` (
  `id_professor_componente_curricular` int(11) NOT NULL AUTO_INCREMENT,
  `id_avaliacao` int(11) NOT NULL,
  `id_componente_curricular` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `concluida` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_professor_componente_curricular`),
  KEY `fk_sisai_professor_componente_curricular_sisai_avaliacao1_idx` (`id_avaliacao`),
  KEY `fk_sisai_professor_componente_curricular_siscc_componente_c_idx` (`id_componente_curricular`),
  KEY `fk_sisai_professor_componente_curricular_sisrh_setor1_idx` (`id_setor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_professor_resposta_objetiva`
--

CREATE TABLE IF NOT EXISTS `sisai_professor_resposta_objetiva` (
  `id_professor_resposta_objetiva` int(11) NOT NULL AUTO_INCREMENT,
  `resposta` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  `id_professor_componente_curricular` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_professor_resposta_objetiva`),
  KEY `fk_sisai_resposta_objetiva_sisai_pergunta1_idx` (`id_pergunta`),
  KEY `fk_sisai_resposta_objetiva_sisai_avaliacao1_idx` (`id_avaliacao`),
  KEY `fk_sisai_aluno_resposta_objetiva_copy1_siscc_programa_compo_idx` (`id_professor_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_professor_resposta_subjetiva`
--

CREATE TABLE IF NOT EXISTS `sisai_professor_resposta_subjetiva` (
  `id_professor_resposta_subjetiva` int(11) NOT NULL AUTO_INCREMENT,
  `resposta` text COLLATE utf8_unicode_ci,
  `id_pergunta` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  `id_professor_componente_curricular` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_professor_resposta_subjetiva`),
  KEY `fk_sisai_resposta_sujetiva_sisai_pergunta1_idx` (`id_pergunta`),
  KEY `fk_sisai_resposta_sujetiva_sisai_avaliacao1_idx` (`id_avaliacao`),
  KEY `fk_sisai_aluno_resposta_sujetiva_copy1_siscc_programa_compo_idx` (`id_professor_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_questionario`
--

CREATE TABLE IF NOT EXISTS `sisai_questionario` (
  `id_questionario` int(4) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_questionario` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_questionario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_resposta_multipla_escolha`
--

CREATE TABLE IF NOT EXISTS `sisai_resposta_multipla_escolha` (
  `id_sisai_resposta_multipla_escolha` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_sisai_resposta_multipla_escolha`,`id_pergunta`),
  KEY `fk_sisai_resposta_multipla_escolha_sisai_pergunta1_idx` (`id_pergunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_tecnico_resposta_objetiva`
--

CREATE TABLE IF NOT EXISTS `sisai_tecnico_resposta_objetiva` (
  `id_tecnico_resposta_objetiva` int(11) NOT NULL AUTO_INCREMENT,
  `resposta` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  PRIMARY KEY (`id_tecnico_resposta_objetiva`),
  KEY `fk_sisai_resposta_objetiva_sisai_pergunta1_idx` (`id_pergunta`),
  KEY `fk_sisai_resposta_objetiva_sisai_avaliacao1_idx` (`id_avaliacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisai_tecnico_resposta_subjetiva`
--

CREATE TABLE IF NOT EXISTS `sisai_tecnico_resposta_subjetiva` (
  `id_tecnico_resposta_subjetiva` int(11) NOT NULL AUTO_INCREMENT,
  `resposta` text COLLATE utf8_unicode_ci,
  `id_pergunta` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  PRIMARY KEY (`id_tecnico_resposta_subjetiva`),
  KEY `fk_sisai_resposta_sujetiva_sisai_pergunta1_idx` (`id_pergunta`),
  KEY `fk_sisai_resposta_sujetiva_sisai_avaliacao1_idx` (`id_avaliacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_atividade`
--

CREATE TABLE IF NOT EXISTS `sisape_atividade` (
  `id_atividade` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `descricao_atividade` text COLLATE utf8_unicode_ci,
  `concluida` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_atividade`),
  KEY `fk_sispit_atividade_sisape_projeto1_idx` (`id_projeto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_financiamento`
--

CREATE TABLE IF NOT EXISTS `sisape_financiamento` (
  `id_financiamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) NOT NULL,
  `origem` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id_financiamento`),
  KEY `fk_sisape_financiamento_sisape_projeto1_idx` (`id_projeto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_integrante_externo`
--

CREATE TABLE IF NOT EXISTS `sisape_integrante_externo` (
  `id_integrante_externo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_integrante_externo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_parecer`
--

CREATE TABLE IF NOT EXISTS `sisape_parecer` (
  `id_parecer` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) DEFAULT NULL,
  `id_relatorio` int(11) DEFAULT NULL,
  `id_pessoa` int(11) NOT NULL,
  `tipo_parecerista` int(11) DEFAULT NULL,
  `parecer` text CHARACTER SET utf8,
  `data` date DEFAULT NULL,
  `atual` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id_parecer`),
  KEY `fk_sisape_parecer_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_sisape_parecer_sisape_projeto1_idx` (`id_projeto`),
  KEY `fk_sisape_parecer_sisape_relatorio1_idx` (`id_relatorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_projeto`
--

CREATE TABLE IF NOT EXISTS `sisape_projeto` (
  `id_projeto` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `tipo_projeto` int(4) DEFAULT NULL,
  `titulo` text COLLATE utf8_unicode_ci,
  `area_atuacao` text COLLATE utf8_unicode_ci,
  `local_execucao` text COLLATE utf8_unicode_ci,
  `parcerias` text COLLATE utf8_unicode_ci,
  `infraestrutura` text COLLATE utf8_unicode_ci,
  `submetido_etica` tinyint(1) DEFAULT NULL,
  `disponivel_site` tinyint(1) DEFAULT NULL,
  `resumo` text COLLATE utf8_unicode_ci,
  `introducao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `justificativa` text COLLATE utf8_unicode_ci,
  `objetivos` text COLLATE utf8_unicode_ci,
  `metodologia` text COLLATE utf8_unicode_ci,
  `resultados_esperados` text COLLATE utf8_unicode_ci,
  `situacao` int(4) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL COMMENT '	',
  `orcamento` text COLLATE utf8_unicode_ci,
  `referencias` text COLLATE utf8_unicode_ci,
  `id_setor` int(11) DEFAULT NULL,
  `data_aprovacao_nucleo` date DEFAULT NULL,
  `data_aprovacao_copex` date DEFAULT NULL,
  `data_homologacao_congregacao` date DEFAULT NULL,
  `sessao_congregacao` int(11) DEFAULT NULL,
  `tipo_sessao_congregacao` tinyint(1) DEFAULT NULL,
  `tipo_extensao` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_projeto`),
  KEY `fk_sisape_projeto_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_setor` (`id_setor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_projeto_integrante`
--

CREATE TABLE IF NOT EXISTS `sisape_projeto_integrante` (
  `id_projeto_integrante` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) NOT NULL,
  `id_integrante_externo` int(11) DEFAULT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `funcao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL COMMENT 'falta vincular\nid_aluno a \nsisai_aluno',
  `carga_horaria` int(6) DEFAULT NULL,
  `vinculo` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_projeto_integrante`),
  KEY `fk_sisape_projeto_integrante_sisape_projeto1_idx` (`id_projeto`),
  KEY `fk_sisape_projeto_integrante_sisape_integrante_externo1_idx` (`id_integrante_externo`),
  KEY `fk_sisape_projeto_integrante_sisrh_pessoa1_idx` (`id_pessoa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisape_relatorio`
--

CREATE TABLE IF NOT EXISTS `sisape_relatorio` (
  `id_relatorio` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) NOT NULL,
  `situacao_projeto` int(4) DEFAULT NULL,
  `justificativa` text COLLATE utf8_unicode_ci,
  `alunos_orientados` int(4) DEFAULT NULL,
  `resumos_publicados` int(4) DEFAULT NULL,
  `artigos_publicados` int(4) DEFAULT NULL,
  `artigos_aceitos` int(4) DEFAULT NULL,
  `relatorio_agencia` tinyint(1) DEFAULT NULL,
  `deposito_patente` tinyint(1) DEFAULT NULL,
  `outros_indicadores` text COLLATE utf8_unicode_ci,
  `consideracoes_finais` text COLLATE utf8_unicode_ci,
  `data_relatorio` date DEFAULT NULL,
  `data_aprovacao_nucleo` date DEFAULT NULL,
  `data_aprovacao_copex` date DEFAULT NULL,
  `data_homologacao_congregacao` date DEFAULT NULL,
  `sessao_congregacao` int(11) DEFAULT NULL,
  `tipo_sessao_congregacao` tinyint(1) DEFAULT NULL,
  `situacao` int(11) NOT NULL,
  PRIMARY KEY (`id_relatorio`),
  KEY `fk_sisape_relatorio_sisape_projeto1_idx` (`id_projeto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_bibliografia`
--

CREATE TABLE IF NOT EXISTS `siscc_bibliografia` (
  `id_bibliografia` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_bibliografia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_componente_curricular`
--

CREATE TABLE IF NOT EXISTS `siscc_componente_curricular` (
  `id_componente_curricular` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_componente` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `ch_teorica` int(7) DEFAULT NULL,
  `ch_pratica` int(7) DEFAULT NULL,
  `ch_estagio` int(7) DEFAULT NULL,
  `ch_extensao` int(7) DEFAULT NULL,
  `modulo_teoria` int(7) DEFAULT NULL,
  `modulo_pratica` int(7) DEFAULT NULL,
  `modulo_estagio` int(7) DEFAULT NULL,
  `modulo_extensao` int(7) DEFAULT NULL,
  `modalidade` int(7) DEFAULT NULL,
  `ementa` text COLLATE utf8_unicode_ci,
  `prerequisitos` text COLLATE utf8_unicode_ci,
  `ativo` tinyint(1) DEFAULT '1',
  `anual` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_parecer`
--

CREATE TABLE IF NOT EXISTS `siscc_parecer` (
  `id_parecer` int(11) NOT NULL AUTO_INCREMENT,
  `id_programa_componente_curricular` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `tipo_parecerista` int(11) DEFAULT NULL,
  `parecer` text COLLATE utf8_unicode_ci,
  `data` date DEFAULT NULL,
  `atual` tinyint(4) DEFAULT '1',
  `edicao` tinyint(1) DEFAULT '0',
  `comentario` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_parecer`),
  KEY `fk_siscc_parecer_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_siscc_parecer_siscc_programa1_idx` (`id_programa_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_programa_componente_curricular`
--

CREATE TABLE IF NOT EXISTS `siscc_programa_componente_curricular` (
  `id_programa_componente_curricular` int(11) NOT NULL AUTO_INCREMENT,
  `id_componente_curricular` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `objetivo_geral` text COLLATE utf8_unicode_ci,
  `objetivos_especificos` text COLLATE utf8_unicode_ci,
  `conteudo_programatico` text COLLATE utf8_unicode_ci,
  `data_aprovacao_colegiado` date DEFAULT NULL,
  `data_aprovacao_coordenacao` date DEFAULT NULL,
  `situacao` int(4) DEFAULT '0',
  PRIMARY KEY (`id_programa_componente_curricular`),
  KEY `fk_siscc_componente_curricular_has_sisrh_setor_sisrh_setor1_idx` (`id_setor`),
  KEY `fk_siscc_componente_curricular_has_sisrh_setor_siscc_compon_idx` (`id_componente_curricular`),
  KEY `fk_siscc_programa_componente_curricular_siscc_semestre1_idx` (`id_semestre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_programa_componente_curricular_bibliografia`
--

CREATE TABLE IF NOT EXISTS `siscc_programa_componente_curricular_bibliografia` (
  `id_programa_componente_curricular` int(11) NOT NULL,
  `id_bibliografia` int(11) NOT NULL,
  `tipo_referencia` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_programa_componente_curricular`,`id_bibliografia`),
  KEY `fk_siscc_programa_componente_curricular_has_siscc_bibliogra_idx` (`id_bibliografia`),
  KEY `fk_siscc_programa_componente_curricular_has_siscc_bibliogra_idx1` (`id_programa_componente_curricular`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_programa_componente_curricular_pessoa`
--

CREATE TABLE IF NOT EXISTS `siscc_programa_componente_curricular_pessoa` (
  `id_programa_componente_curricular` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  PRIMARY KEY (`id_programa_componente_curricular`,`id_pessoa`),
  KEY `fk_siscc_programa_componente_curricular_has_sisrh_pessoa_si_idx` (`id_pessoa`),
  KEY `fk_siscc_programa_componente_curricular_has_sisrh_pessoa_si_idx1` (`id_programa_componente_curricular`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `siscc_semestre`
--

CREATE TABLE IF NOT EXISTS `siscc_semestre` (
  `id_semestre` int(11) NOT NULL AUTO_INCREMENT,
  `ano` int(7) NOT NULL,
  `semestre` int(2) NOT NULL,
  `remoto` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_semestre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisliga_integrante_externo`
--

CREATE TABLE IF NOT EXISTS `sisliga_integrante_externo` (
  `id_integrante_externo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instituicao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_integrante_externo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisliga_liga_academica`
--

CREATE TABLE IF NOT EXISTS `sisliga_liga_academica` (
  `id_liga_academica` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resumo` text COLLATE utf8_unicode_ci NOT NULL,
  `area_conhecimento` text COLLATE utf8_unicode_ci NOT NULL,
  `local_atuacao` text COLLATE utf8_unicode_ci NOT NULL,
  `arquivo_solicitacao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arquivo_regimento` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_aprovacao_comissao` date DEFAULT NULL,
  `data_homologacao_congregacao` date DEFAULT NULL,
  `sessao_congregacao` int(4) DEFAULT NULL,
  `tipo_sessao_congregacao` tinyint(1) DEFAULT NULL,
  `situacao` int(4) DEFAULT '0',
  PRIMARY KEY (`id_liga_academica`),
  KEY `fk_sisliga_liga_academica_sisrh_pessoa1` (`id_pessoa`),
  KEY `fk_sisliga_liga_academica_sisrh_setor1` (`id_setor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisliga_liga_integrante`
--

CREATE TABLE IF NOT EXISTS `sisliga_liga_integrante` (
  `id_liga_integrante` int(11) NOT NULL AUTO_INCREMENT,
  `id_liga_academica` int(11) NOT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `id_integrante_externo` int(11) DEFAULT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `funcao` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carga_horaria` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_liga_integrante`),
  KEY `fk_sisliga_liga_integrante_sisliga_liga_academica1` (`id_liga_academica`),
  KEY `fk_sisliga_liga_integrante_sisai_aluno1` (`id_aluno`),
  KEY `fk_sisliga_liga_integrante_sisliga_integrante_externo1` (`id_integrante_externo`),
  KEY `fk_sisliga_liga_integrante_sisrh_pessoa1` (`id_pessoa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisliga_parecer`
--

CREATE TABLE IF NOT EXISTS `sisliga_parecer` (
  `id_parecer` int(11) NOT NULL AUTO_INCREMENT,
  `id_liga_academica` int(11) DEFAULT NULL,
  `id_relatorio` int(11) DEFAULT NULL,
  `id_pessoa` int(11) NOT NULL,
  `parecer` text COLLATE utf8_unicode_ci,
  `data` date DEFAULT NULL,
  `atual` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_parecer`),
  KEY `fk_sisliga_parecer_sisliga_liga_academica1` (`id_liga_academica`),
  KEY `fk_sisliga_parecer_sisliga_relatorio1` (`id_relatorio`),
  KEY `fk_sisliga_parecer_sisrh_pessoa1` (`id_pessoa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisliga_relatorio`
--

CREATE TABLE IF NOT EXISTS `sisliga_relatorio` (
  `id_relatorio` int(11) NOT NULL AUTO_INCREMENT,
  `id_liga_academica` int(11) NOT NULL,
  `atividades` text COLLATE utf8_unicode_ci NOT NULL,
  `prestacao_contas` text COLLATE utf8_unicode_ci,
  `consideracoes_finais` text COLLATE utf8_unicode_ci,
  `data_aprovacao_comissao` date DEFAULT NULL,
  `data_homologacao_congregacao` date DEFAULT NULL,
  `sessao_congregacao` int(4) DEFAULT NULL,
  `tipo_sessao_congregacao` tinyint(1) DEFAULT NULL,
  `data_relatorio` date DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `situacao` int(4) DEFAULT NULL,
  `situacao_liga` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_relatorio`),
  KEY `fk_sisliga_relatorio_sisliga_liga_academica1` (`id_liga_academica`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_afastamento_docente`
--

CREATE TABLE IF NOT EXISTS `sispit_afastamento_docente` (
  `id_afastamento_docente` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `semestre` int(11) DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nivel_graduacao` int(11) DEFAULT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `pit_rit` int(1) DEFAULT NULL,
  `eh_afastamento` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_afastamento_docente`),
  KEY `fk_sispit_afastamento_docente_sispit_plano_relatorio1_idx` (`id_plano_relatorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_ano`
--

CREATE TABLE IF NOT EXISTS `sispit_ano` (
  `id_ano` int(11) NOT NULL AUTO_INCREMENT,
  `ano` int(4) NOT NULL,
  `suplementar` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ano`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_atividades_administrativas`
--

CREATE TABLE IF NOT EXISTS `sispit_atividades_administrativas` (
  `id_atividades_administrativas` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `tipo_atividade` int(11) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `descricao` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_atividades_administrativas`),
  KEY `fk_atv_adm_academica` (`tipo_atividade`),
  KEY `fk_sispit_atividades_administrativas_sispit_plano_relatorio_idx` (`id_plano_relatorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_atividade_complementar`
--

CREATE TABLE IF NOT EXISTS `sispit_atividade_complementar` (
  `id_plano_relatorio` int(11) NOT NULL,
  `ch_graduacao_sem1_pit` int(11) DEFAULT NULL,
  `ch_pos_sem1_pit` int(11) DEFAULT NULL,
  `ch_graduacao_sem2_pit` int(11) DEFAULT NULL,
  `ch_pos_sem2_pit` int(11) DEFAULT NULL,
  `ch_graduacao_sem1_rit` int(11) DEFAULT NULL,
  `ch_pos_sem1_rit` int(11) DEFAULT NULL,
  `ch_graduacao_sem2_rit` int(11) DEFAULT NULL,
  `ch_pos_sem2_rit` int(11) DEFAULT NULL,
  `ch_orientacao_academica_sem1_pit` int(11) DEFAULT NULL,
  `ch_orientacao_academica_sem2_pit` int(11) DEFAULT NULL,
  `ch_orientacao_academica_sem1_rit` int(11) DEFAULT NULL,
  `ch_orientacao_academica_sem2_rit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_plano_relatorio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_ensino_componente`
--

CREATE TABLE IF NOT EXISTS `sispit_ensino_componente` (
  `id_ensino_componente` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `id_componente_curricular` int(11) DEFAULT NULL,
  `nivel_graduacao` int(11) DEFAULT NULL,
  `semestre` int(1) DEFAULT NULL,
  `ch_teorica` int(11) DEFAULT NULL,
  `ch_pratica` int(11) DEFAULT NULL,
  `ch_estagio` int(11) DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_ensino_componente`),
  KEY `fk_sispit_ensino_componente_sispit_plano_relatorio1_idx` (`id_plano_relatorio`),
  KEY `fk_sispit_ensino_componente_siscc_componente1_idx` (`id_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_ensino_orientacao`
--

CREATE TABLE IF NOT EXISTS `sispit_ensino_orientacao` (
  `id_ensino_orientacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `semestre` int(11) DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `projeto` text COLLATE utf8_unicode_ci,
  `tipo_orientacao` int(11) NOT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_ensino_orientacao`),
  KEY `fk_sispit_ens_orientacao_sispit_plano_relatorio1_idx` (`id_plano_relatorio`),
  KEY `fk_sispit_ens_orientacao_sisai_aluno1_idx` (`id_aluno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_liga_academica`
--

CREATE TABLE IF NOT EXISTS `sispit_liga_academica` (
  `id_sispit_liga_academica` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `id_liga_academica` int(11) NOT NULL,
  `semestre` int(1) DEFAULT NULL,
  `funcao` int(1) DEFAULT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `pit_rit` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_sispit_liga_academica`),
  KEY `fk_sispit_liga_academica_sispit_plano_relatorio1_idx` (`id_plano_relatorio`),
  KEY `fk_sispit_liga_academica_sisliga_liga_academica1_idx` (`id_liga_academica`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_monitoria`
--

CREATE TABLE IF NOT EXISTS `sispit_monitoria` (
  `id_monitoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `semestre` int(11) DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `id_componente_curricular` int(11) DEFAULT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_monitoria`),
  KEY `fk_sispit_monitoria_sispit_plano_relatorio1_idx` (`id_plano_relatorio`),
  KEY `fk_sispit_monitoria_sisai_aluno1_idx` (`id_aluno`),
  KEY `fk_sispit_monitoria_siscc_componente1_idx` (`id_componente_curricular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_orientacao_academica`
--

CREATE TABLE IF NOT EXISTS `sispit_orientacao_academica` (
  `id_orientacao_academica` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `semestre` int(11) DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `pit_rit` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_orientacao_academica`),
  KEY `fk_sispit_orientacao_academica_sispit_plano_relatorio1_idx` (`id_plano_relatorio`),
  KEY `fk_sispit_orientacao_academica_sisai_aluno_idx` (`id_aluno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_parecer`
--

CREATE TABLE IF NOT EXISTS `sispit_parecer` (
  `id_parecer` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `tipo_parecerista` int(11) DEFAULT NULL,
  `parecer` text CHARACTER SET utf8,
  `data` date DEFAULT NULL,
  `atual` tinyint(4) DEFAULT '1',
  `pit_rit` int(1) DEFAULT '0',
  `comissao_pit_rit` tinyint(1) DEFAULT '0',
  `comentario` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_parecer`),
  KEY `fk_sispit_parecer_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_sispit_parecer_sispit_plano1_idx` (`id_plano_relatorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_participacao_evento`
--

CREATE TABLE IF NOT EXISTS `sispit_participacao_evento` (
  `id_participacao_evento` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `nome` text COLLATE utf8_unicode_ci,
  `semestre` int(11) DEFAULT NULL,
  `tipo_evento` int(11) DEFAULT NULL,
  `tipo_participacao_evento` int(11) DEFAULT NULL,
  `local` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_participacao_evento`),
  KEY `fk_tipo_evento` (`tipo_evento`),
  KEY `fk_tipo_participacao_evento` (`tipo_participacao_evento`),
  KEY `fk_sispit_participacao_evento_sispit_plano_relatorio1_idx` (`id_plano_relatorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_pesquisa_extensao`
--

CREATE TABLE IF NOT EXISTS `sispit_pesquisa_extensao` (
  `id_pesquisa_extensao` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `semestre` int(1) DEFAULT NULL,
  `tipo_participacao` int(11) DEFAULT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_pesquisa_extensao`),
  KEY `fk_sispit_pesquisa_extensao_sispit_plano_relatorio1_idx` (`id_plano_relatorio`),
  KEY `fk_sispit_pesquisa_extensao_sisape_projeto1_idx` (`id_projeto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_plano_relatorio`
--

CREATE TABLE IF NOT EXISTS `sispit_plano_relatorio` (
  `id_plano_relatorio` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `id_ano` int(11) NOT NULL,
  `observacao_pit` text COLLATE utf8_unicode_ci,
  `observacao_rit` text COLLATE utf8_unicode_ci,
  `justificativa` text COLLATE utf8_unicode_ci,
  `data_homologacao_nucleo_pit` date DEFAULT NULL,
  `data_homologacao_cac_pit` date DEFAULT NULL,
  `data_preenchimento_pit` date DEFAULT NULL COMMENT '	',
  `data_homologacao_nucleo_rit` date DEFAULT NULL,
  `data_homologacao_cac_rit` date DEFAULT NULL,
  `data_preenchimento_rit` date DEFAULT NULL COMMENT '	',
  `status` int(11) DEFAULT NULL,
  `situacao_estagio_probatorio` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_plano_relatorio`),
  UNIQUE KEY `unique_ano_pessoa` (`id_ano`,`user_id`),
  KEY `fk_sispit_plano_sisrh_pessoa1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sispit_publicacao`
--

CREATE TABLE IF NOT EXISTS `sispit_publicacao` (
  `id_publicacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_relatorio` int(11) NOT NULL,
  `titulo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `editora` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `local` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fonte_financiadora` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pit_rit` int(1) DEFAULT '0',
  PRIMARY KEY (`id_publicacao`),
  KEY `fk_sispit_publicacao_sispit_plano_relatorio1_idx` (`id_plano_relatorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_afastamento`
--

CREATE TABLE IF NOT EXISTS `sisrh_afastamento` (
  `id_afastamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `inicio` date NOT NULL,
  `termino` date DEFAULT NULL,
  `id_ocorrencia` int(11) NOT NULL,
  `observacao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_afastamento`),
  KEY `afastamento_pessoa` (`id_pessoa`),
  KEY `justificativa` (`id_ocorrencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='No sistema, afastamento foi renomeado para ocorrência.';

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_cargo`
--

CREATE TABLE IF NOT EXISTS `sisrh_cargo` (
  `id_cargo` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `atribuicoes` text COLLATE utf8_unicode_ci,
  `id_categoria` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_cargo`),
  KEY `fk_categoria` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_categoria`
--

CREATE TABLE IF NOT EXISTS `sisrh_categoria` (
  `id_categoria` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_classe_funcional`
--

CREATE TABLE IF NOT EXISTS `sisrh_classe_funcional` (
  `id_classe_funcional` int(11) NOT NULL AUTO_INCREMENT,
  `denominacao` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `id_categoria` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_classe_funcional`),
  KEY `fkcategoria` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_comissao`
--

CREATE TABLE IF NOT EXISTS `sisrh_comissao` (
  `id_comissao` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `observacao` text COLLATE utf8_unicode_ci,
  `eh_comissao_pit_rit` tinyint(1) DEFAULT '0',
  `eh_comissao_liga` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_comissao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_comissao_pessoa`
--

CREATE TABLE IF NOT EXISTS `sisrh_comissao_pessoa` (
  `id_comissao` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `funcao` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_comissao`,`id_pessoa`),
  KEY `fk_sisrh_comissao_has_sisrh_pessoa_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_sisrh_comissao_has_sisrh_pessoa_sisrh_comissao1_idx` (`id_comissao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_estado`
--

CREATE TABLE IF NOT EXISTS `sisrh_estado` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `sigla` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_historico_funcional`
--

CREATE TABLE IF NOT EXISTS `sisrh_historico_funcional` (
  `id_historico_funcional` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `id_setor` int(11) DEFAULT NULL,
  `id_comissao` int(11) DEFAULT NULL,
  `funcao` int(4) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  PRIMARY KEY (`id_historico_funcional`),
  KEY `fk_sisrh_historico_coordenacoes_sisrh_setor1_idx` (`id_setor`),
  KEY `fk_sisrh_historico_coordenacoes_sisrh_pessoa1_idx` (`id_pessoa`),
  KEY `fk_sisrh_historico_funcional_sisrh_comissao1_idx` (`id_comissao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_municipio`
--

CREATE TABLE IF NOT EXISTS `sisrh_municipio` (
  `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`id_municipio`),
  KEY `fk_estado` (`id_estado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_ocorrencia`
--

CREATE TABLE IF NOT EXISTS `sisrh_ocorrencia` (
  `id_ocorrencia` int(11) NOT NULL AUTO_INCREMENT,
  `justificativa` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_ocorrencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Justificativas para as ocorrências da tab sisrh_afastamento';

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_pessoa`
--

CREATE TABLE IF NOT EXISTS `sisrh_pessoa` (
  `id_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `siape` int(11) DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dt_nascimento` date DEFAULT NULL,
  `sexo` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado_civil` int(11) DEFAULT '10',
  `escolaridade` int(4) DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emails` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regime_trabalho` int(11) DEFAULT NULL,
  `dt_ingresso_orgao` date DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_classe_funcional` int(11) DEFAULT NULL,
  `situacao` int(4) DEFAULT NULL,
  `dt_exercicio` date DEFAULT NULL,
  `dt_vigencia` date DEFAULT NULL,
  `id_pessoa_vinculada` int(11) DEFAULT NULL,
  `cpf` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`),
  UNIQUE KEY `un_siape` (`siape`),
  KEY `fk_user` (`id_user`),
  KEY `fk_cargo` (`id_cargo`),
  KEY `fk_classe` (`id_classe_funcional`),
  KEY `fk_pessoa` (`id_pessoa_vinculada`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_setor`
--

CREATE TABLE IF NOT EXISTS `sisrh_setor` (
  `id_setor` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_setor_responsavel` int(11) DEFAULT NULL,
  `codigo` int(11) DEFAULT NULL,
  `sigla` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ramais` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eh_colegiado` tinyint(1) DEFAULT '0',
  `eh_nucleo_academico` tinyint(1) DEFAULT '0',
  `observacao` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_setor`),
  KEY `fk_responsavel` (`id_setor_responsavel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_setor_membro_automatico`
--

CREATE TABLE IF NOT EXISTS `sisrh_setor_membro_automatico` (
  `id_membro_automatico` int(11) NOT NULL AUTO_INCREMENT,
  `id_setor_origem` int(11) NOT NULL,
  `funcao_origem` int(4) NOT NULL,
  `id_setor_destino` int(11) NOT NULL,
  `funcao_destino` int(4) NOT NULL,
  PRIMARY KEY (`id_membro_automatico`),
  KEY `fk_setor_origem` (`id_setor_origem`),
  KEY `fk_setor_destino` (`id_setor_destino`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sisrh_setor_pessoa`
--

CREATE TABLE IF NOT EXISTS `sisrh_setor_pessoa` (
  `id_setor` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `funcao` int(4) NOT NULL,
  PRIMARY KEY (`id_setor`,`id_pessoa`),
  KEY `fk_pessoa` (`id_pessoa`),
  KEY `fk_setor` (`id_setor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `social_account`
--

CREATE TABLE IF NOT EXISTS `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_unique` (`provider`,`client_id`),
  UNIQUE KEY `account_unique_code` (`code`),
  KEY `fk_user_account` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `last_login_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_username` (`username`),
  UNIQUE KEY `user_unique_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para a tabela `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `auth_item_sisrh_setor`
--
ALTER TABLE `auth_item_sisrh_setor`
  ADD CONSTRAINT `auth_item_sisrh_setor_ibfk_1` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_sisrh_setor_ibfk_2` FOREIGN KEY (`id_comissao`) REFERENCES `sisrh_comissao` (`id_comissao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auth_item_has_sisrh_setor_auth_item1` FOREIGN KEY (`name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para a tabela `sisai_aluno`
--
ALTER TABLE `sisai_aluno`
  ADD CONSTRAINT `fk_sisai_aluno_sisrh_setor1` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_aluno_ibfk_1` FOREIGN KEY (`id_semestre`) REFERENCES `siscc_semestre` (`id_semestre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_aluno_componente_curricular`
--
ALTER TABLE `sisai_aluno_componente_curricular`
  ADD CONSTRAINT `sisai_aluno_componente_curricular_ibfk_1` FOREIGN KEY (`id_componente_curricular`) REFERENCES `siscc_componente_curricular` (`id_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_aluno_componente_curricular_ibfk_2` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_aluno_componente_curricular_ibfk_3` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_aluno_componente_curricular_ibfk_4` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_aluno_resposta_objetiva`
--
ALTER TABLE `sisai_aluno_resposta_objetiva`
  ADD CONSTRAINT `fk_sisai_resposta_objetiva_sisai_avaliacao1` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_resposta_objetiva_sisai_pergunta1` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_aluno_resposta_objetiva_ibfk_1` FOREIGN KEY (`id_aluno_componente_curricular`) REFERENCES `sisai_aluno_componente_curricular` (`id_aluno_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_aluno_resposta_subjetiva`
--
ALTER TABLE `sisai_aluno_resposta_subjetiva`
  ADD CONSTRAINT `fk_sisai_resposta_sujetiva_sisai_avaliacao1` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_resposta_sujetiva_sisai_pergunta1` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_aluno_resposta_subjetiva_ibfk_1` FOREIGN KEY (`id_aluno_componente_curricular`) REFERENCES `sisai_aluno_componente_curricular` (`id_aluno_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_avaliacao`
--
ALTER TABLE `sisai_avaliacao`
  ADD CONSTRAINT `fk_sisai_avaliacao_sisai_aluno1` FOREIGN KEY (`id_aluno`) REFERENCES `sisai_aluno` (`id_aluno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_avaliacao_siscc_semestre1` FOREIGN KEY (`id_semestre`) REFERENCES `siscc_semestre` (`id_semestre`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_avaliacao_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_colegiado_semestre_atuv`
--
ALTER TABLE `sisai_colegiado_semestre_atuv`
  ADD CONSTRAINT `fk_sisai_colegiado_semestre_atuv_siscc_semestre1` FOREIGN KEY (`id_semestre`) REFERENCES `siscc_semestre` (`id_semestre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_grupo_perguntas`
--
ALTER TABLE `sisai_grupo_perguntas`
  ADD CONSTRAINT `sisai_grupo_perguntas_ibfk_1` FOREIGN KEY (`id_questionario`) REFERENCES `sisai_questionario` (`id_questionario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_pergunta`
--
ALTER TABLE `sisai_pergunta`
  ADD CONSTRAINT `sisai_pergunta_ibfk_1` FOREIGN KEY (`id_grupo_perguntas`) REFERENCES `sisai_grupo_perguntas` (`id_grupo_perguntas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_periodo_avaliacao`
--
ALTER TABLE `sisai_periodo_avaliacao`
  ADD CONSTRAINT `sisai_periodo_avaliacao_ibfk_1` FOREIGN KEY (`id_semestre`) REFERENCES `siscc_semestre` (`id_semestre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_professor_componente_curricular`
--
ALTER TABLE `sisai_professor_componente_curricular`
  ADD CONSTRAINT `sisai_professor_componente_curricular_ibfk_1` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_professor_componente_curricular_ibfk_2` FOREIGN KEY (`id_componente_curricular`) REFERENCES `siscc_componente_curricular` (`id_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_professor_componente_curricular_ibfk_3` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_professor_resposta_objetiva`
--
ALTER TABLE `sisai_professor_resposta_objetiva`
  ADD CONSTRAINT `fk_sisai_resposta_objetiva_sisai_avaliacao10` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_resposta_objetiva_sisai_pergunta10` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_professor_resposta_objetiva_ibfk_1` FOREIGN KEY (`id_professor_componente_curricular`) REFERENCES `sisai_professor_componente_curricular` (`id_professor_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_professor_resposta_subjetiva`
--
ALTER TABLE `sisai_professor_resposta_subjetiva`
  ADD CONSTRAINT `fk_sisai_resposta_sujetiva_sisai_avaliacao10` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_resposta_sujetiva_sisai_pergunta10` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisai_professor_resposta_subjetiva_ibfk_1` FOREIGN KEY (`id_professor_componente_curricular`) REFERENCES `sisai_professor_componente_curricular` (`id_professor_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_resposta_multipla_escolha`
--
ALTER TABLE `sisai_resposta_multipla_escolha`
  ADD CONSTRAINT `sisai_resposta_multipla_escolha_ibfk_1` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_tecnico_resposta_objetiva`
--
ALTER TABLE `sisai_tecnico_resposta_objetiva`
  ADD CONSTRAINT `fk_sisai_resposta_objetiva_sisai_avaliacao100` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_resposta_objetiva_sisai_pergunta100` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisai_tecnico_resposta_subjetiva`
--
ALTER TABLE `sisai_tecnico_resposta_subjetiva`
  ADD CONSTRAINT `fk_sisai_resposta_sujetiva_sisai_avaliacao100` FOREIGN KEY (`id_avaliacao`) REFERENCES `sisai_avaliacao` (`id_avaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisai_resposta_sujetiva_sisai_pergunta100` FOREIGN KEY (`id_pergunta`) REFERENCES `sisai_pergunta` (`id_pergunta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisape_atividade`
--
ALTER TABLE `sisape_atividade`
  ADD CONSTRAINT `fk_sispit_atividade_sisape_projeto1` FOREIGN KEY (`id_projeto`) REFERENCES `sisape_projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisape_financiamento`
--
ALTER TABLE `sisape_financiamento`
  ADD CONSTRAINT `fk_sisape_financiamento_sisape_projeto1` FOREIGN KEY (`id_projeto`) REFERENCES `sisape_projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisape_parecer`
--
ALTER TABLE `sisape_parecer`
  ADD CONSTRAINT `fk_sisape_parecer_sisape_projeto1` FOREIGN KEY (`id_projeto`) REFERENCES `sisape_projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisape_parecer_sisape_relatorio1` FOREIGN KEY (`id_relatorio`) REFERENCES `sisape_relatorio` (`id_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisape_parecer_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisape_projeto`
--
ALTER TABLE `sisape_projeto`
  ADD CONSTRAINT `sisape_projeto_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sisape_projeto_ibfk_2` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisape_projeto_integrante`
--
ALTER TABLE `sisape_projeto_integrante`
  ADD CONSTRAINT `fk_sisape_projeto_integrante_sisape_integrante_externo1` FOREIGN KEY (`id_integrante_externo`) REFERENCES `sisape_integrante_externo` (`id_integrante_externo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisape_projeto_integrante_sisape_projeto1` FOREIGN KEY (`id_projeto`) REFERENCES `sisape_projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisape_projeto_integrante_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisape_relatorio`
--
ALTER TABLE `sisape_relatorio`
  ADD CONSTRAINT `fk_sisape_relatorio_sisape_projeto1` FOREIGN KEY (`id_projeto`) REFERENCES `sisape_projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `siscc_parecer`
--
ALTER TABLE `siscc_parecer`
  ADD CONSTRAINT `siscc_parecer_ibfk_1` FOREIGN KEY (`id_programa_componente_curricular`) REFERENCES `siscc_programa_componente_curricular` (`id_programa_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siscc_parecer_ibfk_2` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `siscc_programa_componente_curricular`
--
ALTER TABLE `siscc_programa_componente_curricular`
  ADD CONSTRAINT `fk_siscc_componente_curricular_has_sisrh_setor_siscc_componen1` FOREIGN KEY (`id_componente_curricular`) REFERENCES `siscc_componente_curricular` (`id_componente_curricular`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siscc_componente_curricular_has_sisrh_setor_sisrh_setor1` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siscc_programa_componente_curricular_siscc_semestre1` FOREIGN KEY (`id_semestre`) REFERENCES `siscc_semestre` (`id_semestre`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `siscc_programa_componente_curricular_bibliografia`
--
ALTER TABLE `siscc_programa_componente_curricular_bibliografia`
  ADD CONSTRAINT `fk_siscc_programa_componente_curricular_has_siscc_bibliografi1` FOREIGN KEY (`id_programa_componente_curricular`) REFERENCES `siscc_programa_componente_curricular` (`id_programa_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siscc_programa_componente_curricular_has_siscc_bibliografi2` FOREIGN KEY (`id_bibliografia`) REFERENCES `siscc_bibliografia` (`id_bibliografia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `siscc_programa_componente_curricular_pessoa`
--
ALTER TABLE `siscc_programa_componente_curricular_pessoa`
  ADD CONSTRAINT `siscc_programa_componente_curricular_pessoa_ibfk_1` FOREIGN KEY (`id_programa_componente_curricular`) REFERENCES `siscc_programa_componente_curricular` (`id_programa_componente_curricular`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siscc_programa_componente_curricular_pessoa_ibfk_2` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisliga_liga_academica`
--
ALTER TABLE `sisliga_liga_academica`
  ADD CONSTRAINT `fk_sisliga_liga_academica_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisliga_liga_academica_sisrh_setor1` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisliga_liga_integrante`
--
ALTER TABLE `sisliga_liga_integrante`
  ADD CONSTRAINT `fk_sisliga_liga_integrante_sisai_aluno1` FOREIGN KEY (`id_aluno`) REFERENCES `sisai_aluno` (`id_aluno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisliga_liga_integrante_sisliga_integrante_externo1` FOREIGN KEY (`id_integrante_externo`) REFERENCES `sisliga_integrante_externo` (`id_integrante_externo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisliga_liga_integrante_sisliga_liga_academica1` FOREIGN KEY (`id_liga_academica`) REFERENCES `sisliga_liga_academica` (`id_liga_academica`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisliga_liga_integrante_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisliga_parecer`
--
ALTER TABLE `sisliga_parecer`
  ADD CONSTRAINT `fk_sisliga_parecer_sisliga_liga_academica1` FOREIGN KEY (`id_liga_academica`) REFERENCES `sisliga_liga_academica` (`id_liga_academica`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisliga_parecer_sisliga_relatorio1` FOREIGN KEY (`id_relatorio`) REFERENCES `sisliga_relatorio` (`id_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisliga_parecer_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisliga_relatorio`
--
ALTER TABLE `sisliga_relatorio`
  ADD CONSTRAINT `fk_sisliga_relatorio_sisliga_liga_academica1` FOREIGN KEY (`id_liga_academica`) REFERENCES `sisliga_liga_academica` (`id_liga_academica`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_afastamento_docente`
--
ALTER TABLE `sispit_afastamento_docente`
  ADD CONSTRAINT `fk_sispit_afastamento_docente_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_atividades_administrativas`
--
ALTER TABLE `sispit_atividades_administrativas`
  ADD CONSTRAINT `fk_sispit_atividades_administrativas_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_atividade_complementar`
--
ALTER TABLE `sispit_atividade_complementar`
  ADD CONSTRAINT `fk_sispit_atividade_complementar_sispit_plano1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_ensino_componente`
--
ALTER TABLE `sispit_ensino_componente`
  ADD CONSTRAINT `fk_sispit_ensino_componente_siscc_componente1` FOREIGN KEY (`id_componente_curricular`) REFERENCES `siscc_componente_curricular` (`id_componente_curricular`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_ensino_componente_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_ensino_orientacao`
--
ALTER TABLE `sispit_ensino_orientacao`
  ADD CONSTRAINT `fk_sispit_ens_orientacao_sisai_aluno1` FOREIGN KEY (`id_aluno`) REFERENCES `sisai_aluno` (`id_aluno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_ens_orientacao_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_liga_academica`
--
ALTER TABLE `sispit_liga_academica`
  ADD CONSTRAINT `fk_sispit_liga_academica_sisliga_liga_academica1` FOREIGN KEY (`id_liga_academica`) REFERENCES `sisliga_liga_academica` (`id_liga_academica`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_liga_academica_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_monitoria`
--
ALTER TABLE `sispit_monitoria`
  ADD CONSTRAINT `fk_sispit_monitoria_sisai_aluno1` FOREIGN KEY (`id_aluno`) REFERENCES `sisai_aluno` (`id_aluno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_monitoria_siscc_componente1` FOREIGN KEY (`id_componente_curricular`) REFERENCES `siscc_componente_curricular` (`id_componente_curricular`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_monitoria_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_orientacao_academica`
--
ALTER TABLE `sispit_orientacao_academica`
  ADD CONSTRAINT `fk_sispit_orientacao_academica_sisai_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `sisai_aluno` (`id_aluno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_orientacao_academica_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_parecer`
--
ALTER TABLE `sispit_parecer`
  ADD CONSTRAINT `fk_sispit_parecer_sispit_plano1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sispit_parecer_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_participacao_evento`
--
ALTER TABLE `sispit_participacao_evento`
  ADD CONSTRAINT `fk_sispit_participacao_evento_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_pesquisa_extensao`
--
ALTER TABLE `sispit_pesquisa_extensao`
  ADD CONSTRAINT `fk_sispit_pesquisa_extensao_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sispit_pesquisa_extensao_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `sisape_projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sispit_plano_relatorio`
--
ALTER TABLE `sispit_plano_relatorio`
  ADD CONSTRAINT `sispit_plano_relatorio_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sispit_plano_relatorio_ibfk_3` FOREIGN KEY (`id_ano`) REFERENCES `sispit_ano` (`id_ano`);

--
-- Restrições para a tabela `sispit_publicacao`
--
ALTER TABLE `sispit_publicacao`
  ADD CONSTRAINT `fk_sispit_publicacao_sispit_plano_relatorio1` FOREIGN KEY (`id_plano_relatorio`) REFERENCES `sispit_plano_relatorio` (`id_plano_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_afastamento`
--
ALTER TABLE `sisrh_afastamento`
  ADD CONSTRAINT `afastamento_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisrh_afastamento_ibfk_1` FOREIGN KEY (`id_ocorrencia`) REFERENCES `sisrh_ocorrencia` (`id_ocorrencia`);

--
-- Restrições para a tabela `sisrh_cargo`
--
ALTER TABLE `sisrh_cargo`
  ADD CONSTRAINT `sisrh_cargo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `sisrh_categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_classe_funcional`
--
ALTER TABLE `sisrh_classe_funcional`
  ADD CONSTRAINT `sisrh_classe_funcional_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `sisrh_categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_comissao_pessoa`
--
ALTER TABLE `sisrh_comissao_pessoa`
  ADD CONSTRAINT `fk_sisrh_comissao_has_sisrh_pessoa_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sisrh_comissao_pessoa_ibfk_1` FOREIGN KEY (`id_comissao`) REFERENCES `sisrh_comissao` (`id_comissao`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_historico_funcional`
--
ALTER TABLE `sisrh_historico_funcional`
  ADD CONSTRAINT `fk_sisrh_historico_coordenacoes_sisrh_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisrh_historico_coordenacoes_sisrh_setor1` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sisrh_historico_funcional_sisrh_comissao1` FOREIGN KEY (`id_comissao`) REFERENCES `sisrh_comissao` (`id_comissao`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_municipio`
--
ALTER TABLE `sisrh_municipio`
  ADD CONSTRAINT `fk_estado` FOREIGN KEY (`id_estado`) REFERENCES `sisrh_estado` (`id_estado`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_pessoa`
--
ALTER TABLE `sisrh_pessoa`
  ADD CONSTRAINT `fk_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `sisrh_cargo` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classe` FOREIGN KEY (`id_classe_funcional`) REFERENCES `sisrh_classe_funcional` (`id_classe_funcional`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisrh_pessoa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sisrh_pessoa_ibfk_2` FOREIGN KEY (`id_pessoa_vinculada`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_setor`
--
ALTER TABLE `sisrh_setor`
  ADD CONSTRAINT `sisrh_setor_ibfk_1` FOREIGN KEY (`id_setor_responsavel`) REFERENCES `sisrh_setor` (`id_setor`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_setor_membro_automatico`
--
ALTER TABLE `sisrh_setor_membro_automatico`
  ADD CONSTRAINT `sisrh_setor_membro_automatico_ibfk_1` FOREIGN KEY (`id_setor_origem`) REFERENCES `sisrh_setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sisrh_setor_membro_automatico_ibfk_2` FOREIGN KEY (`id_setor_destino`) REFERENCES `sisrh_setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `sisrh_setor_pessoa`
--
ALTER TABLE `sisrh_setor_pessoa`
  ADD CONSTRAINT `sisrh_setor_pessoa_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `sisrh_pessoa` (`id_pessoa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sisrh_setor_pessoa_ibfk_2` FOREIGN KEY (`id_setor`) REFERENCES `sisrh_setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `social_account`
--
ALTER TABLE `social_account`
  ADD CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para a tabela `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
