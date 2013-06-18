SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `sefedocuments`
-- 
CREATE DATABASE `sefedocuments` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sefedocuments`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `documentos`
-- 

CREATE TABLE `documentos` (
  `iddocumentos` bigint(20) NOT NULL auto_increment,
  `titulo` varchar(250) NOT NULL,
  `descripcion` text NOT NULL,
  `palabras_clave` varchar(250) NOT NULL,
  `url` varchar(200) NOT NULL,
  `idUsuarios` bigint(20) NOT NULL,
  `Feha` datetime NOT NULL,
  `file_name` tinytext,
   PRIMARY KEY  (`iddocumentos`),
  FULLTEXT `buscar` (titulo,descripcion,palabras_clave)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;