create table demandas (
	id int not null AUTO_INCREMENT,
    id_processo int not null references processos(id),
    data_final date not null,
    id_responsavel int not null references users(id),
    data_conclusao date,
    created_at timestamp,
    updated_at timestamp,
    primary key(id)
);