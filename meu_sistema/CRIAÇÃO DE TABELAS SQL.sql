CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NULL,
    documento_identificacao VARCHAR(50) NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
