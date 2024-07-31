/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 10.4.28-MariaDB : Database - sitio
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sitio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `sitio`;

/*Table structure for table `libros` */

DROP TABLE IF EXISTS `libros`;

CREATE TABLE `libros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `libros` */

insert  into `libros`(`id`,`nombre`,`imagen`) values 
(1,'Los Dominios del Onix Negro','1698199252_los_dominios_del_onix.JPG'),
(2,'Once Minutos - Paulo Cohelo','1698019479_once_minutos.jpeg'),
(8,'Un Cadáver para un detective','1698199218_un_cadaver_para_un_detective.jpeg'),
(9,'Pueblo Cero','1698195760_pueblo_cero.jpeg'),
(10,'Angeles Caidos','1698199066_angeles_caidos_susan_fe.jpeg'),
(11,'El Abandono','1698199072_el_abandono_daniel.jpg'),
(12,'La Hora Del Mar','1698199097_la_hora_del_mar_carlos_sisi.jpg'),
(18,'Leyendas de la tierra Limite','1698202194_leyendas_de_la_tierra_limite.jpeg');

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` varchar(30) NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuario` */

insert  into `usuario`(`idusuario`,`nombre`,`correo`,`contrasenia`,`rol`) values 
(1,'Jose','asd@gmail.com','1234','Administrador'),
(2,'prueba','asd1232@gmail.com','fd559236d5a17b3f6bd8ef9a0ee99bf6','Usuario')

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
