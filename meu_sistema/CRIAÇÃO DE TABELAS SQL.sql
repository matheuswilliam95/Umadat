CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NULL,
    documento_identificacao VARCHAR(50) NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    congregacao_id INT NULL,
    conjunto_id INT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (congregacao_id) REFERENCES congregacoes(id) ON DELETE SET NULL,
    FOREIGN KEY (conjunto_id) REFERENCES conjuntos(id) ON DELETE SET NULL
);

CREATE TABLE congregacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    regional_id INT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (regional_id) REFERENCES hierarquia(id) ON DELETE SET NULL
);

CREATE TABLE conjuntos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    congregacao_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (congregacao_id) REFERENCES congregacoes(id) ON DELETE CASCADE
);


CREATE TABLE hierarquia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('campo', 'regional', 'congregacao', 'conjunto') NOT NULL,
    parent_id INT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES hierarquia(id) ON DELETE SET NULL
);

CREATE TABLE usuarios_hierarquia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    hierarquia_id INT NOT NULL,
    papel ENUM('super_admin', 'admin_regional', 'admin_congregacao', 'moderador', 'usuario') NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (hierarquia_id) REFERENCES hierarquia(id) ON DELETE CASCADE
);

CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    data_inicio DATE NOT NULL,
    horario_inicio TIME NULL,
    data_fim DATE NOT NULL,
    horario_fim TIME NULL,
    local VARCHAR(255) NULL,
    valor DECIMAL(10,2) NULL,
    data_limite_inscricao DATE NULL,
    responsavel_nome VARCHAR(100) NULL,
    responsavel_contato VARCHAR(50) NULL,
    tipo ENUM('publico', 'restrito') DEFAULT 'publico',
    criado_por INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criado_por) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE eventos_hierarquia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT NOT NULL,
    hierarquia_id INT NOT NULL,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (hierarquia_id) REFERENCES hierarquia(id) ON DELETE CASCADE
);

CREATE TABLE inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    evento_id INT NOT NULL,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE
);

CREATE TABLE instagram (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hierarquia_id INT NOT NULL,
    perfil_instagram VARCHAR(100) NOT NULL,
    token_acesso TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hierarquia_id) REFERENCES hierarquia(id) ON DELETE CASCADE
);




-- CREATE DATABASE igreja_rede_social;
-- USE igreja_rede_social;

-- -- Tabela de usuários
-- CREATE TABLE usuarios (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(100) NOT NULL,
--     email VARCHAR(100) UNIQUE NOT NULL,
--     senha VARCHAR(255) NOT NULL,
--     telefone VARCHAR(20),
--     tipo ENUM('admin_campo', 'admin_regional', 'admin_congregacao', 'usuario') NOT NULL,
--     congregacao_id INT,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela de congregações
-- CREATE TABLE congregacoes (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(100) NOT NULL,
--     regional_id INT,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela de regionais
-- CREATE TABLE regionais (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(100) NOT NULL,
--     campo_id INT,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela do campo
-- CREATE TABLE campos (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(100) NOT NULL,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela de conjuntos
-- CREATE TABLE conjuntos (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(100) NOT NULL,
--     congregacao_id INT NOT NULL,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela de eventos
-- CREATE TABLE eventos (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     titulo VARCHAR(255) NOT NULL,
--     descricao TEXT NOT NULL,
--     data_evento DATETIME NOT NULL,
--     tipo ENUM('campo', 'regional', 'congregacao', 'conjunto') NOT NULL,
--     publico BOOLEAN DEFAULT 1,
--     criado_por INT NOT NULL,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela de inscrições nos eventos
-- CREATE TABLE inscricoes (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     usuario_id INT NOT NULL,
--     evento_id INT NOT NULL,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Tabela de publicações vinculadas ao Instagram
-- CREATE TABLE publicacoes (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     usuario_id INT NOT NULL,
--     url VARCHAR(255) NOT NULL,
--     descricao TEXT,
--     criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Definição de chaves estrangeiras
-- ALTER TABLE usuarios ADD CONSTRAINT fk_usuario_congregacao FOREIGN KEY (congregacao_id) REFERENCES congregacoes(id);
-- ALTER TABLE congregacoes ADD CONSTRAINT fk_congregacao_regional FOREIGN KEY (regional_id) REFERENCES regionais(id);
-- ALTER TABLE regionais ADD CONSTRAINT fk_regional_campo FOREIGN KEY (campo_id) REFERENCES campos(id);
-- ALTER TABLE conjuntos ADD CONSTRAINT fk_conjunto_congregacao FOREIGN KEY (congregacao_id) REFERENCES congregacoes(id);
-- ALTER TABLE eventos ADD CONSTRAINT fk_evento_criador FOREIGN KEY (criado_por) REFERENCES usuarios(id);
-- ALTER TABLE inscricoes ADD CONSTRAINT fk_inscricao_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id);
-- ALTER TABLE inscricoes ADD CONSTRAINT fk_inscricao_evento FOREIGN KEY (evento_id) REFERENCES eventos(id);
-- ALTER TABLE publicacoes ADD CONSTRAINT fk_publicacao_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id);

