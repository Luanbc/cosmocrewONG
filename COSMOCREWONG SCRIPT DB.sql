CREATE SCHEMA cosmocrewONG;
USE cosmocrewONG;

-- GRANTS UTILIZADOS
CREATE USER 'admin_cosmocrew'@'localhost' IDENTIFIED BY 'Extreme07@';
CREATE USER 'gerente_cosmocrew'@'localhost' IDENTIFIED BY 'Gerente2024';
CREATE USER 'voluntario_cosmocrew'@'localhost' IDENTIFIED BY 'Voluntario2024';
CREATE USER 'usuario@localhost' IDENTIFIED BY '';

GRANT ALL PRIVILEGES ON cosmocrewong.* TO 'admin_cosmocrew'@'localhost' WITH GRANT OPTION; 
GRANT SELECT, INSERT, UPDATE, DELETE ON cosmocrewong.projeto_ativo TO 'gerente'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON cosmocrewong.animais TO 'gerente'@'localhost';

SHOW GRANTS FOR 'admin_cosmocrew'@'localhost';
FLUSH PRIVILEGES;


-- VERIFICAR TABELAS E REPARAR
USE mysql;
CHECK TABLE db;
REPAIR TABLE db;

CHECK TABLE tables_priv;
REPAIR TABLE tables_priv;


-- TABELAS

CREATE TABLE usuario (
    usuario_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(40) NOT NULL,
    email VARCHAR(40) NOT NULL,
    telefone VARCHAR(15),
    data_nascimento DATE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'gerente', 'usuario') DEFAULT 'usuario',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

update usuario
SET tipo_usuario = 'admin'
where usuario_id = 1;

CREATE TABLE projeto_ativo (
    projeto_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE,
    status ENUM('ativo') DEFAULT 'ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id)
);

CREATE TABLE projeto_usuarios (
  projeto_usuario_id INT AUTO_INCREMENT PRIMARY KEY,
  projeto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  data_inscricao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  KEY projeto_id (projeto_id),
  KEY usuario_id (usuario_id),
  FOREIGN KEY (projeto_id) REFERENCES projeto_ativo(projeto_id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id) ON DELETE CASCADE
);

CREATE TABLE projeto_finalizado (
	finalizado_id INT PRIMARY KEY,
    projeto_id INT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    status ENUM('finalizado') DEFAULT 'finalizado',
    data_finalizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (projeto_id) REFERENCES projeto_ativo(projeto_id) ON DELETE SET NULL
);


CREATE TABLE historico_projeto (
    historico_id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE,
    status ENUM('ativo', 'finalizado') NOT NULL, -- Removido 'excluido'
    acao ENUM('criado', 'editado', 'finalizado') NOT NULL,
    data_acao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (projeto_id) REFERENCES projeto_ativo(projeto_id) ON DELETE SET NULL
);

CREATE TABLE animais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT UNIQUE,
    nome VARCHAR(30) NOT NULL,
    especie VARCHAR(30) NOT NULL,
    raca VARCHAR(30),
    idade INT,
    descricao TEXT,
    foto_url VARCHAR(255),
    data_adicionado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE adocoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    usuario_id INT DEFAULT NULL,
    nome VARCHAR(40) NOT NULL,
    email VARCHAR(40) NOT NULL,
    telefone VARCHAR(30),
    endereco VARCHAR(255),
    status ENUM('pendente', 'aprovada', 'rejeitada') DEFAULT 'pendente',
    data_aprovacao TIMESTAMP NULL,
    data_adocao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
); 


ALTER TABLE adocoes ADD INDEX idx_status_adocao (status);



CREATE TABLE fotos_adocao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    adocao_id INT NOT NULL,
    caminho_foto VARCHAR(255) NOT NULL,
    FOREIGN KEY (adocao_id) REFERENCES adocoes(id) ON DELETE CASCADE
);


CREATE TABLE historico_adocoes (
    id_adocao INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    animal_nome VARCHAR(40) NOT NULL,
    adotante_nome VARCHAR(40) NOT NULL,
    adotante_email VARCHAR(40),
    adotante_telefone VARCHAR(30),
    adotante_endereco VARCHAR(255),
    data_adocao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- PROCEDURES 

DELIMITER $$

CREATE PROCEDURE inserir_usuario(
    IN p_nome VARCHAR(40),
    IN p_email VARCHAR(40),
    IN p_senha VARCHAR(255),
    IN p_tipo_usuario ENUM('admin', 'voluntario', 'gerente', 'usuario'),
    IN p_telefone VARCHAR(15),  -- Novo parâmetro para telefone
    IN p_data_nascimento DATE   -- Novo parâmetro para data de nascimento
)
BEGIN
    -- Se o tipo de usuário não for passado (NULL), ele vai definir por padrão como 'usuario'
    IF p_tipo_usuario IS NULL THEN
        SET p_tipo_usuario = 'usuario';
    END IF;

    -- Inserção do usuário
    INSERT INTO usuario (nome, email, senha, tipo_usuario, telefone, data_nascimento)
    VALUES (p_nome, p_email, p_senha, p_tipo_usuario, p_telefone, p_data_nascimento);
END $$

DELIMITER ;


DELIMITER //
CREATE PROCEDURE inscrever_voluntario (
    IN p_projeto_id INT,
    IN p_usuario_id INT
)
BEGIN
    DECLARE projeto_existente INT;
    DECLARE usuario_existente INT;
    
    -- Verifica se o projeto existe
    SELECT COUNT(*) INTO projeto_existente 
    FROM projeto_ativo
    WHERE projeto_id = p_projeto_id;
    
    -- Verifica se o usuário existe
    SELECT COUNT(*) INTO usuario_existente 
    FROM usuario 
    WHERE usuario_id = p_usuario_id;
    
    -- Se ambos existirem, insere na tabela de inscrição de voluntários
    IF projeto_existente > 0 AND usuario_existente > 0 THEN
        INSERT INTO projeto_usuarios (projeto_id, usuario_id)
        VALUES (p_projeto_id, p_usuario_id);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Projeto ou usuário não encontrado.';
    END IF;
END //
DELIMITER ;


DELIMITER $$

CREATE PROCEDURE cadastrar_projeto(
    IN p_titulo VARCHAR(255),
    IN p_descricao TEXT,
    IN p_data_inicio DATE,
    IN p_data_fim DATE
)
BEGIN
    DECLARE projeto_id INT;

    -- Inserir projeto na tabela de projetos ativos
    INSERT INTO projeto_ativo (titulo, descricao, data_inicio, data_fim, status)
    VALUES (p_titulo, p_descricao, p_data_inicio, p_data_fim, 'ativo');

    -- Obter o ID do projeto recém-inserido
    SET projeto_id = LAST_INSERT_ID();

    -- Retornar o ID do projeto
    SELECT projeto_id;
END $$

DELIMITER ;

-- TRIGGERS
DELIMITER //

CREATE TRIGGER verificar_email_duplicado
BEFORE INSERT ON usuario
FOR EACH ROW
BEGIN
    IF EXISTS (SELECT 1 FROM usuario WHERE email = NEW.email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Este e-mail já está registrado.';
    END IF;
END //

DELIMITER ;




