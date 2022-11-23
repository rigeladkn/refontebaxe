create table taux(
	id integer primary key auto_increment,
	dateTaux Timestamp,
	pays_to varchar(10),
	pays_from varchar(10),
	taux DECIMAL(20,5)
);

insert into taux (datetaux, pays_to, pays_from, taux) values (CURRENT_TIMESTAMP(), 'EUR', 'XOF', 0.00149);
insert into taux (datetaux, pays_to, pays_from, taux) values (CURRENT_TIMESTAMP(), 'XOF', 'EUR', 652.01);

insert into taux (datetaux, pays_to, pays_from, taux) values (CURRENT_TIMESTAMP(), 'EUR', 'XAF', 0.00149);
insert into taux (datetaux, pays_to, pays_from, taux) values (CURRENT_TIMESTAMP(), 'XAF', 'EUR', 652.01);