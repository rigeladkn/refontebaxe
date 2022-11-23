CREATE OR REPLACE view bt_agence_lib as 
    select a.*, 
    p.url_drapeau,
    p.symbole_monnaie,
    p.nom nom_pays,
    p.monnaie monnaie_pays,
    p.indicatif indicatif_pays,
    p.continent,
    p.code code_pays
    from bt_agence a join pays p on p.id =a.id_pays;


CREATE OR REPLACE view bt_client_credentials as 
    select 
	id , nom , prenoms 
	, sha1( md5 ( concat( 'Baxe_', id) ) ) carte_token 
	, AES_ENCRYPT(concat( nom , prenoms  ),  md5 ( concat( 'AES_', id) )  ) valeur_crypte
	, AES_DECRYPT( AES_ENCRYPT( concat( nom , ' ' , prenoms  ) , md5 ( concat( 'AES_', id) )  ) ,  md5 ( concat( 'AES_', id) )  ) valeur_decrypte
from clients ;