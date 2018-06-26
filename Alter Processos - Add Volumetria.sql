
ALTER TABLE `gvdb`.`processos` 
ADD COLUMN `volumetria` VARCHAR(1) NULL DEFAULT NULL AFTER `updated_at`;
