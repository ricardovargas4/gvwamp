create table classificacoes (
	id int not null AUTO_INCREMENT,
    id_atividade int not null references atividades(id),
    opcao varchar(150) not null,
    created_at timestamp,
    updated_at timestamp,
    primary key(id)
);