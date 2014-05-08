CREATE TABLE tb_place (
  `id_place` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `phone` VARCHAR(45) NULL,
  `email` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NULL,
  `adress` VARCHAR(45) NULL,
  `fb_id` VARCHAR(45) NULL,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  
  PRIMARY KEY (`id_place`) /* digo que ele será minha PK */
) ENGINE=INNODB;

CREATE TABLE tb_event (
  `id_event` INT NOT NULL AUTO_INCREMENT,
  `fb_id` VARCHAR(45) NOT NULL,
  `place_id` INT(11) NOT NULL,
  `owner_id` VARCHAR(45) NOT NULL,

  PRIMARY KEY(`id_event`),
    
  INDEX (`place_id`), /* Defino o campo como index (necessário para definí-lo posteriormente como pl) */

  FOREIGN KEY (`place_id`) /* Digo qual campo será a PK */
    REFERENCES tb_place(`id_place`) /* Informo qual Tabela e campo serão referenciados como FK */
) ENGINE=INNODB;

CREATE TABLE tb_user (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `phone` VARCHAR(45) NULL,
  `cpf` VARCHAR(45) NULL,
  `rg` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `fb_id` VARCHAR(45) NOT NULL,
  `birthday` VARCHAR(45) NULL,
  `gender` VARCHAR(1) NULL,
  `username` VARCHAR(45) NOT NULL,
  `adress` VARCHAR(200) NULL,
  
  PRIMARY KEY (`id_user`)
) ENGINE=INNODB;

CREATE TABLE tb_lista (
  `id_lista` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `id_event` INT(11) NOT NULL,

  PRIMARY KEY(`id_lista`),
	
	INDEX (`id_user`),
    INDEX (`id_event`),
	
	FOREIGN KEY (`id_user`)
    REFERENCES tb_lista(`id_user`),

  FOREIGN KEY (`id_event`)
    REFERENCES tb_event(`id_event`)
) ENGINE=INNODB;