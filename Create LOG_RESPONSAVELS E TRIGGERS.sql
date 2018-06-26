create table LOGS_RESPONSAVELS (
    
    id int not null AUTO_INCREMENT,	
    ID_PROCESSO int not null references PROCESSOS(id),
    USUARIO int not null references users(id),
    DATA_ALTERACAO  timestamp not null,
    TIPO  varchar(150) not null,
    primary key(id)
);

CREATE DEFINER=`root`@`localhost` TRIGGER LOG_AFTER_INSERT
AFTER INSERT
   ON responsavels FOR EACH ROW

BEGIN

   INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( NEW.ID_PROCESSO,
	 NEW.USUARIO,
     SYSDATE(),
     'INCLUSAO' );

END

;

CREATE DEFINER=`root`@`localhost` TRIGGER `gvdb`.`responsavels_AFTER_UPDATE` AFTER UPDATE ON `responsavels` FOR EACH ROW
BEGIN

	INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( OLD.ID_PROCESSO,
	 OLD.USUARIO,
     SYSDATE(),
     'EXCLUSAO' );

   INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( NEW.ID_PROCESSO,
	 NEW.USUARIO,
     SYSDATE(),
     'INCLUSAO' );

END;

CREATE DEFINER=`root`@`localhost` TRIGGER `gvdb`.`responsavels_AFTER_DELETE` AFTER DELETE ON `responsavels` FOR EACH ROW
BEGIN

	INSERT INTO LOGS_RESPONSAVELS
   ( ID_PROCESSO,
     USUARIO,
     DATA_ALTERACAO,
     TIPO)
   VALUES
   ( OLD.ID_PROCESSO,
	 OLD.USUARIO,
     SYSDATE(),
     'EXCLUSAO' );

END