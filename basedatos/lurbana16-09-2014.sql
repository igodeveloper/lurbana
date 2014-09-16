-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2014 a las 16:05:22
-- Versión del servidor: 5.1.50
-- Versión de PHP: 5.3.9-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `lurbana`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_clientes`
--

CREATE TABLE IF NOT EXISTS `adm_clientes` (
  `CODIGO_CLIENTE` int(11) NOT NULL AUTO_INCREMENT,
  `ESTADO_CLIENTE` char(1) NOT NULL,
  `CODIGO_PERSONA` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_CLIENTE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcar la base de datos para la tabla `adm_clientes`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_personas`
--

CREATE TABLE IF NOT EXISTS `adm_personas` (
  `CODIGO_PERSONA` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION_PERSONA` varchar(100) NOT NULL,
  `NRO_DOCUMENTO_PERSONA` int(11) NOT NULL,
  `RUC_PERSONA` varchar(100) NOT NULL,
  `TELEFONO_PERSONA` varchar(100) NOT NULL,
  `CELULAR_PERSONA` varchar(12) DEFAULT NULL,
  `EMAIL_PERSONA` varchar(100) NOT NULL,
  `ENVIAR_EMAIL` varchar(2) NOT NULL,
  `DIRECCION_PERSONA` varchar(100) NOT NULL,
  `REFERENCIA_PERSONA` varchar(100) DEFAULT NULL,
  `CODIGO_CIUDAD` int(11) NOT NULL,
  `CODIGO_BARRIO` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_PERSONA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcar la base de datos para la tabla `adm_personas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_planes`
--

CREATE TABLE IF NOT EXISTS `adm_planes` (
  `CODIGO_PLAN` int(4) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION_PLAN` varchar(60) NOT NULL,
  `TIPO_PLAN` varchar(2) NOT NULL,
  `CANTIDAD_PLAN` int(4) NOT NULL,
  `COSTO_PLAN` int(12) NOT NULL,
  `ESTADO_PLAN` varchar(2) NOT NULL,
  PRIMARY KEY (`CODIGO_PLAN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `adm_planes`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_suscripciones`
--

CREATE TABLE IF NOT EXISTS `adm_suscripciones` (
  `CODIGO_SUSCRIPCION` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_CLIENTE` int(11) NOT NULL,
  `CODIGO_PLAN` int(11) NOT NULL,
  `FECHA_SUSCRIPCION` date NOT NULL,
  `FECHA_VENCIMIENTO` date NOT NULL,
  `FECHA_ACREDITACION` date NOT NULL,
  `IMPORTE_GESTION` int(11) NOT NULL,
  `ESTADO_SUSCRIPCION` varchar(2) NOT NULL,
  PRIMARY KEY (`CODIGO_SUSCRIPCION`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `adm_suscripciones`
--


--
-- (Evento) desencadenante `adm_suscripciones`
--
DROP TRIGGER IF EXISTS `inserta_log_saldo`;
DELIMITER //
CREATE TRIGGER `inserta_log_saldo` AFTER INSERT ON `adm_suscripciones`
 FOR EACH ROW BEGIN
  DECLARE monto_plan INT;
  DECLARE cantidad_plan INT;
  
  SET monto_plan =  (SELECT pl.costo_plan FROM adm_planes pl WHERE pl.codigo_plan = NEW.codigo_plan);
  SET cantidad_plan =  (SELECT pl.cantidad_plan FROM adm_planes pl WHERE pl.codigo_plan = NEW.codigo_plan);
  
    INSERT INTO log_saldo(CODIGO_SALDO, CODIGO_SUSCRIPCION,	CODIGO_CLIENTE, FECHA_SALDO,FECHA_SALDO_VTO, CANTIDAD, CANTIDAD_SALDO, IMPORTE, IMPORTE_SALDO) 
	VALUES (NULL, NEW.CODIGO_SUSCRIPCION, NEW.CODIGO_CLIENTE, NEW.FECHA_SUSCRIPCION, NEW.FECHA_VENCIMIENTO, cantidad_plan, cantidad_plan, monto_plan, monto_plan); 
	
	INSERT INTO montos VALUES (monto_plan,'100');
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_barrios`
--

CREATE TABLE IF NOT EXISTS `conf_barrios` (
  `CODIGO_BARRIO` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION_BARRIO` varchar(100) NOT NULL,
  `CODIGO_CIUDAD` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_BARRIO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `conf_barrios`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_ciudades`
--

CREATE TABLE IF NOT EXISTS `conf_ciudades` (
  `CODIGO_CIUDAD` int(11) NOT NULL AUTO_INCREMENT,
  `ESTADO_CLIENTE` varchar(100) NOT NULL,
  `CODIGO_DEPARTAMENTO` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_CIUDAD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `conf_ciudades`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_departamentos`
--

CREATE TABLE IF NOT EXISTS `conf_departamentos` (
  `CODIGO_DEPARTAMENTO` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION_DEPARTAMENTO` varchar(100) NOT NULL,
  PRIMARY KEY (`CODIGO_DEPARTAMENTO`),
  UNIQUE KEY `DESCRIPCION_DEPARTAMENTO` (`DESCRIPCION_DEPARTAMENTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `conf_departamentos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_usuario`
--

CREATE TABLE IF NOT EXISTS `conf_usuario` (
  `COD_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_APELLIDO` varchar(100) NOT NULL,
  `ID_USUARIO` varchar(10) NOT NULL,
  `USUARIO_PASSWORD` varchar(10) NOT NULL,
  PRIMARY KEY (`COD_USUARIO`),
  UNIQUE KEY `ID_USUARIO` (`ID_USUARIO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `conf_usuario`
--

INSERT INTO `conf_usuario` (`COD_USUARIO`, `NOMBRE_APELLIDO`, `ID_USUARIO`, `USUARIO_PASSWORD`) VALUES
(1, 'IVAN GOMEZ', 'IVAN', 'IVAN'),
(2, 'ADMINISTRADOR', 'ADMIN', 'ADMIN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_gestiones`
--

CREATE TABLE IF NOT EXISTS `log_gestiones` (
  `NUMERO_GESTION` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA_GESTION` date NOT NULL,
  `FECHA_INICIO` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FECHA_FIN` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `CODIGO_CLIENTE` int(11) NOT NULL,
  `CODIGO_GESTOR` int(11) DEFAULT NULL,
  `CODIGO_USUARIO` int(11) NOT NULL,
  `ESTADO` varchar(1) DEFAULT NULL,
  `CANTIDAD_MINUTOS` int(11) DEFAULT NULL,
  `CANTIDAD_GESTIONES` float(11,1) DEFAULT NULL,
  `OBSERVACION` varchar(500) DEFAULT NULL,
  `CODIGO_PLAN` int(11) NOT NULL,
  UNIQUE KEY `PRIMARY_LOG_GESTIONES` (`NUMERO_GESTION`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Volcar la base de datos para la tabla `log_gestiones`
--


--
-- (Evento) desencadenante `log_gestiones`
--
DROP TRIGGER IF EXISTS `descuenta_saldo`;
DELIMITER //
CREATE TRIGGER `descuenta_saldo` AFTER INSERT ON `log_gestiones`
 FOR EACH ROW BEGIN
  DECLARE codigo_suscripcion INT;
  DECLARE monto_gestion INT;
	SET codigo_suscripcion =  (SELECT a.CODIGO_SUSCRIPCION
                                    FROM ADM_SUSCRIPCIONES A
                                    WHERE A.CODIGO_CLIENTE     = NEW.CODIGO_CLIENTE
                                      AND A.CODIGO_PLAN     = NEW.CODIGO_PLAN
                                      AND A.ESTADO_suscripcion = 'A'
                                      AND A.FECHA_SUSCRIPCION =    (select min(U.FECHA_SUSCRIPCION) 
                                                                from adm_suscripciones u 
                                                                where u.ESTADO_suscripcion = 'A'  
                                                                and u.codigo_cliente = NEW.codigo_cliente
                                                                and u.FECHA_VENCIMIENTO>=  DATE( NOW() ) 
                                                                and u.codigo_plan = NEW.codigo_plan ));
																
	SET monto_gestion =  (SELECT pl.importe_gestion FROM adm_suscripciones pl WHERE pl.codigo_suscripcion = codigo_suscripcion);
	
        UPDATE log_saldo
        SET cantidad_saldo = cantidad_saldo - NEW.cantidad_gestiones,
		importe_saldo = importe_saldo - (NEW.cantidad_gestiones*monto_gestion)  
        WHERE codigo_Cliente = New.codigo_cliente
        and cantidad_saldo>0
        and codigo_suscripcion = codigo_suscripcion;
                                        
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `retorna_saldo`;
DELIMITER //
CREATE TRIGGER `retorna_saldo` AFTER UPDATE ON `log_gestiones`
 FOR EACH ROW BEGIN
  DECLARE codigo_suscripcion INT;
  DECLARE monto_gestion INT;
  DECLARE diferencia INT;
	SET codigo_suscripcion =  (SELECT a.CODIGO_SUSCRIPCION
                                    FROM ADM_SUSCRIPCIONES A
                                    WHERE A.CODIGO_CLIENTE     = OLD.CODIGO_CLIENTE
                                      AND A.CODIGO_PLAN     = OLD.CODIGO_PLAN
                                      AND A.ESTADO_suscripcion = 'A'
                                      AND A.FECHA_SUSCRIPCION =    (select min(U.FECHA_SUSCRIPCION) 
                                                                from adm_suscripciones u 
                                                                where u.ESTADO_suscripcion = 'A'  
                                                                and u.codigo_cliente = OLD.codigo_cliente
                                                                and u.FECHA_VENCIMIENTO>=  DATE( NOW() ) 
                                                                and u.codigo_plan = OLD.codigo_plan ));
																
	SET monto_gestion =  (SELECT pl.importe_gestion FROM adm_suscripciones pl WHERE pl.codigo_suscripcion = codigo_suscripcion);
	SET diferencia = (NEW.cantidad_gestiones - OLD.cantidad_gestiones); 
	
		IF NEW.ESTADO = 'A' THEN
			UPDATE log_saldo
			SET cantidad_saldo = cantidad_saldo + OLD.cantidad_gestiones,
			importe_saldo = importe_saldo + (OLD.cantidad_gestiones*monto_gestion)  
			WHERE codigo_Cliente = OLD.codigo_cliente
			and codigo_suscripcion = codigo_suscripcion;
		ELSE 
			IF diferencia != 0 THEN				
					UPDATE log_saldo
					SET cantidad_saldo = cantidad_saldo - diferencia,
					importe_saldo = importe_saldo - (diferencia*monto_gestion)  
					WHERE codigo_Cliente = OLD.codigo_cliente
					and codigo_suscripcion = codigo_suscripcion;
					
					update montos set cantidad = 999;
				END IF;
		END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_gestores`
--

CREATE TABLE IF NOT EXISTS `log_gestores` (
  `CODIGO_GESTOR` int(11) NOT NULL AUTO_INCREMENT,
  `ESTADO_GESTOR` char(1) NOT NULL,
  `CODIGO_PERSONA` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_GESTOR`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `log_gestores`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_saldo`
--

CREATE TABLE IF NOT EXISTS `log_saldo` (
  `CODIGO_SALDO` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_SUSCRIPCION` int(11) NOT NULL,
  `CODIGO_CLIENTE` int(11) NOT NULL,
  `FECHA_SALDO` date NOT NULL,
  `FECHA_SALDO_VTO` date NOT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `CANTIDAD_SALDO` float(11,1) NOT NULL,
  `IMPORTE` int(11) NOT NULL,
  `IMPORTE_SALDO` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_SALDO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcar la base de datos para la tabla `log_saldo`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `montos`
--

CREATE TABLE IF NOT EXISTS `montos` (
  `monto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `montos`
--

INSERT INTO `montos` (`monto`, `cantidad`) VALUES
(880000, 999),
(250000, 999),
(880000, 999),
(250000, 999),
(60000, 100),
(250000, 100);
