-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-05-2014 a las 16:19:33
-- Versión del servidor: 5.1.49
-- Versión de PHP: 5.3.3-7+squeeze14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `blog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_admin`
--

CREATE TABLE IF NOT EXISTS `info_admin` (
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `nivel` int(11) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `logeado` int(11) NOT NULL,
  `sesion` varchar(200) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `creado` date NOT NULL,
  `modificado` date NOT NULL,
  `eliminado` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `info_admin`
--

INSERT INTO `info_admin` (`nombres`, `apellidos`, `nivel`, `usuario`, `clave`, `logeado`, `sesion`, `correo`, `creado`, `modificado`, `eliminado`, `id`) VALUES
('Administrador', 'Sistema', 0, 'a', '92eb5ffee6ae2fec3ad71c777531578f', 0, '0', '0', '2014-05-15', '2014-05-15', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `info_admin` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `creado` date NOT NULL,
  `modificado` date NOT NULL,
  `eliminado` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `info_admin` (`info_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `post`
--

INSERT INTO `post` (`info_admin`, `comentario`, `creado`, `modificado`, `eliminado`, `id`) VALUES
(1, 'Hola mundo', '0000-00-00', '0000-00-00', 0, 1),
(1, 'hola de nuevo', '0000-00-00', '0000-00-00', 0, 2),
(1, 'Este es mi tercer post', '0000-00-00', '0000-00-00', 0, 3),
(1, 'Escribe aqui tu post', '2014-05-15', '2014-05-15', 0, 4);

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`info_admin`) REFERENCES `info_admin` (`id`);
