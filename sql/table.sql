//Praticiens

create table specialite(
id varchar(5),
label varchar(50) not null,
description text not null,
primary key(id)
);

create table praticien(
id UUID,
rpps varchar(50) not null,
nom varchar(50) not null,
prenom varchar(50) not null,
adresse varchar(100) not null,
tel varchar(20) not null,
specialite varchar(5) not null,
primary key(id),
foreign key(specialite) references specialite(id)
);





/////////////////////////////////
//RDVS

create table patient(
id UUID,
nom varchar(50) not null,
prenom varchar(50) not null,
dateNaissance date not null,
adresse varchar(100),
tel varchar(20),
mail varchar(100),
idMedcinTraitant UUID,
numSecuSociale varchar(50),
primary key(id)
);

create table status(
id int,
label varchar(50) not null,
primary key(id)
);

create table rdv(
id UUID,
date timestamp,
patientId UUID,
praticienId UUID,
status int,
primary key(id),
foreign key(patientId) references patient(id),
foreign key(status) references status(id)
);


