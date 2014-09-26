-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2014 a las 17:42:42
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `adm_clientes`
--

INSERT INTO `adm_clientes` (`CODIGO_CLIENTE`, `ESTADO_CLIENTE`, `CODIGO_PERSONA`) VALUES
(1, 'A', 1),
(2, 'A', 2),
(3, 'A', 3),
(4, 'A', 4),
(5, 'A', 5),
(6, 'A', 6),
(7, 'A', 7),
(8, 'A', 8),
(9, 'A', 9),
(10, 'A', 10),
(11, 'A', 11),
(12, 'A', 12),
(13, 'A', 13),
(14, 'A', 14),
(15, 'A', 15);

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
  `ENVIAR_EMAIL` varchar(2) NOT NULL DEFAULT 'S',
  `DIRECCION_PERSONA` varchar(100) NOT NULL,
  `REFERENCIA_PERSONA` varchar(100) DEFAULT NULL,
  `CODIGO_CIUDAD` int(11) NOT NULL,
  `CODIGO_BARRIO` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_PERSONA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Volcar la base de datos para la tabla `adm_personas`
--

INSERT INTO `adm_personas` (`CODIGO_PERSONA`, `DESCRIPCION_PERSONA`, `NRO_DOCUMENTO_PERSONA`, `RUC_PERSONA`, `TELEFONO_PERSONA`, `CELULAR_PERSONA`, `EMAIL_PERSONA`, `ENVIAR_EMAIL`, `DIRECCION_PERSONA`, `REFERENCIA_PERSONA`, `CODIGO_CIUDAD`, `CODIGO_BARRIO`) VALUES
(1, 'Lilian Diana Delgado Lopez', 2499085, '2499085-0', '0982636733', '0982636733', 'innovartemarket@gmail.com', 'N', 'Tatyiba 1646 c/ Morelos', 'Barrio Obrero', 1, 1),
(2, 'Ingrid Villalba', 2390514, '2390514', '0981400455', '0981400455', 'ivprensa@gmail.com', 'N', 'Vicente Flores 910', 'A dos cuadras de la casa vieja de Strosner', 1, 1),
(3, 'Veronica Meyer Frutos', 1356154, '1356154-0', '282557', '0981407063', 'veromeyer@gmail.com', 'N', 'Marcelino Ayala 2024', 'Al costado de IPS central', 1, 1),
(4, 'Macarena Galindo - Fuera de Foco', 4624023, '80081452-5', '442901', '0981426859', 'maca@fueradefoco.com.py', 'N', '13 Proyectadas c/ Brasil', 'Barrio Obrero', 1, 1),
(5, 'Eduardo Saccomani', 1514483, '1514483-6', '0981564176', '0981268603', 'dreduardo_saccomani@hotmail.com', 'N', 'Asuncion 1318 c/ Yataity Cora', 'Lambare', 1, 1),
(6, 'Eduardo Bobadilla', 1871962, '1871962-7', '2488000', '0981223790', 'edubob@hotmail.com', 'N', 'Rio de Janeiro y Rosa peña', 'Vista Alegre', 1, 1),
(7, 'Andres Ferrari', 1111, '1111', '0981847373', '0981847373', 'ferrari@imagen.com.py', 'N', 'Caquique Lambare y dr. Luis Maria Argaña', 'Lambare', 1, 1),
(8, 'Lilian Magnelia', 1111, '80061823-8', '0981440115', '0981440115', 'lilian.magnelia@merge.com.py', 'N', 'Tracking', 'Tracking', 1, 1),
(9, 'Celia Meyer', 1871620, '1871620-2', '0971130035', '0971130035', 'cmeyerfrutos@gmail.com', 'N', 'Santa Rosa c/ España', 'Avda España', 1, 1),
(10, 'Chiche Corte', 1111, '1111', '0974199001', '0981199001', 'chichecorte@gmail.com', 'N', 'Avda. Peron y Concepcion Yegros', 'Al  lado de caracol', 1, 1),
(11, 'Activamente S.R.L', 8006683, '8006683-8', '672671', '0982885248', 'laura@activamente.com.py', 'N', 'Marcelino Ayala  2016 e/ Gomez de la Fuente', 'IPS central', 1, 1),
(12, 'Andres Parcerisa', 915801, '915801-4', '291104', '0981811700', 'andyparce@gmail.com', 'N', 'Rio Apa e/ Yegros', 'Plaza', 1, 1),
(13, 'Adolfina Cabrera', 83985, '83985-7', '291104', '0985501502', 'ami@activamente.com.py', 'N', 'Rio Apa e/ Yegros', 'Plazita', 1, 1),
(14, 'Gaspar Cabrera', 1960404, '1960404-1', '0985438057', '0985438057', 'gaspar@activamente.com.py', 'N', 'Rio Apara e/ Sarabi', 'A una cuadra de Dylan', 1, 1),
(15, 'Desarrollo Agricola del Paraguay S.A', 2875921, '80029592-7', '208450', '208450', 'oramirez@dap.com.py', 'N', 'Avda. España 2045e/ Luis Morales', 'Avda España 2045', 1, 1),
(16, 'Gilberto Diaz Alfonso', 3591205, '11111', '0985514252', NULL, 'giberto@sansolucion.com.py', 'N', 'Coronel Bogado y Mcal. Estigarribia', NULL, 1, 1),
(17, 'Antonio Vera', 3995304, '3995304-1', '0985514013', NULL, 'antonio@sansolucion.com.py', 'N', 'Calle Hernandarias Escuela 860', NULL, 1, 1),
(18, 'Cristhian Ramirez', 2192625, '1111', '0985112635', NULL, 'cristhian@sansolucion.com.py', 'N', 'Rio Tebycuary 2171', NULL, 1, 1),
(19, 'Samuel Benitez', 4983762, '1111', '0983541515', NULL, 'samuel@sansolucion.com.py', 'N', 'Fracción ciudad Villa Jardín 3', NULL, 1, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Volcar la base de datos para la tabla `adm_planes`
--

INSERT INTO `adm_planes` (`CODIGO_PLAN`, `DESCRIPCION_PLAN`, `TIPO_PLAN`, `CANTIDAD_PLAN`, `COSTO_PLAN`, `ESTADO_PLAN`) VALUES
(1, 'Plan Inicial 5', 'M', 5, 250000, 'A'),
(2, 'Plan Solución 10', 'M', 10, 480000, 'A'),
(3, 'Plan Solución 15', 'M', 15, 690000, 'A'),
(4, 'Plan Solución 20', 'M', 20, 880000, 'A'),
(5, 'Plan Solución 25', 'M', 25, 1050000, 'A'),
(6, 'Plan Solución 30', 'M', 30, 1200000, 'A'),
(7, 'Plan Solución 35', 'M', 35, 1330000, 'A'),
(8, 'Plan Solución 40', 'M', 40, 1440000, 'A'),
(9, 'Plan Solución 45', 'M', 45, 1530000, 'A'),
(10, 'Plan Empresa 50', 'M', 50, 1700000, 'A'),
(11, 'Plan Empresa 60', 'M', 60, 2040000, 'A'),
(12, 'Plan Empresa 70', 'M', 70, 2380000, 'A'),
(13, 'Plan Empresa 80', 'M', 80, 2720000, 'A'),
(14, 'Plan Empresa 90', 'M', 90, 3060000, 'A'),
(15, 'Plan Empresa 100', 'M', 100, 3400000, 'A'),
(16, 'Gestión individual', 'C', 1, 60000, 'A'),
(17, 'Gestión individual staff', 'C', 1, 57000, 'A'),
(18, 'Paquete 2', 'C', 2, 100000, 'A'),
(19, 'Paquete 3', 'C', 3, 150000, 'A'),
(20, 'Paquete 4', 'C', 4, 200000, 'A'),
(21, 'Paquete 5', 'C', 5, 250000, 'A'),
(22, 'Paquete 6', 'C', 6, 300000, 'A'),
(23, 'Paquete 7', 'C', 7, 350000, 'A'),
(24, 'Paquete 8', 'C', 8, 400000, 'A'),
(25, 'Paquete 9', 'C', 9, 450000, 'A'),
(26, 'Paquete 10', 'C', 10, 500000, 'A'),
(27, 'Paquete 2 staff', 'C', 2, 95000, 'A'),
(28, 'Paquete 3 staff', 'C', 3, 142500, 'A'),
(29, 'Paquete 4 staff', 'C', 4, 190000, 'A'),
(30, 'Paquete 5 staff', 'C', 5, 237500, 'A'),
(31, 'Paquete 6 staff', 'C', 6, 285000, 'A'),
(32, 'Paquete 7 staff', 'C', 7, 332500, 'A'),
(33, 'Paquete 8 staff', 'C', 8, 380000, 'A'),
(34, 'Paquete 9 staff', 'C', 9, 427500, 'A'),
(35, 'Paquete 10 staff', 'C', 10, 475000, 'A'),
(36, 'Plan inicial staff', 'M', 5, 237500, 'A');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `adm_suscripciones`
--

INSERT INTO `adm_suscripciones` (`CODIGO_SUSCRIPCION`, `CODIGO_CLIENTE`, `CODIGO_PLAN`, `FECHA_SUSCRIPCION`, `FECHA_VENCIMIENTO`, `FECHA_ACREDITACION`, `IMPORTE_GESTION`, `ESTADO_SUSCRIPCION`) VALUES
(1, 15, 36, '2014-09-24', '2014-10-24', '2014-09-24', 9500, 'A'),
(2, 15, 26, '2014-09-24', '2014-09-24', '2014-09-24', 5000, 'A'),
(3, 15, 26, '2014-09-24', '2014-09-24', '2014-09-24', 5000, 'A');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `log_gestores`
--

INSERT INTO `log_gestores` (`CODIGO_GESTOR`, `ESTADO_GESTOR`, `CODIGO_PERSONA`) VALUES
(1, 'A', 16),
(2, 'A', 17),
(3, 'A', 18),
(4, 'A', 19);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `log_saldo`
--

INSERT INTO `log_saldo` (`CODIGO_SALDO`, `CODIGO_SUSCRIPCION`, `CODIGO_CLIENTE`, `FECHA_SALDO`, `FECHA_SALDO_VTO`, `CANTIDAD`, `CANTIDAD_SALDO`, `IMPORTE`, `IMPORTE_SALDO`) VALUES
(1, 1, 15, '2014-09-24', '2014-10-24', 5, 0.0, 47500, 47500),
(2, 2, 15, '2014-09-24', '2014-12-24', 10, 9.0, 50000, 50000),
(3, 3, 15, '2014-09-24', '2014-09-24', 10, 10.0, 50000, 50000);

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
(250000, 100),
(47500, 100),
(50000, 100),
(50000, 100);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vlog_saldos`
--
CREATE TABLE IF NOT EXISTS `vlog_saldos` (
`codigo_cliente` int(11)
,`nombre` varchar(100)
,`saldo` double(18,1)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vlog_saldos_planes`
--
CREATE TABLE IF NOT EXISTS `vlog_saldos_planes` (
`codigo_suscripcion` int(11)
,`codigo_cliente` int(11)
,`nombre` varchar(100)
,`saldo` double(18,1)
,`descripcion_plan` varchar(60)
);
-- --------------------------------------------------------

--
-- Estructura para la vista `vlog_saldos`
--
DROP TABLE IF EXISTS `vlog_saldos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`diego`@`192.168.0.100` SQL SECURITY DEFINER VIEW `vlog_saldos` AS select `c`.`CODIGO_CLIENTE` AS `codigo_cliente`,`p`.`DESCRIPCION_PERSONA` AS `nombre`,sum(`s`.`CANTIDAD_SALDO`) AS `saldo` from ((((`log_saldo` `s` join `adm_clientes` `c`) join `adm_personas` `p`) join `adm_suscripciones` `u`) join `adm_planes` `l`) where ((`s`.`CODIGO_SUSCRIPCION` = `u`.`CODIGO_SUSCRIPCION`) and (`u`.`CODIGO_PLAN` = `l`.`CODIGO_PLAN`) and (`s`.`CODIGO_CLIENTE` = `c`.`CODIGO_CLIENTE`) and (`c`.`CODIGO_PERSONA` = `p`.`CODIGO_PERSONA`) and (`u`.`ESTADO_SUSCRIPCION` = 'A') and (`s`.`FECHA_SALDO_VTO` >= cast(now() as date))) group by `c`.`CODIGO_CLIENTE`,`p`.`DESCRIPCION_PERSONA`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vlog_saldos_planes`
--
DROP TABLE IF EXISTS `vlog_saldos_planes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`diego`@`192.168.0.100` SQL SECURITY DEFINER VIEW `vlog_saldos_planes` AS select `u`.`CODIGO_SUSCRIPCION` AS `codigo_suscripcion`,`c`.`CODIGO_CLIENTE` AS `codigo_cliente`,`p`.`DESCRIPCION_PERSONA` AS `nombre`,sum(`s`.`CANTIDAD_SALDO`) AS `saldo`,`l`.`DESCRIPCION_PLAN` AS `descripcion_plan` from ((((`log_saldo` `s` join `adm_clientes` `c`) join `adm_personas` `p`) join `adm_suscripciones` `u`) join `adm_planes` `l`) where ((`s`.`CODIGO_SUSCRIPCION` = `u`.`CODIGO_SUSCRIPCION`) and (`u`.`CODIGO_PLAN` = `l`.`CODIGO_PLAN`) and (`s`.`CODIGO_CLIENTE` = `c`.`CODIGO_CLIENTE`) and (`c`.`CODIGO_PERSONA` = `p`.`CODIGO_PERSONA`) and (`u`.`ESTADO_SUSCRIPCION` = 'A') and (`s`.`FECHA_SALDO_VTO` >= cast(now() as date))) group by `u`.`CODIGO_SUSCRIPCION`,`c`.`CODIGO_CLIENTE`,`p`.`DESCRIPCION_PERSONA`,`l`.`DESCRIPCION_PLAN` having (sum(`s`.`CANTIDAD_SALDO`) > 0);
