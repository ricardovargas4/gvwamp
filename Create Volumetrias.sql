create table volumetrias (
	id int not null AUTO_INCREMENT,
    id_atividade int not null references atividades(id),
    volumetria int not null,
    created_at timestamp,
    updated_at timestamp,
    primary key(id)
);