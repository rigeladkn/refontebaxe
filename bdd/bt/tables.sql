CREATE table bt_agence (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_pays bigint(20) unsigned NOT NULL,
    nom varchar(150) NOT NULL,
	adresse varchar(250) NOT NULL,
	lat decimal(13,10)  NOT NULL,
	lng decimal(13,10) NOT NULL,
    FOREIGN KEY (id_pays) REFERENCES PAYS(id)
);


ALTER TABLE distributeurs add column (	lat decimal(13,10));
ALTER TABLE distributeurs add column (	lng decimal(13,10));


CREATE TABLE bt_client_session (
    id bigint(20) unsigned PRIMARY KEY AUTO_INCREMENT,
    id_client bigint(20) unsigned NOT NULL,
    id_user bigint(20) unsigned NOT NULL,
    created_at timestamp not null,
    updated_at timestamp ,
    closed_at timestamp null,
    FOREIGN KEY (id_client) REFERENCES clients (id),
    FOREIGN KEY (id_user) REFERENCES users (id)
); 


CREATE TABLE bt_commercants (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  reference varchar(50) DEFAULT NULL,
  user_id bigint(20) unsigned NOT NULL,
  pays_id bigint(20) unsigned NOT NULL,
  nom varchar(255) NOT NULL,
  prenoms varchar(255) NOT NULL,
  code_postal varchar(255) NOT NULL,
  ville varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  telephone varchar(255) NOT NULL,
  telephone2 varchar(255) DEFAULT NULL,
  telephone3 varchar(255) DEFAULT NULL,
  activite_principale varchar(255) NOT NULL,
  registre_commerce varchar(255) DEFAULT NULL,
  num_compte_bancaire varchar(50) DEFAULT NULL,
  entreprise_nom varchar(255) NOT NULL,
  path_piece_identitite longtext DEFAULT NULL COMMENT '(DC2Type:json)',
  path_media_du_local longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  communication_baxe varchar(255) DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY commercants_user_id_unique (user_id),
  KEY commercants_pays_id_index (pays_id),
  CONSTRAINT commercants_pays_id_foreign FOREIGN KEY (pays_id) REFERENCES pays (id),
  CONSTRAINT commercants_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;


CREATE TABLE bt_paiements_commercant (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  reference varchar(50) DEFAULT NULL,
  user_id_from bigint(20) unsigned NOT NULL,
  user_id_to bigint(20) unsigned NOT NULL,
  montant double NOT NULL,
  frais double NOT NULL,
  taux_from double NOT NULL,
  taux_to double NOT NULL,
  pays_from varchar(255) NOT NULL,
  pays_to varchar(255) NOT NULL,
  ip_from varchar(255) NOT NULL,
  ip_to varchar(255) NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY paiements_commercant_user_id_from_index (user_id_from),
  KEY paiements_commercant_user_id_to_index (user_id_to),
  CONSTRAINT paiements_commercant_user_id_from_foreign FOREIGN KEY (user_id_from) REFERENCES users (id),
  CONSTRAINT paiements_commercant_user_id_to_foreign FOREIGN KEY (user_id_to) REFERENCES users (id)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb3;
