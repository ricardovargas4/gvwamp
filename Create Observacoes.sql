create table observacoes (
	id int not null AUTO_INCREMENT,
    id_atividade int not null references atividades(id),
    observacao varchar(300),
    classificacao int references classificacoes(id),
    created_at timestamp,
    updated_at timestamp,
    primary key(id)
);