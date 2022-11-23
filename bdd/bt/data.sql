UPDATE distributeurs set lat=47.1 , lng=1.5 where id=2;
UPDATE distributeurs set lat=48 , lng=49 where id=5;
UPDATE distributeurs set lat=48 , lng=49 where id=7;
UPDATE distributeurs set lat=47.2 , lng=1.6 where id=8;
UPDATE distributeurs set lat=48 , lng=49 where id=9;
UPDATE distributeurs set lat=48 , lng=49 where id=10;
UPDATE distributeurs set lat=48 , lng=49 where id=11;
UPDATE distributeurs set lat=47.3 , lng=1.7 where id=12;
UPDATE distributeurs set lat=48 , lng=49 where id=13;

UPDATE distributeurs
SET lat=47.4959660000, lng=-18.9364127000
WHERE id=2;
UPDATE distributeurs
SET lat=47.3000000000, lng=-18.8364127000
WHERE id=5;


--Commercant test
INSERT INTO bt_commercants
(reference, user_id, pays_id, nom, prenoms, code_postal, ville, email, telephone, telephone2, telephone3, activite_principale, registre_commerce, num_compte_bancaire, entreprise_nom, path_piece_identitite, path_media_du_local, communication_baxe, created_at, updated_at)
VALUES('onig2SQU16', 176, 143, 'Bydeau', 'Axel', '95720', 'Dakar', 'axelbydeau@gmail.com', '+33783266596', '', '', 'Business', 'SN DKR 2019 A 13592', NULL, 'BAXE', NULL, NULL, 'Via Un Ami', '2022-09-10 19:02:31', '2022-09-10 19:02:31');
