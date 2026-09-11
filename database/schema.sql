-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`utilisateurs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`utilisateurs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(50) NULL,
  `prenom` VARCHAR(50) NULL,
  `courriel` VARCHAR(100) NULL,
  `mot_de_passe` VARCHAR(255) NULL,
  `ROLE` ENUM('MEMBRE', 'COACH', 'ADMIN') NULL,
  `date_creation` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `courriel_UNIQUE` (`courriel` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`abonnements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`abonnements` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `utilisateurs_id` INT NOT NULL,
  `type_forfait` VARCHAR(50) NULL,
  `date_debut` DATE NULL,
  `date_fin` DATE NULL,
  `statut` ENUM('ACTIF', 'EXPIRE') NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_abonnements_utilisateurs_idx` (`utilisateurs_id` ASC) VISIBLE,
  CONSTRAINT `fk_abonnements_utilisateurs`
    FOREIGN KEY (`utilisateurs_id`)
    REFERENCES `mydb`.`utilisateurs` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`terrains`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`terrains` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom_terrain` VARCHAR(50) NULL,
  `surface` VARCHAR(50) NULL,
  `emplacement` ENUM('INTERIEUR', 'EXTERIEUR') NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`reservations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`reservations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `terrains_id` INT NOT NULL,
  `date_heure_debut` DATETIME NULL,
  `date_heure_fin` DATETIME NULL,
  `statut` ENUM('confirmee', 'annulee') NULL,
  `utilisateur_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reservations_terrains1_idx` (`terrains_id` ASC) VISIBLE,
  INDEX `fk_utilisateur_id_idx` (`utilisateur_id` ASC) VISIBLE,
  CONSTRAINT `fk_reservations_terrains1`
    FOREIGN KEY (`terrains_id`)
    REFERENCES `mydb`.`terrains` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_utilisateur_id`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `mydb`.`utilisateurs` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`indisponibilites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`indisponibilites` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `terrains_id` INT NOT NULL,
  `admin_id` INT NULL,
  `date_heure_debut` DATETIME NULL,
  `date_heure_fin` DATETIME NULL,
  `raison` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_indisponibilites_terrains1_idx` (`terrains_id` ASC) VISIBLE,
  INDEX `FK_admin_id_idx` (`admin_id` ASC) VISIBLE,
  CONSTRAINT `fk_indisponibilites_terrains1`
    FOREIGN KEY (`terrains_id`)
    REFERENCES `mydb`.`terrains` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_admin_id`
    FOREIGN KEY (`admin_id`)
    REFERENCES `mydb`.`utilisateurs` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
