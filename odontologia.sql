-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-12-2023 a las 13:43:06
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `odontologia`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_citas` ()   BEGIN
  UPDATE evento SET status = 7 WHERE status = 5 AND hora >= NOW();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AsignarHora` (IN `id_ser` INT, OUT `hora_aleatoria` TIME, OUT `id_doc` INT)   BEGIN
    DECLARE horEntrada TIME;
    DECLARE horSalida TIME;
    DECLARE segEntrada INT;
    DECLARE segSalida INT;
    DECLARE segSalidaMenosUnaHora INT;
    DECLARE rand FLOAT;
    DECLARE segAleatorio INT;
    SET id_doc = (select vs.empl from empleados empl inner join empl_vs_espec vs on empl.id_empl=vs.empl join servicios ser on ser.espec_req=vs.espec join especialidad esp on esp.id_esp=vs.espec where empl.status=1 and ser.id_ser=id_ser order by rand() limit 1);

    -- Obtener la hora de entrada y salida del empleado
    SET horEntrada=(SELECT hor.entrada FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
        
    SET horSalida=(SELECT hor.salida FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
    -- Convertir la hora de entrada y salida en segundos
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC(horSalida);

    -- Restar una hora a la hora de salida
    SET segSalidaMenosUnaHora = segSalida - 3600;

    -- Generar un número aleatorio entre 0 y 1
    SET rand = RAND();

    -- Calcular la hora aleatoria dentro del rango
    SET segAleatorio = (segSalidaMenosUnaHora - segEntrada) * rand + segEntrada;
    SET hora_aleatoria = SEC_TO_TIME(segAleatorio);

    -- Formatear la hora aleatoria sin milisegundos
    SET hora_aleatoria = TIME_FORMAT(hora_aleatoria, '%H:%i:00');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AsignarHoraXTanda` (IN `id_ser` INT, IN `tanda` INT, OUT `hora_aleatoria` TIME, OUT `id_doc` INT)   BEGIN
    DECLARE horEntrada TIME;
    DECLARE horSalida TIME;
    DECLARE segEntrada INT;
    DECLARE segSalida INT;
    DECLARE segSalidaMenosUnaHora INT;
    DECLARE rand FLOAT;
    DECLARE segAleatorio INT;
    DECLARE tip_hor INT;
    SET tip_hor=1;
    /*
    SI tanda=1
Busco los doctores cuyo id de horario sea 1 y busco una hora random entre la hora de entrada (8 am) y las 12 del medio dia

SI tanda=2
Busco los doctores cuyo id de horario sea 1 y busco una hora random entre la 1 pm hasta la hora de salida(6pm)

SI tanda=3
Busco los doctores cuyo id de horario sea 2 y busco una hora random entre la entrada y la salida
    */
    IF tanda=3 THEN
   SET tip_hor=2;
    END IF;

    SET id_doc = (select vs.empl from empleados empl inner join empl_vs_espec vs on empl.id_empl=vs.empl join servicios ser on ser.espec_req=vs.espec join especialidad esp on esp.id_esp=vs.espec where ser.id_ser=id_ser and empl.horario=tip_hor order by rand() limit 1);

    -- Obtener la hora de entrada y salida del empleado
    SET horEntrada=(SELECT hor.entrada FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
        
    SET horSalida=(SELECT hor.salida FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
    
    -- Establecer rango de horas
    IF tanda=1 THEN
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC('12:30:00');
    SET segSalidaMenosUnaHora = segSalida;
    
    ELSEIF tanda=2 THEN
    SET segEntrada = TIME_TO_SEC('12:30:00');
    SET segSalida = TIME_TO_SEC(horSalida);
    SET segSalidaMenosUnaHora = segSalida - 3600;

    ELSE
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC('23:59:59');
    SET segSalidaMenosUnaHora = segSalida;
    END IF;
    

    -- Generar un número aleatorio entre 0 y 1
    SET rand = RAND();

    -- Calcular la hora aleatoria dentro del rango
    SET segAleatorio = (segSalidaMenosUnaHora - segEntrada) * rand + segEntrada;
    
    SET hora_aleatoria = SEC_TO_TIME(segAleatorio);

    -- Formatear la hora aleatoria sin milisegundos
    SET hora_aleatoria = TIME_FORMAT(hora_aleatoria, '%H:%i:00');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckProductQuantity` (IN `producto_id` INT)   BEGIN
    DECLARE cantidad INT;
    DECLARE limite INT;

    -- Límite establecido para la cantidad de productos
    SET limite = 10;

    -- Verificar si el producto existe
    IF EXISTS (SELECT 1 FROM inventario WHERE id_art = producto_id) THEN
        -- Obtener la cantidad del producto
        SELECT cant_exist INTO cantidad
        FROM inventario
        WHERE id_art = producto_id;

        -- Mostrar mensaje si la cantidad es inferior al límite
        IF cantidad < limite THEN
            SET @message = CONCAT('Atención: La cantidad del producto con ID ', producto_id, ' es ', cantidad, ', por debajo del límite de ', limite, '.');
            SELECT @message AS notification;
            -- Puedes agregar lógica adicional aquí para enviar notificaciones o realizar otras acciones
        END IF;
    ELSE
        SELECT 'El producto con ID ' + producto_id + ' no existe.' AS notification;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckProductQuantity111` (IN `producto_id` INT)   BEGIN
DECLARE valor_actual INT;
DECLARE id_max INT;
 DECLARE cantidad INT;
    DECLARE limite INT;

    -- Límite establecido para la cantidad de productos
    SET limite = 10;

 SELECT MAX(id_art) INTO id_max FROM inventario; 

    -- Inicializar valor actual
    SET valor_actual = 1;

    -- Iniciar el bucle WHILE
    WHILE valor_actual <= id_max DO
 -- Verificar si el producto existe
    IF EXISTS (SELECT valor_actual FROM inventario WHERE id_art = valor_actual) THEN
        -- Obtener la cantidad del producto
        SELECT cant_exist INTO cantidad
        FROM inventario
        WHERE id_art = valor_actual;

        -- Mostrar mensaje si la cantidad es inferior al límite
        IF cantidad < limite THEN
            SET @message = CONCAT('Atención: La cantidad del producto con ID ', valor_actual, ' es ', cantidad, ', por debajo del límite de ', limite, '.');
            SELECT @message AS notification;
            -- Puedes agregar lógica adicional aquí para enviar notificaciones o realizar otras acciones
        END IF;
    ELSE
        SELECT 'El producto con ID ' + valor_actual + ' no existe.' AS notification;
    END IF;
        -- Actualizar el valor actual para la próxima iteración
        SET valor_actual = valor_actual + 1;
    END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ReponeArticuloAutomatico` (IN `id_articulo` INT)   BEGIN
    DECLARE valor_actual INT;
    DECLARE top_id INT;
    DECLARE cantidad_a_reponer INT;
    DECLARE cantidad_actual INT;
    DECLARE limite_stock INT;
    
    SELECT MAX(id_art) INTO top_id FROM inventario; 
    SET limite_stock = 10;
    
    -- Inicializar valor actual
    SET valor_actual = 1;

     WHILE valor_actual <= top_id DO
    -- Obtener la cantidad actual del artículo
    SELECT cant_exist INTO cantidad_actual FROM inventario WHERE id_art = valor_actual;

    -- Verificar si la cantidad está por debajo del límite
    IF cantidad_actual < limite_stock THEN
        -- Calcular la cantidad a reponer (puedes ajustar esta lógica según tus necesidades)
        SET cantidad_a_reponer = limite_stock - cantidad_actual + 10;

        -- Incrementar la cantidad del artículo
        UPDATE inventario SET cant_exist = cant_exist + cantidad_a_reponer WHERE id_art = valor_actual;

        SELECT CONCAT('Se ha repuesto automáticamente el artículo con ID ', valor_actual, ' con ', cantidad_a_reponer, ' unidades.') AS mensaje;
    END IF;
    
                -- Actualizar el valor actual para la próxima iteración
        SET valor_actual = valor_actual + 1;
           END WHILE;
     END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alergias`
--

CREATE TABLE `alergias` (
  `id_alerg` int(11) NOT NULL,
  `nom_alerg` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alergias`
--

INSERT INTO `alergias` (`id_alerg`, `nom_alerg`) VALUES
(1, 'METAL'),
(2, 'LATEX'),
(3, 'HIPER SENSIBILIDAD'),
(4, 'ALERGIA A LA ANESTECIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `antecedentes`
--

CREATE TABLE `antecedentes` (
  `id_ant` int(11) NOT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_pad` int(11) DEFAULT NULL,
  `id_alerg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apellidos`
--

CREATE TABLE `apellidos` (
  `id_ape` int(11) NOT NULL,
  `apellido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `apellidos`
--

INSERT INTO `apellidos` (`id_ape`, `apellido`) VALUES
(1, 'Perez'),
(2, 'Aristi'),
(3, 'Lorenzo'),
(4, 'Torres'),
(5, 'Rodriguez'),
(6, 'Sanchez'),
(7, 'Hernandez'),
(8, 'wflalm'),
(9, 'Castillo'),
(10, 'Botello AFP'),
(11, 'Ramirez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id_art` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `des_art` text DEFAULT NULL,
  `precom_art` decimal(10,2) DEFAULT NULL,
  `preven_art` decimal(10,2) DEFAULT NULL,
  `itbis_art` decimal(10,2) DEFAULT NULL,
  `unidad` int(11) DEFAULT NULL,
  `tip_art` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id_art`, `nombre`, `des_art`, `precom_art`, `preven_art`, `itbis_art`, `unidad`, `tip_art`, `status`) VALUES
(1, 'ALAMBRE FINO', 'Alambre Util una buena retencion de los brackets', 500.00, 550.00, 0.16, 1, 2, 1),
(2, 'BRACKET', 'Pieza de bracket', 450.00, 500.00, 0.16, 1, 2, 1),
(3, 'RETENEDORES', 'Para la retencion posterior al tratamiento de ortodoncia', 8600.00, 9000.00, 0.16, 1, 2, 1),
(4, 'ROLLO DE HILO', 'Hilo dental para la adecuada limpieza de los dientes', 20.00, 15.00, 0.16, 1, 1, 1),
(5, 'Escritorio', 'Comodidad y confort del usuario', 1500.00, 2500.00, 0.16, 1, 3, 1),
(6, 'Mascarilla KN95', 'Protege la parte de la boca evitando enfermedades, virus y bacterias.', 10.00, 10.00, 0.18, 1, 2, 1),
(7, 'PASTA DENTAL', 'Util Para la higiene de los dientes', 35.00, 40.00, 0.16, 1, 3, 1),
(8, 'Cera', 'La utilizan pacientes con ortodoncia para calmar dolores y molestias de los brackets.', 45.00, 50.00, 0.16, 1, 2, 1),
(9, 'PINZA', 'Útil para el proceso de cirugía dental y demás.', 55.00, 60.00, 0.16, 1, 1, 1);

--
-- Disparadores `articulos`
--
DELIMITER $$
CREATE TRIGGER `Agregar_a_Inventario` AFTER INSERT ON `articulos` FOR EACH ROW BEGIN
INSERT INTO inventario (id_art,cant_ven,cant_com,cant_exist) VALUES(NEW.id_art,0,0,0);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calles`
--

CREATE TABLE `calles` (
  `id_calle` int(11) NOT NULL,
  `id_mun` int(11) DEFAULT NULL,
  `nom_calle` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calles`
--

INSERT INTO `calles` (`id_calle`, `id_mun`, `nom_calle`) VALUES
(1, 1, 'Calle 3'),
(2, 2, 'Calle Juan Pablo Duarte'),
(3, 3, 'Calle Libertad'),
(4, 4, 'Calle Esmeralda'),
(5, 5, 'Calle Che'),
(6, 6, 'Calle La Lomota'),
(7, 7, 'Calle #5'),
(8, 8, 'C#67'),
(9, 9, 'Calle Mella'),
(10, 10, 'Calle Niches'),
(11, 12, 'C. Ramon Castilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `cargo` varchar(60) DEFAULT NULL,
  `sueldo` decimal(11,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `cargo`, `sueldo`) VALUES
(1, 'Doctor', 50000),
(2, 'Secretario/a', 10000),
(3, 'Conserje', 7000),
(4, 'ENFERMERO', 20000),
(5, 'AUXILIAR DE ENFERMERO/A', 7000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_usuarios`
--

CREATE TABLE `carrito_usuarios` (
  `id_sesion` varchar(255) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cant_art` decimal(10,2) DEFAULT NULL,
  `concepto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cit` int(11) NOT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_doc` int(11) DEFAULT NULL,
  `fec_creacion` date DEFAULT NULL,
  `fec_cit` datetime(6) DEFAULT NULL,
  `total` decimal(19,4) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idcli` int(11) NOT NULL,
  `id_ent` int(11) DEFAULT NULL,
  `lim_cred` decimal(10,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idcli`, `id_ent`, `lim_cred`, `status`) VALUES
(1, 5, 50000.00, 1),
(2, 4, 30000.00, 1),
(3, 2, 0.00, 1),
(4, 3, 10000.00, 1),
(5, 1, 200.00, 1),
(6, 6, 2000.00, 1),
(7, 7, 20000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto_fact`
--

CREATE TABLE `concepto_fact` (
  `id` int(11) NOT NULL,
  `concepto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `concepto_fact`
--

INSERT INTO `concepto_fact` (`id`, `concepto`) VALUES
(1, 'VENTA'),
(2, 'COMPRA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxc`
--

CREATE TABLE `cxc` (
  `id_cxc` int(11) NOT NULL,
  `id_fac` int(11) DEFAULT NULL,
  `pend` decimal(10,2) DEFAULT NULL,
  `pagado` decimal(10,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cxc`
--

INSERT INTO `cxc` (`id_cxc`, `id_fac`, `pend`, `pagado`, `status`) VALUES
(1, 30, 0.00, 638.00, 6),
(2, 31, 0.00, 1276.00, 6),
(3, 32, 0.00, 1276.00, 6),
(4, 33, 0.00, 1336.00, 6),
(5, 34, 0.00, 12112.00, 6),
(6, 59, 0.00, 1276.00, 6),
(7, 60, 0.00, 1276.00, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp`
--

CREATE TABLE `cxp` (
  `id_cxp` int(11) NOT NULL,
  `id_fac` int(11) DEFAULT NULL,
  `pend` decimal(10,2) DEFAULT NULL,
  `pagado` decimal(10,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cxp`
--

INSERT INTO `cxp` (`id_cxp`, `id_fac`, `pend`, `pagado`, `status`) VALUES
(2, 7, 0.00, 33623.00, 6),
(3, 8, 0.00, 1160.00, 6),
(4, 9, 0.00, 30744.00, 6),
(5, 10, 0.00, 1886.00, 6),
(6, 11, 0.00, 30760.00, 6),
(7, 12, 0.00, 10352.00, 6),
(8, 13, 0.00, 50328.00, 6),
(9, 28, 0.00, 2329.00, 6),
(10, 51, 0.00, 10440.00, 6),
(11, 52, 0.00, 6960.00, 6),
(12, 53, 4940.00, 5500.00, 3),
(13, 54, 440.00, 10000.00, 3),
(14, 55, 0.00, 5544.80, 6),
(15, 56, 9404.00, 1500.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `id_datos` int(11) NOT NULL,
  `email` int(11) DEFAULT NULL,
  `dat_dir` int(11) DEFAULT NULL,
  `dat_telf` int(11) DEFAULT NULL,
  `dat_docu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`id_datos`, `email`, `dat_dir`, `dat_telf`, `dat_docu`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 2, 2, 2),
(3, 3, 2, 3, 3),
(4, 4, 3, 4, 4),
(5, 5, 4, 5, 5),
(6, 6, 5, 6, 6),
(7, 7, 4, 7, 7),
(8, 8, 6, 8, 8),
(9, 9, 8, 9, 9),
(10, 10, 8, 10, 10),
(11, 11, 4, 11, 11),
(12, 12, 2, 12, 12),
(13, 13, 3, 13, 13),
(14, 14, 4, 14, 14),
(15, 14, 5, 15, 15),
(16, 14, 5, 16, 16),
(17, 15, 7, 17, 17),
(18, 16, 10, 18, 18),
(19, 17, 1, 19, 19),
(20, 18, 9, 20, 20),
(21, 19, 12, 21, 21),
(22, 20, 2, 22, 22),
(23, 21, 11, 23, 23),
(24, 22, 8, 24, 24),
(25, 23, 6, 25, 25),
(26, 24, 4, 26, 26),
(27, 25, 10, 27, 27),
(28, 26, 1, 28, 28),
(29, 27, 8, 29, 29),
(30, 28, 8, 30, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_fact_com`
--

CREATE TABLE `detalle_fact_com` (
  `id_det` int(11) NOT NULL,
  `id_fac` int(11) DEFAULT NULL,
  `id_art` int(11) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `itbis` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_fact_com`
--

INSERT INTO `detalle_fact_com` (`id_det`, `id_fac`, `id_art`, `cantidad`, `subtotal`, `itbis`, `total`) VALUES
(4, 1, 1, 1.00, 500.00, 80.00, 580.00),
(5, 1, 2, 2.00, 900.00, 144.00, 1044.00),
(6, 1, 4, 30.00, 600.00, 96.00, 696.00),
(7, 2, 1, 1.00, 500.00, 80.00, 580.00),
(8, 3, 3, 5.00, 43000.00, 6880.00, 49880.00),
(9, 3, 2, 3.00, 1350.00, 216.00, 1566.00),
(10, 4, 5, 1.00, 1500.00, 240.00, 1740.00),
(11, 4, 6, 5.00, 50.00, 9.00, 59.00),
(12, 5, 1, 2.00, 1000.00, 160.00, 1160.00),
(13, 5, 2, 2.00, 900.00, 144.00, 1044.00),
(14, 5, 4, 4.00, 80.00, 12.80, 92.80),
(17, 7, 1, 4.00, 2000.00, 320.00, 2320.00),
(18, 7, 2, 2.00, 900.00, 144.00, 1044.00),
(19, 7, 3, 4.00, 34400.00, 5504.00, 39904.00),
(20, 8, 1, 2.00, 1000.00, 160.00, 1160.00),
(21, 9, 1, 3.00, 1500.00, 240.00, 1740.00),
(22, 9, 2, 8.00, 3600.00, 576.00, 4176.00),
(23, 9, 3, 3.00, 25800.00, 4128.00, 29928.00),
(24, 10, 1, 4.00, 2000.00, 320.00, 2320.00),
(25, 10, 2, 3.00, 1350.00, 216.00, 1566.00),
(26, 11, 1, 5.00, 2500.00, 400.00, 2900.00),
(27, 11, 2, 6.00, 2700.00, 432.00, 3132.00),
(28, 11, 3, 3.00, 25800.00, 4128.00, 29928.00),
(29, 12, 2, 3.00, 1350.00, 216.00, 1566.00),
(30, 12, 1, 2.00, 1000.00, 160.00, 1160.00),
(31, 12, 3, 1.00, 8600.00, 1376.00, 9976.00),
(32, 13, 1, 2.00, 1000.00, 160.00, 1160.00),
(33, 13, 2, 4.00, 1800.00, 288.00, 2088.00),
(34, 13, 3, 5.00, 43000.00, 6880.00, 49880.00),
(35, 14, 1, 5.00, 2500.00, 400.00, 2900.00),
(36, 15, 1, 5.00, 2500.00, 400.00, 2900.00),
(37, 18, 1, 4.00, 2000.00, 320.00, 2320.00),
(38, 18, 2, 6.00, 2700.00, 432.00, 3132.00),
(39, 21, 1, 5.00, 2500.00, 400.00, 2900.00),
(40, 22, 5, 1.00, 2500.00, 400.00, 2900.00),
(41, 22, 1, 4.00, 2000.00, 320.00, 2320.00),
(42, 22, 2, 4.00, 1800.00, 288.00, 2088.00),
(43, 22, 5, 3.00, 4500.00, 720.00, 5220.00),
(44, 22, 1, 2.00, 1000.00, 160.00, 1160.00),
(45, 22, 5, 1.00, 1500.00, 240.00, 1740.00),
(46, 23, 5, 1.00, 2500.00, 400.00, 2900.00),
(47, 24, 5, 1.00, 1500.00, 240.00, 1740.00),
(48, 25, 6, 5.00, 50.00, 9.00, 59.00),
(49, 26, 1, 1.00, 550.00, 88.00, 638.00),
(50, 27, 1, 5.00, 2750.00, 440.00, 3190.00),
(51, 27, 2, 42.00, 21000.00, 3360.00, 24360.00),
(52, 28, 6, 5.00, 50.00, 9.00, 59.00),
(53, 28, 1, 4.00, 2000.00, 320.00, 2320.00),
(54, 28, 1, 5.00, 2750.00, 440.00, 3190.00),
(55, 28, 1, 1.00, 550.00, 88.00, 638.00),
(56, 30, 1, 1.00, 550.00, 88.00, 638.00),
(57, 31, 1, 2.00, 1100.00, 176.00, 1276.00),
(58, 32, 1, 2.00, 1100.00, 176.00, 1276.00),
(59, 33, 1, 2.00, 1100.00, 176.00, 1276.00),
(60, 33, 2, 2.00, 1000.00, 160.00, 1160.00),
(61, 34, 1, 4.00, 2200.00, 352.00, 2552.00),
(62, 34, 2, 2.00, 1000.00, 160.00, 1160.00),
(63, 34, 5, 4.00, 10000.00, 1600.00, 11600.00),
(64, 35, 1, 3.00, 1650.00, 264.00, 1914.00),
(65, 36, 1, 2.00, 1100.00, 176.00, 1276.00),
(66, 37, 1, 2.00, 1100.00, 176.00, 1276.00),
(67, 38, 1, 2.00, 1100.00, 176.00, 1276.00),
(68, 39, 1, 1.00, 550.00, 88.00, 638.00),
(69, 40, 1, 2.00, 1100.00, 176.00, 1276.00),
(70, 41, 1, 35.00, 17500.00, 2800.00, 20300.00),
(71, 41, 2, 24.00, 10800.00, 1728.00, 12528.00),
(72, 41, 4, 41.00, 820.00, 131.20, 951.20),
(73, 42, 1, 4.00, 2200.00, 352.00, 2552.00),
(74, 42, 2, 2.00, 1000.00, 160.00, 1160.00),
(75, 43, 1, 2.00, 1100.00, 176.00, 1276.00),
(76, 43, 2, 5.00, 2500.00, 400.00, 2900.00),
(77, 44, 1, 1.00, 500.00, 80.00, 580.00),
(78, 45, 1, 2.00, 1100.00, 176.00, 1276.00),
(79, 45, 2, 1.00, 500.00, 80.00, 580.00),
(80, 45, 4, 4.00, 60.00, 9.60, 69.60),
(81, 46, 1, 4.00, 2200.00, 352.00, 2552.00),
(82, 46, 2, 1.00, 500.00, 80.00, 580.00),
(83, 47, 1, 2.00, 1100.00, 176.00, 1276.00),
(84, 47, 2, 3.00, 1500.00, 240.00, 1740.00),
(85, 48, 1, 4.00, 2200.00, 352.00, 2552.00),
(86, 48, 2, 6.00, 3000.00, 480.00, 3480.00),
(87, 49, 1, 2.00, 1100.00, 176.00, 1276.00),
(88, 50, 1, 2.00, 1100.00, 176.00, 1276.00),
(89, 51, 5, 5.00, 7500.00, 1200.00, 8700.00),
(90, 51, 1, 3.00, 1500.00, 240.00, 1740.00),
(91, 52, 5, 4.00, 6000.00, 960.00, 6960.00),
(92, 53, 1, 3.00, 1500.00, 240.00, 1740.00),
(93, 53, 5, 5.00, 7500.00, 1200.00, 8700.00),
(94, 54, 5, 5.00, 7500.00, 1200.00, 8700.00),
(95, 54, 1, 3.00, 1500.00, 240.00, 1740.00),
(96, 55, 1, 4.00, 2000.00, 320.00, 2320.00),
(97, 55, 2, 6.00, 2700.00, 432.00, 3132.00),
(98, 55, 4, 4.00, 80.00, 12.80, 92.80),
(99, 56, 1, 5.00, 2500.00, 400.00, 2900.00),
(100, 56, 5, 4.00, 6000.00, 960.00, 6960.00),
(101, 56, 2, 2.00, 900.00, 144.00, 1044.00),
(102, 57, 1, 5.00, 2750.00, 440.00, 3190.00),
(103, 57, 2, 4.00, 2000.00, 320.00, 2320.00),
(104, 57, 5, 3.00, 7500.00, 1200.00, 8700.00),
(105, 58, 9, 12.00, 660.00, 105.60, 765.60),
(106, 58, 8, 6.00, 270.00, 43.20, 313.20),
(107, 58, 7, 70.00, 2450.00, 392.00, 2842.00),
(108, 59, 1, 2.00, 1100.00, 176.00, 1276.00),
(109, 60, 1, 2.00, 1100.00, 176.00, 1276.00),
(110, 61, 1, 88.00, 48400.00, 7744.00, 56144.00),
(111, 62, 1, 50.00, 27500.00, 4400.00, 31900.00),
(112, 63, 1, 10.00, 5000.00, 800.00, 5800.00),
(113, 64, 1, 20.00, 11000.00, 1760.00, 12760.00),
(114, 65, 1, 20.00, 11000.00, 1760.00, 12760.00),
(115, 66, 1, 50.00, 27500.00, 4400.00, 31900.00),
(116, 67, 2, 30.00, 15000.00, 2400.00, 17400.00),
(117, 68, 1, 50.00, 25000.00, 4000.00, 29000.00),
(118, 69, 2, 15.00, 7500.00, 1200.00, 8700.00),
(119, 70, 2, 15.00, 7500.00, 1200.00, 8700.00),
(120, 71, 1, 20.00, 11000.00, 1760.00, 12760.00),
(121, 72, 2, 50.00, 22500.00, 3600.00, 26100.00),
(122, 73, 2, 1.00, 500.00, 80.00, 580.00),
(123, 74, 2, 12.00, 6000.00, 960.00, 6960.00),
(124, 75, 1, 28.00, 15400.00, 2464.00, 17864.00),
(125, 75, 3, 13.00, 117000.00, 18720.00, 135720.00),
(126, 75, 4, 68.00, 1020.00, 163.20, 1183.20),
(127, 76, 1, 2.00, 1100.00, 176.00, 1276.00),
(128, 77, 4, 5.00, 75.00, 12.00, 87.00),
(129, 78, 1, 15.00, 8250.00, 1320.00, 9570.00),
(130, 79, 1, 11.00, 6050.00, 968.00, 7018.00),
(131, 80, 1, 5.00, 2750.00, 440.00, 3190.00),
(132, 81, 1, 40.00, 20000.00, 3200.00, 23200.00),
(133, 82, 1, 10.00, 5500.00, 880.00, 6380.00),
(134, 83, 1, 8.00, 4400.00, 704.00, 5104.00),
(135, 84, 1, 50.00, 25000.00, 4000.00, 29000.00),
(136, 84, 2, 50.00, 22500.00, 3600.00, 26100.00),
(137, 84, 3, 50.00, 430000.00, 68800.00, 498800.00),
(138, 84, 4, 50.00, 1000.00, 160.00, 1160.00),
(139, 84, 5, 20.00, 30000.00, 4800.00, 34800.00),
(140, 84, 6, 20.00, 200.00, 36.00, 236.00),
(141, 84, 7, 20.00, 700.00, 112.00, 812.00),
(142, 84, 8, 20.00, 900.00, 144.00, 1044.00),
(143, 84, 9, 20.00, 1100.00, 176.00, 1276.00),
(144, 85, 1, 40.00, 22000.00, 3520.00, 25520.00),
(145, 86, 1, 11.00, 6050.00, 968.00, 7018.00),
(146, 87, 1, 17.00, 9350.00, 1496.00, 10846.00),
(147, 88, 1, 11.00, 6050.00, 968.00, 7018.00),
(148, 89, 1, 12.00, 6600.00, 1056.00, 7656.00),
(149, 90, 1, 13.00, 7150.00, 1144.00, 8294.00),
(150, 91, 1, 14.00, 7700.00, 1232.00, 8932.00),
(151, 92, 1, 17.00, 9350.00, 1496.00, 10846.00),
(152, 93, 1, 15.00, 8250.00, 1320.00, 9570.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_receta`
--

CREATE TABLE `detalle_receta` (
  `id_det` int(11) NOT NULL,
  `id_rec` int(11) DEFAULT NULL,
  `id_med` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_dir` int(11) NOT NULL,
  `provincia` int(11) DEFAULT NULL,
  `calle` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_dir`, `provincia`, `calle`, `status`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(3, 3, 3, 1),
(4, 4, 4, 1),
(5, 5, 5, 1),
(6, 1, 6, 1),
(7, 6, 7, 1),
(8, 7, 8, 1),
(9, 6, 9, 1),
(10, 8, 10, 1),
(11, 9, 3, 2),
(12, 10, 11, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctor`
--

CREATE TABLE `doctor` (
  `id_doc` int(11) NOT NULL,
  `id_per` int(11) DEFAULT NULL,
  `doc_esp` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_docu` int(11) NOT NULL,
  `tip_docu` int(11) DEFAULT NULL,
  `num_docu` char(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id_docu`, `tip_docu`, `num_docu`) VALUES
(1, 1, '402-01122418-1'),
(2, 1, '402-01122124-1'),
(3, 1, '096-9241894-3'),
(4, 1, '402-2999130-5'),
(5, 1, '095-298491803-10'),
(6, 4, '240-319491002-3'),
(7, 2, '096-39183419-1'),
(8, 4, '21981984120'),
(9, 4, '122-29481928-11'),
(10, 5, '999-29999241-13'),
(11, 1, '6-29814221-2091'),
(12, 1, '6-29764221-2691'),
(13, 1, '6-29763871-2692'),
(14, 5, '28-192849-129'),
(15, 5, '402-2409101-6'),
(16, 5, '402-9241892-3'),
(17, 5, '402418925021'),
(18, 1, '20948184'),
(19, 4, '999999999'),
(20, 4, '99999999999'),
(21, 5, '09829847190'),
(22, 1, '92841'),
(23, 1, '1000-2222-3333'),
(24, 1, '999-000-000'),
(25, 1, '20930219'),
(26, 2, '2837821'),
(27, 5, '402-2149451-6'),
(28, 5, '0962284198204'),
(29, 5, '0512984128471'),
(30, 5, '402-9241821-3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `id_email` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `email`
--

INSERT INTO `email` (`id_email`, `email`) VALUES
(1, 'leolorenzo@gmail.com'),
(2, 'rosalorenzo@gmail.com'),
(3, 'amarilisSuarez@hotmail.com'),
(4, 'Elpidio@hotmail.com'),
(5, 'gonzalo@hotmail.com'),
(6, 'entidad1@hotmail.com'),
(7, 'armany@outlook.com'),
(8, 'arshumano@gmail.com'),
(9, 'ARSUniversal@hotmail.com'),
(10, 'ElpidioG@hotmail.com'),
(11, 'julito@hotmail.com'),
(12, 'germoso@hotmail.com'),
(13, 'Gabriel@hotmail.com'),
(14, 'SamuelP@hotmail.com'),
(15, 'guillermoT@Hotmail.com'),
(16, 'juanR@gmail.com'),
(17, 'FkLab@hotmail.com'),
(18, 'clinicacorominas@gmail.com'),
(19, 'jaredSanchez@hotmail.com'),
(20, 'carlosmaa@gmail.com'),
(21, 'CarlosPerez@gmail.com'),
(22, 'michHernandez@gmail.com'),
(23, 'lsafknal@gmal.com'),
(24, 'way@gmail.com'),
(25, 'gonzalo@gmail.com'),
(26, 'lucifer@gmail.com'),
(27, 'botello4@gmail.com'),
(28, 'tomasramirez@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empl` int(11) NOT NULL,
  `id_per` int(11) DEFAULT NULL,
  `cargo` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `horario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empl`, `id_per`, `cargo`, `status`, `horario`) VALUES
(1, 1, 1, 1, 1),
(2, 3, 1, 1, 2),
(3, 2, 5, 1, 1),
(4, 5, 1, 1, 2),
(5, 6, 3, 1, 3),
(6, 4, 1, 1, 1),
(7, 10, 1, 1, 2),
(8, 12, 1, 1, 2),
(9, 13, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empl_vs_espec`
--

CREATE TABLE `empl_vs_espec` (
  `id_vs` int(11) NOT NULL,
  `empl` int(11) DEFAULT NULL,
  `espec` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empl_vs_espec`
--

INSERT INTO `empl_vs_espec` (`id_vs`, `empl`, `espec`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 1, 2),
(4, 4, 5),
(5, 6, 2),
(6, 7, 3),
(7, 7, 4),
(8, 8, 1),
(9, 4, 1),
(10, 9, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad`
--

CREATE TABLE `entidad` (
  `id_ent` int(11) NOT NULL,
  `nom_ent` varchar(100) DEFAULT NULL,
  `datos` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entidad`
--

INSERT INTO `entidad` (`id_ent`, `nom_ent`, `datos`, `status`) VALUES
(1, 'Entidad01', 6, 2),
(2, 'ARS HUMANO', 8, 1),
(3, 'ARS UNIVERSAL', 9, 1),
(4, 'Laboratorios FK', 19, 1),
(5, 'CLINICA COROMINAS', 20, 1),
(6, 'carlos maa', 22, 1),
(7, 'way', 26, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id_esp` int(11) NOT NULL,
  `des_esp` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_esp`, `des_esp`) VALUES
(1, 'Dentista general'),
(2, 'Ortodoncista'),
(3, 'Odontopediatra'),
(4, 'Endodoncista'),
(5, 'Patólogo oral');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_status` int(11) NOT NULL,
  `des_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_status`, `des_status`) VALUES
(1, 'ACTIVO'),
(2, 'INACTIVO'),
(3, 'PENDIENTE'),
(4, 'ELIMINADO'),
(5, 'EN CURSO'),
(6, 'PAGADO'),
(7, 'FINALIZADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `start` date NOT NULL,
  `color` varchar(20) NOT NULL,
  `doctor` int(11) DEFAULT NULL,
  `paciente` int(11) DEFAULT NULL,
  `servicio` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_pago` int(11) NOT NULL,
  `hora` time DEFAULT NULL,
  `fin_cita` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id`, `title`, `start`, `color`, `doctor`, `paciente`, `servicio`, `status`, `status_pago`, `hora`, `fin_cita`) VALUES
(72, 'Nueva Cita', '2022-07-14', '#e00b0b', 1, 2, 2, 4, 4, '17:09:00', '18:09:00'),
(73, 'Nueva Cita 2', '2022-07-14', '#ff0505', 2, 2, 3, 4, 4, '21:53:00', '22:53:00'),
(74, 'Cita de Limpieza', '2022-07-15', '#04ff00', 2, 2, 2, 7, 6, '12:04:00', '13:04:00'),
(75, 'nuevo ', '2022-07-22', '#000000', 2, 1, 1, 7, 6, '17:00:00', '18:00:00'),
(76, 'skjna', '2022-07-19', '#d10000', 2, 3, 2, 7, 6, '15:23:00', '16:23:00'),
(77, 'naijfa', '2022-07-26', '#000000', 2, 5, 2, 4, 4, '20:46:00', '21:46:00'),
(78, 'Cita de Ortodoncia', '2022-07-20', '#002aff', 4, 5, 3, 7, 6, '12:00:00', '13:00:00'),
(79, 'skfmasl', '2022-08-06', '#000000', 4, 2, 2, 4, 4, '13:00:00', '14:00:00'),
(80, 'Cita Especial', '2022-08-12', '#000000', 2, 1, 1, 7, 6, '14:30:00', '15:30:00'),
(81, 'Limpieza', '2023-03-25', '#b60c0c', 6, 2, 2, 4, 4, '15:30:00', '16:30:00'),
(82, 'Limpieza', '2023-03-25', '#b60c0c', 4, 2, 1, 4, 4, '17:22:00', '18:22:00'),
(83, 'Limpieza', '2023-03-25', '#39a79a', 4, 4, 3, 7, 6, '00:00:00', '01:00:00'),
(84, 'Limpieza', '2023-03-29', '#b60c0c', 2, 2, 2, 4, 4, '00:00:00', '01:00:00'),
(85, 'Limpieza', '2023-03-25', '#39a79a', 2, 2, 2, 4, 4, '00:00:00', '01:00:00'),
(86, 'Limpieza', '2023-03-28', '#39a79a', 2, 2, 2, 4, 4, '00:00:00', '01:00:00'),
(87, 'Limpieza', '2023-03-25', '#39a79a', 6, 3, 2, 4, 4, '00:00:00', '01:00:00'),
(88, 'Limpieza', '2023-03-26', '#39a79a', 6, 3, 2, 4, 4, '00:00:00', '01:00:00'),
(89, 'Limpieza', '2023-03-27', '#39a79a', 6, 3, 2, 4, 4, '00:00:00', '01:00:00'),
(90, 'Si', '2023-03-25', '#000000', 4, 2, 2, 4, 4, '00:00:00', '01:00:00'),
(91, 'nueva cita', '2023-04-15', '#94ffb9', 4, 2, 2, 7, 6, '17:00:00', '18:00:00'),
(92, 'Evaluacion Craneal', '2023-04-18', '#22aa56', 6, 5, 1, 7, 6, '18:00:00', '19:00:00'),
(93, 'Prueba', '2023-04-27', '#166f20', 7, 6, 3, 4, 6, '22:07:00', '23:07:00'),
(94, 'Quelosque', '2023-04-27', '#a0276e', 4, 2, 2, 4, 6, '22:00:00', '23:00:00'),
(95, 'Colocar Brackets', '2023-04-26', '#1885b4', 6, 6, 3, 4, 3, '16:18:00', '17:18:00'),
(96, 'PRUEBA2', '2023-04-25', '#000000', 6, 6, 3, 4, 3, '21:00:00', '22:00:00'),
(97, 'cita nueva otra ve', '2023-04-29', '#000000', 7, 4, 2, 4, 3, '23:59:00', '24:59:00'),
(98, 'Cita de limpieza', '2023-04-28', '#000000', 9, 2, 2, 4, 3, '08:36:00', '09:36:00'),
(101, 'SKAJ', '2023-04-27', '#000000', 9, 2, 2, 4, 3, '08:13:00', '09:13:00'),
(104, 'hola2', '2023-04-27', '#000000', 9, 2, 2, 4, 3, '10:15:00', '10:45:00'),
(105, 'pruebin', '2023-04-29', '#000000', 6, 6, 3, 4, 3, '10:47:00', '11:10:00'),
(106, 'pruebita', '2023-05-01', '#000000', 9, 2, 2, 4, 3, '10:25:00', '11:25:00'),
(107, 'pruebita2', '2023-04-27', '#000000', 9, 2, 2, 4, 3, '10:25:00', '11:25:00'),
(108, 'ortodoncia', '2023-04-28', '#fd1c1c', 6, 6, 3, 4, 6, '12:59:00', '14:44:00'),
(109, 'EXTRACCION', '2023-04-29', '#440909', 1, 5, 4, 4, 6, '08:09:00', '09:54:00'),
(110, 'EVALUACION', '2023-04-28', '#1d8684', 1, 6, 1, 4, 6, '10:49:00', '11:49:00'),
(111, 'servicio', '2023-04-28', '#000000', 4, 2, 1, 4, 3, '21:46:00', '22:46:00'),
(112, 'Cita de Limpieza', '2023-04-28', '#000000', 9, 5, 2, 4, 3, '15:30:00', '16:30:00'),
(113, 'cita 1', '2023-12-20', '#000000', 6, 2, 3, 5, 3, '08:28:00', '10:13:00');

--
-- Disparadores `evento`
--
DELIMITER $$
CREATE TRIGGER `asign_hora_fin` BEFORE INSERT ON `evento` FOR EACH ROW BEGIN

DECLARE duracion TIME;
SET duracion=(SELECT ts.duracion FROM tip_servicio ts Inner JOIN servicios ser ON ser.tip_ser=ts.id_tip where ser.id_ser=NEW.servicio limit 1);

   SET NEW.fin_cita=ADDTIME(NEW.hora,duracion);
   
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_fac` int(11) NOT NULL,
  `fec_fac` date DEFAULT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `total_pag` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_fac`, `fec_fac`, `id_pac`, `id_cita`, `total_pag`) VALUES
(1, '2022-07-17', 2, 73, 500.00),
(2, '2022-07-17', 2, 73, 100.00),
(3, '2022-07-17', 1, 75, 700.00),
(4, '2022-07-17', 1, 75, 1300.00),
(5, '2022-07-18', 2, 73, 600.00),
(6, '2022-07-19', 3, 76, 2500.00),
(7, '2022-07-19', 5, 77, 2500.00),
(8, '2022-07-19', 5, 78, 24000.00),
(9, '2022-07-19', 5, 78, 26000.00),
(11, '2022-08-06', 2, 73, 1.00),
(12, '2022-08-06', 2, 79, 500.00),
(13, '2022-08-06', 2, 73, 99.00),
(14, '2022-08-06', 2, 74, 500.00),
(15, '2022-08-06', 2, 79, 500.00),
(16, '2022-08-06', 2, 79, 1500.00),
(17, '2022-08-06', 2, 74, 500.00),
(18, '2022-08-06', 2, 73, 10500.00),
(19, '2022-08-06', 2, 74, 1100.00),
(20, '2022-08-16', 2, 74, 100.00),
(21, '2023-04-18', 4, 93, 25000.00),
(22, '2023-04-18', 4, 93, 25000.00),
(23, '2023-04-18', 1, 80, 500.00),
(24, '2023-04-18', 2, 74, 300.00),
(25, '2023-04-18', 1, 80, 1500.00),
(26, '2023-04-18', 2, 91, 2500.00),
(27, '2023-04-18', 4, 83, 50000.00),
(28, '2023-04-18', 5, 92, 2000.00),
(29, '2023-04-18', 2, 94, 2500.00),
(30, '2023-04-26', 5, 109, 2500.00),
(31, '2023-04-26', 6, 108, 50000.00),
(32, '2023-04-26', 6, 93, 50000.00),
(33, '2023-04-26', 6, 110, 2000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_compra`
--

CREATE TABLE `factura_compra` (
  `id_fac` int(11) NOT NULL,
  `fec_fac` datetime DEFAULT NULL,
  `tip_fac` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `itbis` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `concepto` int(11) DEFAULT NULL,
  `entidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura_compra`
--

INSERT INTO `factura_compra` (`id_fac`, `fec_fac`, `tip_fac`, `subtotal`, `itbis`, `total`, `status`, `concepto`, `entidad`) VALUES
(1, '2022-08-04 21:03:58', 2, 2000.00, 320.00, 2320.00, 6, 2, 1),
(2, '2022-08-04 21:27:20', 2, 500.00, 80.00, 580.00, 6, 2, 1),
(3, '2022-08-04 21:29:58', 2, 44350.00, 7096.00, 51446.00, 6, 2, 1),
(4, '2022-08-06 16:42:55', 2, 1550.00, 249.00, 299.00, 6, 2, 1),
(5, '2022-08-06 17:09:32', 2, 1980.00, 316.80, 396.80, 6, 2, 1),
(7, '2022-08-06 22:20:09', 1, 37300.00, 5968.00, 40368.00, 6, 2, 1),
(8, '2022-08-07 15:12:59', 1, 1000.00, 160.00, 1160.00, 6, 2, 1),
(9, '2022-08-07 15:19:29', 1, 30900.00, 4944.00, 30744.00, 6, 2, 1),
(10, '2022-08-07 15:24:33', 1, 3350.00, 536.00, 1886.00, 6, 2, 1),
(11, '2022-08-07 15:27:43', 1, 31000.00, 4960.00, 30760.00, 6, 2, 1),
(12, '2022-08-07 15:28:10', 1, 10950.00, 1752.00, 10352.00, 6, 2, 1),
(13, '2022-08-09 20:21:44', 1, 45800.00, 7328.00, 50328.00, 6, 2, 1),
(14, '2022-08-11 14:11:35', 2, 2500.00, 400.00, 2900.00, 6, 2, 1),
(15, '2022-08-11 14:12:26', 2, 2500.00, 400.00, 2900.00, 6, 2, 1),
(16, '2022-08-11 14:12:38', 2, 0.00, 0.00, 0.00, 6, 2, 1),
(17, '2022-08-11 14:15:12', 2, 0.00, 0.00, 0.00, 6, 2, 1),
(18, '2022-08-11 14:16:46', 2, 4700.00, 752.00, 3452.00, 6, 2, 1),
(19, '2022-08-11 14:16:49', 2, 0.00, 0.00, 0.00, 6, 2, 1),
(20, '2022-08-11 14:16:51', 2, 0.00, 0.00, 0.00, 6, 2, 1),
(21, '2022-08-11 14:24:14', 2, 2500.00, 400.00, 2900.00, 6, 2, 1),
(22, '2022-08-11 23:49:43', 1, 2500.00, 400.00, 2900.00, 3, 1, 4),
(23, '2022-08-12 00:03:04', 2, 2500.00, 400.00, 2900.00, 6, 1, 4),
(24, '2022-08-12 00:04:09', 2, 1500.00, 240.00, 1740.00, 6, 2, 1),
(25, '2022-08-12 00:10:46', 2, 50.00, 9.00, 59.00, 6, 1, 4),
(26, '2022-08-12 15:08:03', 1, 550.00, 88.00, 638.00, 3, 1, 4),
(27, '2022-08-12 15:16:11', 2, 23750.00, 3800.00, 24800.00, 6, 1, 4),
(28, '2022-08-12 15:44:34', 1, 2050.00, 329.00, 2329.00, 6, 2, 1),
(30, '2022-08-12 17:43:58', 1, 550.00, 88.00, 638.00, 6, 1, 5),
(31, '2022-08-12 17:46:51', 1, 1100.00, 176.00, 1276.00, 6, 1, 5),
(32, '2022-08-12 17:52:08', 1, 1100.00, 176.00, 1276.00, 6, 1, 5),
(33, '2022-08-12 18:02:02', 1, 2100.00, 336.00, 1336.00, 6, 1, 4),
(34, '2022-08-12 18:18:57', 1, 13200.00, 2112.00, 12112.00, 6, 1, 4),
(35, '2022-08-12 18:58:07', 2, 1650.00, 264.00, 1914.00, 6, 1, 2),
(36, '2022-08-16 10:49:23', 2, 1100.00, 176.00, 1276.00, 6, 1, 5),
(37, '2022-08-16 10:55:05', 2, 1100.00, 176.00, 1276.00, 6, 1, 5),
(38, '2022-08-16 11:08:29', 2, 1100.00, 176.00, 1276.00, 6, 1, 4),
(39, '2022-08-16 11:18:23', 2, 550.00, 88.00, 638.00, 6, 1, 5),
(40, '2022-08-16 11:20:35', 2, 1100.00, 176.00, 1276.00, 6, 1, 2),
(41, '2022-08-16 11:25:19', 2, 29120.00, 4659.20, 5479.20, 6, 2, 1),
(42, '2022-08-16 16:47:02', 2, 3200.00, 512.00, 1512.00, 6, 1, 4),
(43, '2022-08-16 16:48:24', 2, 3600.00, 576.00, 3076.00, 6, 1, 4),
(44, '2022-08-16 17:01:51', 2, 500.00, 80.00, 580.00, 6, 2, 1),
(45, '2022-08-16 17:18:15', 2, 1660.00, 265.60, 325.60, 6, 1, 5),
(46, '2022-08-16 17:22:20', 2, 2700.00, 432.00, 932.00, 6, 1, 4),
(47, '2022-08-16 17:35:09', 2, 2600.00, 416.00, 1916.00, 6, 1, 5),
(48, '2022-08-16 17:39:46', 2, 5200.00, 832.00, 3832.00, 6, 1, 4),
(49, '2022-08-17 07:16:39', 2, 1100.00, 176.00, 1276.00, 6, 1, 4),
(50, '2022-08-17 07:18:17', 2, 1100.00, 176.00, 1276.00, 6, 1, 4),
(51, '2022-08-17 16:48:59', 1, 9000.00, 1440.00, 10440.00, 6, 2, 1),
(52, '2022-08-17 16:50:51', 1, 6000.00, 960.00, 6960.00, 6, 2, 1),
(53, '2022-08-17 16:53:29', 1, 9000.00, 1440.00, 10440.00, 3, 2, 1),
(54, '2022-08-17 17:02:46', 1, 9000.00, 1440.00, 10440.00, 3, 2, 1),
(55, '2022-08-17 17:14:29', 1, 4780.00, 764.80, 5544.80, 6, 2, 1),
(56, '2022-08-17 17:15:08', 1, 9400.00, 1504.00, 10904.00, 3, 2, 1),
(57, '2022-08-17 17:28:07', 2, 12250.00, 1960.00, 14210.00, 6, 1, 5),
(58, '2022-08-18 00:36:54', 2, 3380.00, 540.80, 3920.80, 6, 2, 4),
(59, '2022-09-29 00:40:10', 1, 1100.00, 176.00, 1276.00, 6, 1, 7),
(60, '2023-04-21 18:13:44', 1, 1100.00, 176.00, 1276.00, 6, 1, 5),
(61, '2023-12-19 10:11:06', 2, 48400.00, 7744.00, 56144.00, 6, 1, 6),
(62, '2023-12-19 10:22:29', 2, 27500.00, 4400.00, 31900.00, 6, 1, 1),
(63, '2023-12-19 10:23:22', 2, 5000.00, 800.00, 5800.00, 6, 2, 1),
(64, '2023-12-19 10:35:04', 2, 11000.00, 1760.00, 12760.00, 6, 1, 1),
(65, '2023-12-19 10:35:53', 2, 11000.00, 1760.00, 12760.00, 6, 1, 1),
(66, '2023-12-19 16:07:24', 2, 27500.00, 4400.00, 31900.00, 6, 1, 1),
(67, '2023-12-19 16:54:57', 2, 15000.00, 2400.00, 17400.00, 6, 1, 6),
(68, '2023-12-20 18:16:51', 2, 25000.00, 4000.00, 29000.00, 6, 2, 1),
(69, '2023-12-20 22:26:26', 2, 7500.00, 1200.00, 8700.00, 6, 1, 1),
(70, '2023-12-20 22:27:42', 2, 7500.00, 1200.00, 8700.00, 6, 1, 1),
(71, '2023-12-20 22:28:14', 2, 11000.00, 1760.00, 12760.00, 6, 1, 5),
(72, '2023-12-20 22:28:42', 2, 22500.00, 3600.00, 26100.00, 6, 2, 1),
(73, '2023-12-20 22:29:52', 2, 500.00, 80.00, 580.00, 6, 1, 6),
(74, '2023-12-20 22:30:22', 2, 6000.00, 960.00, 6960.00, 6, 1, 6),
(75, '2023-12-20 22:31:28', 2, 133420.00, 21347.20, 154767.20, 6, 1, 2),
(76, '2023-12-20 22:31:52', 2, 1100.00, 176.00, 1276.00, 6, 1, 5),
(77, '2023-12-20 22:42:32', 2, 75.00, 12.00, 87.00, 6, 1, 5),
(78, '2023-12-20 22:55:09', 2, 8250.00, 1320.00, 9570.00, 6, 1, 4),
(79, '2023-12-20 22:56:09', 2, 6050.00, 968.00, 7018.00, 6, 1, 6),
(80, '2023-12-20 22:57:29', 2, 2750.00, 440.00, 3190.00, 6, 1, 5),
(81, '2023-12-20 22:57:55', 2, 20000.00, 3200.00, 23200.00, 6, 2, 1),
(82, '2023-12-20 22:58:11', 2, 5500.00, 880.00, 6380.00, 6, 1, 3),
(83, '2023-12-20 23:00:06', 2, 4400.00, 704.00, 5104.00, 6, 1, 5),
(84, '2023-12-20 23:01:28', 2, 511400.00, 81828.00, 593228.00, 6, 2, 1),
(85, '2023-12-20 23:06:11', 2, 22000.00, 3520.00, 25520.00, 6, 1, 5),
(86, '2023-12-20 23:06:49', 2, 6050.00, 968.00, 7018.00, 6, 1, 5),
(87, '2023-12-20 23:10:21', 2, 9350.00, 1496.00, 10846.00, 6, 1, 6),
(88, '2023-12-20 23:17:02', 2, 6050.00, 968.00, 7018.00, 6, 1, 5),
(89, '2023-12-20 23:18:46', 2, 6600.00, 1056.00, 7656.00, 6, 1, 5),
(90, '2023-12-20 23:19:34', 2, 7150.00, 1144.00, 8294.00, 6, 1, 5),
(91, '2023-12-20 23:24:11', 2, 7700.00, 1232.00, 8932.00, 6, 1, 5),
(92, '2023-12-21 00:00:28', 2, 9350.00, 1496.00, 10846.00, 6, 1, 1),
(93, '2023-12-21 00:06:20', 2, 8250.00, 1320.00, 9570.00, 6, 1, 6);

--
-- Disparadores `factura_compra`
--
DELIMITER $$
CREATE TRIGGER `Agregar_cuenta` AFTER INSERT ON `factura_compra` FOR EACH ROW BEGIN

IF NEW.tip_fac=1 AND NEW.concepto=2 THEN
INSERT INTO cxp(id_fac,pagado,pend,status) VALUES(NEW.id_fac,0,NEW.total,3);
ELSEIF NEW.tip_fac=1 AND NEW.concepto=1 THEN
INSERT INTO cxc(id_fac,pagado,pend,status) VALUES(NEW.id_fac,0,NEW.total,3);
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pac`
--

CREATE TABLE `historial_pac` (
  `id_hist` int(11) NOT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_cit` int(11) DEFAULT NULL,
  `id_ser` int(11) DEFAULT NULL,
  `det_hist` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hist_cxc`
--

CREATE TABLE `hist_cxc` (
  `id` int(11) NOT NULL,
  `id_cxc` int(11) DEFAULT NULL,
  `pago` decimal(10,2) DEFAULT NULL,
  `pend` decimal(10,2) DEFAULT NULL,
  `fec_pag` datetime DEFAULT NULL,
  `met_pag` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hist_cxc`
--

INSERT INTO `hist_cxc` (`id`, `id_cxc`, `pago`, `pend`, `fec_pag`, `met_pag`, `status`) VALUES
(1, 1, 300.00, 338.00, '2022-08-12 19:38:55', 1, 1),
(2, 1, 338.00, 0.00, '2022-08-12 19:42:04', 1, 1),
(3, 5, 5000.00, 7112.00, '2022-08-12 20:13:11', 1, 1),
(4, 4, 1200.00, 136.00, '2022-08-16 11:32:12', 2, 1),
(5, 4, 136.00, 0.00, '2022-08-18 00:24:14', 1, 1),
(6, 5, 7112.00, 0.00, '2022-08-18 00:24:31', 2, 1),
(7, 7, 1276.00, 0.00, '2023-04-21 18:14:58', 1, 1),
(8, 2, 1276.00, 0.00, '2023-04-21 18:15:21', 1, 1),
(9, 3, 1276.00, 0.00, '2023-04-21 18:15:34', 1, 1),
(10, 6, 1000.00, 276.00, '2023-04-21 18:15:48', 1, 1),
(11, 6, 276.00, 0.00, '2023-04-21 18:16:26', 1, 1);

--
-- Disparadores `hist_cxc`
--
DELIMITER $$
CREATE TRIGGER `Actualiza_cxc` AFTER INSERT ON `hist_cxc` FOR EACH ROW BEGIN

UPDATE cxc
SET
pagado=pagado+NEW.pago,
pend=NEW.pend
WHERE id_cxc=NEW.id_cxc;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hist_cxp`
--

CREATE TABLE `hist_cxp` (
  `id` int(11) NOT NULL,
  `id_cxp` int(11) DEFAULT NULL,
  `pago` decimal(10,2) DEFAULT NULL,
  `pend` decimal(10,2) DEFAULT NULL,
  `fec_pag` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `met_pag` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hist_cxp`
--

INSERT INTO `hist_cxp` (`id`, `id_cxp`, `pago`, `pend`, `fec_pag`, `status`, `met_pag`) VALUES
(1, 2, 500.00, 39868.00, '2022-08-07 14:47:49', 1, 1),
(2, 2, 500.00, 39368.00, '2022-08-07 14:51:08', 1, 1),
(3, 2, 1.00, 39367.00, '2022-08-07 14:52:15', 1, 1),
(4, 2, 99.00, 38268.00, '2022-08-07 14:52:34', 1, 1),
(5, 2, 7.00, 37260.00, '2022-08-07 14:53:29', 1, 1),
(6, 2, 160.00, 36000.00, '2022-08-07 14:56:03', 1, 1),
(7, 2, 3.00, 34890.00, '2022-08-07 14:56:55', 1, 1),
(9, 2, 20000.00, 13623.00, '2022-08-07 15:05:47', 1, 1),
(10, 2, 12353.00, 0.00, '2022-08-07 15:09:47', 1, 1),
(11, 3, 1160.00, 0.00, '2022-08-07 15:13:20', 1, 1),
(12, 4, 744.00, 30000.00, '2022-08-07 15:19:56', 1, 1),
(13, 4, 30000.00, 0.00, '2022-08-07 15:20:53', 1, 1),
(14, 5, 886.00, 1000.00, '2022-08-07 15:24:49', 1, 1),
(15, 5, 1000.00, 0.00, '2022-08-07 15:25:04', 1, 1),
(16, 6, 760.00, 30000.00, '2022-08-07 15:28:28', 1, 1),
(17, 7, 10352.00, 0.00, '2022-08-07 15:28:42', 1, 1),
(18, 6, 5000.00, 25000.00, '2022-08-07 15:30:49', 1, 1),
(19, 6, 25000.00, 0.00, '2022-08-07 15:36:49', 1, 1),
(20, 8, 50328.00, 0.00, '2022-08-12 00:16:22', 1, 1),
(21, 9, 2000.00, 329.00, '2022-08-12 15:44:58', 1, 2),
(22, 14, 3500.00, 2044.80, '2022-08-18 00:23:03', 1, 1),
(23, 9, 329.00, 0.00, '2022-08-18 00:23:15', 1, 1),
(24, 11, 1255.00, 5705.00, '2022-08-18 00:23:27', 1, 1),
(25, 13, 10000.00, 440.00, '2022-08-18 00:23:37', 1, 1),
(26, 15, 1500.00, 9404.00, '2022-08-24 14:56:06', 1, 1),
(27, 14, 2044.80, 0.00, '2022-08-24 14:56:26', 1, NULL),
(28, 12, 5500.00, 4940.00, '2022-08-24 14:56:37', 1, NULL),
(29, 10, 10000.00, 440.00, '2022-08-24 14:56:47', 1, NULL),
(30, 10, 440.00, 0.00, '2022-08-24 14:56:59', 1, NULL),
(31, 11, 5705.00, 0.00, '2022-08-24 14:57:28', 1, NULL);

--
-- Disparadores `hist_cxp`
--
DELIMITER $$
CREATE TRIGGER `Actualiza_cxp` AFTER INSERT ON `hist_cxp` FOR EACH ROW BEGIN

UPDATE cxp
SET
pagado=pagado+NEW.pago,
pend=NEW.pend
WHERE id_cxp=NEW.id_cxp;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `entrada` time DEFAULT NULL,
  `salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `entrada`, `salida`) VALUES
(1, '08:00:00', '18:00:00'),
(2, '18:00:00', '23:59:59'),
(3, '08:00:00', '16:00:00'),
(4, '07:00:00', '15:00:00'),
(5, '02:00:00', '08:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `id_art` int(11) DEFAULT NULL,
  `cant_ven` decimal(10,2) DEFAULT NULL,
  `cant_com` decimal(10,2) DEFAULT NULL,
  `cant_exist` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `id_art`, `cant_ven`, `cant_com`, `cant_exist`) VALUES
(1, 1, 362.00, 261.00, 20.00),
(2, 2, 119.00, 178.00, 59.00),
(3, 3, 13.00, 71.00, 58.00),
(4, 4, 73.00, 129.00, 56.00),
(5, 5, 6.00, 49.00, 43.00),
(6, 6, 5.00, 30.00, 25.00),
(7, 7, 0.00, 90.00, 90.00),
(8, 8, 0.00, 26.00, 26.00),
(9, 9, 0.00, 32.00, 32.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorios`
--

CREATE TABLE `laboratorios` (
  `id_lab` int(11) NOT NULL,
  `lab` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id_med` int(11) NOT NULL,
  `nom_med` varchar(50) DEFAULT NULL,
  `id_lab` int(11) DEFAULT NULL,
  `precio` decimal(19,4) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `id_met` int(11) NOT NULL,
  `metodo` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`id_met`, `metodo`, `status`) VALUES
(1, 'EN EFECTIVO', 1),
(2, 'CON TARJETA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `id_mun` int(11) NOT NULL,
  `id_prov` int(11) DEFAULT NULL,
  `nom_mun` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id_mun`, `id_prov`, `nom_mun`) VALUES
(1, 1, 'Navarrete'),
(2, 2, 'Cienfuegos'),
(3, 3, 'Villa Almirante'),
(4, 4, 'Municipio Cubano'),
(5, 5, 'Municipio de cuevas'),
(6, 1, 'Villa Nueva'),
(7, 6, 'Las Lagunas'),
(8, 7, 'Los Alcarrizos'),
(9, 6, 'Las Silvas'),
(10, 8, 'Guanajuato'),
(11, 9, 'La Circa'),
(12, 10, 'Prov Ramon Castilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id_niv` int(11) NOT NULL,
  `nivel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id_niv`, `nivel`) VALUES
(1, 'Admin'),
(2, 'Invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_vs_privilegio`
--

CREATE TABLE `nivel_vs_privilegio` (
  `id` int(11) NOT NULL,
  `nivel` int(11) DEFAULT NULL,
  `priv` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_pac` int(11) NOT NULL,
  `id_per` int(11) DEFAULT NULL,
  `fec_ingr` date DEFAULT NULL,
  `seg_pac` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `padec_pac` longtext DEFAULT NULL,
  `alerg_pac` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id_pac`, `id_per`, `fec_ingr`, `seg_pac`, `status`, `padec_pac`, `alerg_pac`) VALUES
(1, 1, '2022-07-12', 1, 2, 'Alergia al metal y al latex', 'N/A'),
(2, 2, '2022-07-14', 2, 1, 'borracho', 'NO TIENE'),
(3, 4, '2022-07-16', 3, 2, 'Caries', 'No tiene alergias'),
(4, 3, '2022-07-18', 4, 2, 'Padece blablabla', 'Ninguna'),
(5, 6, '2022-07-19', 5, 1, 'n/a', 'No tiene alergias'),
(6, 7, '2023-04-18', 6, 1, 'No padece nada', 'No tiene Alergias'),
(7, 11, '2023-04-20', 7, 1, 'No padece nada', 'Tiene alergias a la biblia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pac_vs_alerg`
--

CREATE TABLE `pac_vs_alerg` (
  `id` int(11) NOT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_alerg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pac_vs_alerg`
--

INSERT INTO `pac_vs_alerg` (`id`, `id_pac`, `id_alerg`) VALUES
(9, 1, 1),
(10, 1, 2),
(11, 1, 3),
(12, 2, 1),
(13, 2, 2),
(14, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pac_vs_padec`
--

CREATE TABLE `pac_vs_padec` (
  `id` int(11) NOT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_pad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pac_vs_padec`
--

INSERT INTO `pac_vs_padec` (`id`, `id_pac`, `id_pad`) VALUES
(11, 1, 1),
(12, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padecimientos`
--

CREATE TABLE `padecimientos` (
  `id_pad` int(11) NOT NULL,
  `nom_pad` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `padecimientos`
--

INSERT INTO `padecimientos` (`id_pad`, `nom_pad`) VALUES
(1, 'CANCER BUCAL'),
(2, 'GINGIVITIS'),
(3, 'CARIES'),
(4, 'HERPES LABIAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `id_pais` int(11) NOT NULL,
  `nom_pais` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`id_pais`, `nom_pais`) VALUES
(1, 'Republica Dominicana'),
(2, 'Republica Dominicana'),
(3, 'Argentina'),
(4, 'Colombia'),
(5, 'Cuba'),
(6, 'Mexico'),
(7, 'Islandia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_per` int(11) NOT NULL,
  `nom_per` varchar(50) DEFAULT NULL,
  `ape_per` int(11) DEFAULT NULL,
  `fec_nac` date DEFAULT NULL,
  `sex_per` char(1) DEFAULT NULL,
  `datos` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_per`, `nom_per`, `ape_per`, `fec_nac`, `sex_per`, `datos`, `status`) VALUES
(1, 'Gabriel', 2, '1999-01-22', 'M', 13, 1),
(2, 'Samuel', 2, '2001-11-11', 'M', 14, 1),
(3, 'Leo', 3, '2002-03-22', 'M', 15, 1),
(4, 'Rosa', 3, '2005-07-25', 'F', 16, 1),
(5, 'Guillermo', 4, '1998-02-24', 'M', 17, 1),
(6, 'Juan', 5, '1993-03-25', 'M', 18, 1),
(7, 'Jared', 6, '1998-06-29', 'M', 21, 1),
(8, 'Carlos', 6, '1999-03-23', 'M', 23, 1),
(9, 'Michael', 7, '1995-02-01', 'M', 24, 1),
(10, 'Gonzalo', 9, '1998-01-13', 'M', 27, 1),
(11, 'Lucifer', 9, '1996-06-06', 'M', 28, 1),
(12, 'Pedro', 10, '1975-10-12', 'M', 29, 1),
(13, 'Tomas', 11, '1994-09-30', 'M', 30, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegios`
--

CREATE TABLE `privilegios` (
  `id_priv` int(11) NOT NULL,
  `priv_user` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `id_prov` int(11) NOT NULL,
  `pais` int(11) DEFAULT NULL,
  `nom_prov` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id_prov`, `pais`, `nom_prov`) VALUES
(1, 1, 'Santiago'),
(2, 2, 'Santiago'),
(3, 4, 'Amazonas'),
(4, 5, 'Pinar Del Rio'),
(5, 5, 'La Habana'),
(6, 1, 'Moca'),
(7, 1, 'Santo Domingo'),
(8, 6, 'Guerrero'),
(9, 1, 'Espaillat'),
(10, 7, 'Mariscal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE `receta` (
  `id_rec` int(11) NOT NULL,
  `fec_rec` date DEFAULT NULL,
  `id_pac` int(11) DEFAULT NULL,
  `id_doc` int(11) DEFAULT NULL,
  `prec_total` decimal(19,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguro`
--

CREATE TABLE `seguro` (
  `id_seg` int(11) NOT NULL,
  `nom_seg` int(11) DEFAULT NULL,
  `seg_afi` int(11) DEFAULT NULL,
  `num_contrato` char(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguro`
--

INSERT INTO `seguro` (`id_seg`, `nom_seg`, `seg_afi`, `num_contrato`) VALUES
(1, 3, 1, '111928401'),
(2, 3, 2, '120409012094'),
(3, 2, 4, '21999921'),
(4, 3, 3, '2091481892'),
(5, 3, 6, '137813814'),
(6, 2, 7, '13948319812'),
(7, 3, 11, '1239184218');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_ser` int(11) NOT NULL,
  `nom_ser` varchar(100) DEFAULT NULL,
  `cost_ser` decimal(19,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `espec_req` int(11) DEFAULT NULL,
  `tip_ser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_ser`, `nom_ser`, `cost_ser`, `status`, `espec_req`, `tip_ser`) VALUES
(1, 'EVALUACION COMPLETA', 2000.00, 1, 1, 2),
(2, 'LIMPIEZA DENTAL', 2500.00, 1, 4, 2),
(3, 'ORTODONCIA', 50000.00, 1, 2, 3),
(4, 'EXTRACCION DE CORDAL', 2500.00, 1, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suplidores`
--

CREATE TABLE `suplidores` (
  `id_sup` int(11) NOT NULL,
  `id_ent` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suplidores`
--

INSERT INTO `suplidores` (`id_sup`, `id_ent`, `status`) VALUES
(1, 1, 1),
(2, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysdiagrams`
--

CREATE TABLE `sysdiagrams` (
  `name` varchar(160) NOT NULL,
  `principal_id` int(11) NOT NULL,
  `diagram_id` int(11) NOT NULL,
  `version` int(11) DEFAULT NULL,
  `definition` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `id_telf` int(11) NOT NULL,
  `tip_telf` int(11) DEFAULT NULL,
  `n_telf` char(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`id_telf`, `tip_telf`, `n_telf`) VALUES
(1, 1, '8294383536'),
(2, 1, '8294382527'),
(3, 1, '8095556666'),
(4, 1, '8490039184'),
(5, 1, '809-111-2948'),
(6, 1, '809-555-2000'),
(7, 1, '829-999-1111'),
(8, 1, '829-101-5555'),
(9, 1, '829-333-2222'),
(10, 1, '829-330-0029'),
(11, 1, '829-291-1192'),
(12, 1, '829-291-1177'),
(13, 1, '829-901-1157'),
(14, 1, '829-333-1111'),
(15, 1, '829-438-3536'),
(16, 1, '809-338-2507'),
(17, 1, '8293198512'),
(18, 1, '54928419383'),
(19, 1, '8293581010'),
(20, 1, '8295552000'),
(21, 1, '18294449999'),
(22, 1, '839-209-1092'),
(23, 1, '8293338888'),
(24, 1, '5558882981'),
(25, 1, '24125325321'),
(26, 1, '99999999'),
(27, 1, '8294385931'),
(28, 1, '8094352124'),
(29, 1, '8294444444'),
(30, 1, '8294375718');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tip_articulo`
--

CREATE TABLE `tip_articulo` (
  `id_tip` int(11) NOT NULL,
  `tip_art` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tip_articulo`
--

INSERT INTO `tip_articulo` (`id_tip`, `tip_art`) VALUES
(1, 'UTENSILIO MEDICO'),
(2, 'PRODUCTO MEDICO'),
(3, 'ARTICULO VARIO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tip_documento`
--

CREATE TABLE `tip_documento` (
  `id_tip` int(11) NOT NULL,
  `tip_docu` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tip_documento`
--

INSERT INTO `tip_documento` (`id_tip`, `tip_docu`) VALUES
(1, 'PASAPORTE'),
(2, 'CARNET'),
(3, 'LICENCIA'),
(4, 'RNC'),
(5, 'CEDULA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tip_factura`
--

CREATE TABLE `tip_factura` (
  `id_tip` int(11) NOT NULL,
  `tip_fac` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tip_factura`
--

INSERT INTO `tip_factura` (`id_tip`, `tip_fac`) VALUES
(1, 'A CREDITO'),
(2, 'AL CONTADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tip_servicio`
--

CREATE TABLE `tip_servicio` (
  `id_tip` int(11) NOT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `duracion` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tip_servicio`
--

INSERT INTO `tip_servicio` (`id_tip`, `tipo`, `duracion`) VALUES
(1, 'SENCILLO', '00:30:00'),
(2, 'NIVEL MEDIO', '01:00:00'),
(3, 'COMPLEJO', '01:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tip_telf`
--

CREATE TABLE `tip_telf` (
  `id_tip` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tip_telf`
--

INSERT INTO `tip_telf` (`id_tip`, `tipo`) VALUES
(1, 'PERSONAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `todontograma`
--

CREATE TABLE `todontograma` (
  `codigoOdontograma` int(11) NOT NULL,
  `codigoPaciente` int(11) DEFAULT NULL,
  `estados` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaRegistro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `todontograma`
--

INSERT INTO `todontograma` (`codigoOdontograma`, `codigoPaciente`, `estados`, `descripcion`, `fechaRegistro`) VALUES
(3, 1, 'D84_C3_13-FRACTURA__D84_C3_14-DIENTE DISCROMICO__D84_C3_17-OBTURACION TEMPORAL', 'Nuevo odontograma', '2022-07-19 09:24:53'),
(4, 5, 'D12_C4_3-REMANENTE RADICULAR__D12_C4_12-CLAVIJA__D12_C4_17-OBTURACION TEMPORAL', '', '2022-07-20 03:52:16'),
(5, 5, 'D53_C5_13-FRACTURA__D12_C4_3-REMANENTE RADICULAR__D12_C4_12-CLAVIJA__D12_C4_17-OBTURACION TEMPORAL', '', '2022-07-20 03:52:33'),
(6, 2, 'D48_C4_12-CLAVIJA', '', '2022-08-11 21:51:59'),
(7, 2, 'D46_C5_18-AMALGAMA__D46_C5_15-GEMINACION__D46_C4_6-GIROVERSION__D48_C4_12-CLAVIJA', '', '2022-08-11 21:52:46'),
(8, 2, 'D18_C1_19-RESINA__D18_C5_5-INTRUSION__D18_C5_20-INCRUSTACION__D46_C5_18-AMALGAMA__D46_C5_15-GEMINACION__D46_C4_6-GIROVERSION__D48_C4_12-CLAVIJA', '', '2022-08-11 21:54:21'),
(9, 4, 'D18_C3_13-FRACTURA__D18_C3_16-CARIES__D18_C5_16-CARIES__D18_C5_19-RESINA', '', '2022-08-18 00:39:20'),
(10, 1, 'D18_C1_27-CORONA VEENER__D84_C3_13-FRACTURA__D84_C3_14-DIENTE DISCROMICO__D84_C3_17-OBTURACION TEMPORAL', '', '2022-08-18 01:38:48'),
(11, 5, 'D46_C4_1-DIENTE INTACTO__D53_C5_13-FRACTURA__D12_C4_3-REMANENTE RADICULAR__D12_C4_12-CLAVIJA__D12_C4_17-OBTURACION TEMPORAL', '', '2022-08-19 02:18:10'),
(12, 5, 'D82_C1_1-DIENTE INTACTO__D82_C1_15-GEMINACION__D82_C1_18-AMALGAMA__D46_C4_1-DIENTE INTACTO__D53_C5_13-FRACTURA__D12_C4_3-REMANENTE RADICULAR__D12_C4_12-CLAVIJA__D12_C4_17-OBTURACION TEMPORAL', 'klk ete tigre tiene to', '2022-08-19 02:19:18'),
(13, 4, 'D18_C5_13-FRACTURA__D18_C5_17-OBTURACION TEMPORAL__D18_C4_17-OBTURACION TEMPORAL__D18_C1_16-CARIES__D18_C3_13-FRACTURA__D18_C3_16-CARIES__D18_C5_16-CARIES__D18_C5_19-RESINA', 'Tiene deto\'', '2022-08-24 22:44:28'),
(14, 4, 'D16_C1_18-AMALGAMA__D16_C4_18-AMALGAMA__D16_C4_14-DIENTE DISCROMICO__D18_C5_13-FRACTURA__D18_C5_17-OBTURACION TEMPORAL__D18_C4_17-OBTURACION TEMPORAL__D18_C1_16-CARIES__D18_C3_13-FRACTURA__D18_C3_16-CARIES__D18_C5_16-CARIES__D18_C5_19-RESINA', '', '2022-08-24 22:45:57'),
(15, 4, 'D82_C3_18-AMALGAMA__D82_C3_17-OBTURACION TEMPORAL__D48_C5_5-INTRUSION__D16_C1_18-AMALGAMA__D16_C4_18-AMALGAMA__D16_C4_14-DIENTE DISCROMICO__D18_C5_13-FRACTURA__D18_C5_17-OBTURACION TEMPORAL__D18_C4_17-OBTURACION TEMPORAL__D18_C1_16-CARIES__D18_C3_13-FRACTURA__D18_C3_16-CARIES__D18_C5_16-CARIES__D18_C5_19-RESINA', '', '2022-08-24 22:48:44'),
(16, 4, 'D45_C4_7-MIGRASION__D82_C3_18-AMALGAMA__D82_C3_17-OBTURACION TEMPORAL__D48_C5_5-INTRUSION__D16_C1_18-AMALGAMA__D16_C4_18-AMALGAMA__D16_C4_14-DIENTE DISCROMICO__D18_C5_13-FRACTURA__D18_C5_17-OBTURACION TEMPORAL__D18_C4_17-OBTURACION TEMPORAL__D18_C1_16-CARIES__D18_C3_13-FRACTURA__D18_C3_16-CARIES__D18_C5_16-CARIES__D18_C5_19-RESINA', '', '2022-08-24 22:49:53'),
(17, 5, 'D12_C1_8-MICRODONCIA__D12_C3_19-RESINA__D12_C4_14-DIENTE DISCROMICO__D41_C4_17-OBTURACION TEMPORAL__D82_C1_1-DIENTE INTACTO__D82_C1_15-GEMINACION__D82_C1_18-AMALGAMA__D46_C4_1-DIENTE INTACTO__D53_C5_13-FRACTURA__D12_C4_3-REMANENTE RADICULAR__D12_C4_12-CLAVIJA__D12_C4_17-OBTURACION TEMPORAL', '', '2023-04-26 22:05:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `id_unidad` int(11) NOT NULL,
  `unidad` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidades`
--

INSERT INTO `unidades` (`id_unidad`, `unidad`) VALUES
(1, 'UNIDADES'),
(2, 'LIBRAS'),
(3, 'ONZAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(11) NOT NULL,
  `nom_user` varchar(60) DEFAULT NULL,
  `pass_user` longtext DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_user`, `nom_user`, `pass_user`, `nivel`, `status`) VALUES
(1, 'admin', '$2y$10$rb.mpqIuKpFpGYOevh9SOukha05Pqqre/K4FmcbGYLuK8.YmXBWXq', 1, 1),
(2, 'Jonuel', '$2y$10$IkmEIayrY2EfhyJ/eI.Zl.OweVzk9IUSu7hpE5sk0r/HH8bUVLr5e', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alergias`
--
ALTER TABLE `alergias`
  ADD PRIMARY KEY (`id_alerg`);

--
-- Indices de la tabla `antecedentes`
--
ALTER TABLE `antecedentes`
  ADD PRIMARY KEY (`id_ant`),
  ADD KEY `FK__anteceden__id_pa__6EF57B66` (`id_pac`),
  ADD KEY `FK__anteceden__id_pa__6FE99F9F` (`id_pad`),
  ADD KEY `FK__anteceden__id_al__70DDC3D8` (`id_alerg`);

--
-- Indices de la tabla `apellidos`
--
ALTER TABLE `apellidos`
  ADD PRIMARY KEY (`id_ape`);

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id_art`),
  ADD KEY `tip_art` (`tip_art`),
  ADD KEY `status` (`status`),
  ADD KEY `unidad` (`unidad`);

--
-- Indices de la tabla `calles`
--
ALTER TABLE `calles`
  ADD PRIMARY KEY (`id_calle`),
  ADD KEY `FK__calles__id_mun__2E1BDC42` (`id_mun`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `carrito_usuarios`
--
ALTER TABLE `carrito_usuarios`
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `ck_concepto_carrito` (`concepto`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cit`),
  ADD KEY `FK__citas__id_pac__656C112C` (`id_pac`),
  ADD KEY `FK__citas__id_doc__66603565` (`id_doc`),
  ADD KEY `FK__citas__status__6754599E` (`status`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcli`),
  ADD KEY `id_ent` (`id_ent`),
  ADD KEY `status` (`status`);

--
-- Indices de la tabla `concepto_fact`
--
ALTER TABLE `concepto_fact`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cxc`
--
ALTER TABLE `cxc`
  ADD PRIMARY KEY (`id_cxc`),
  ADD KEY `id_fac` (`id_fac`),
  ADD KEY `status` (`status`);

--
-- Indices de la tabla `cxp`
--
ALTER TABLE `cxp`
  ADD PRIMARY KEY (`id_cxp`),
  ADD KEY `id_fac` (`id_fac`),
  ADD KEY `status` (`status`);

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD PRIMARY KEY (`id_datos`),
  ADD KEY `FK__datos__email__45F365D3` (`email`),
  ADD KEY `FK__datos__dat_dir__46E78A0C` (`dat_dir`),
  ADD KEY `FK__datos__dat_telf__47DBAE45` (`dat_telf`),
  ADD KEY `FK__datos__dat_docu__48CFD27E` (`dat_docu`);

--
-- Indices de la tabla `detalle_fact_com`
--
ALTER TABLE `detalle_fact_com`
  ADD PRIMARY KEY (`id_det`),
  ADD KEY `id_fac` (`id_fac`),
  ADD KEY `id_art` (`id_art`);

--
-- Indices de la tabla `detalle_receta`
--
ALTER TABLE `detalle_receta`
  ADD PRIMARY KEY (`id_det`),
  ADD KEY `FK__detalle_r__id_me__00200768` (`id_med`),
  ADD KEY `FK__detalle_r__id_re__7F2BE32F` (`id_rec`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_dir`),
  ADD KEY `FK__direccion__provi__30F848ED` (`provincia`),
  ADD KEY `FK__direccion__calle__31EC6D26` (`calle`),
  ADD KEY `fk_stat_dir` (`status`);

--
-- Indices de la tabla `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id_doc`),
  ADD KEY `FK__doctor__id_per__60A75C0F` (`id_per`),
  ADD KEY `FK__doctor__doc_esp__619B8048` (`doc_esp`),
  ADD KEY `FK__doctor__status__628FA481` (`status`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_docu`),
  ADD KEY `FK__documento__tip_d__3E52440B` (`tip_docu`);

--
-- Indices de la tabla `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id_email`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empl`),
  ADD KEY `id_per` (`id_per`),
  ADD KEY `cargo` (`cargo`),
  ADD KEY `status` (`status`),
  ADD KEY `fk_id_horario` (`horario`);

--
-- Indices de la tabla `empl_vs_espec`
--
ALTER TABLE `empl_vs_espec`
  ADD PRIMARY KEY (`id_vs`),
  ADD KEY `empl` (`empl`),
  ADD KEY `espec` (`espec`);

--
-- Indices de la tabla `entidad`
--
ALTER TABLE `entidad`
  ADD PRIMARY KEY (`id_ent`),
  ADD KEY `FK__entidad__datos__4F7CD00D` (`datos`),
  ADD KEY `FK__entidad__status__5070F446` (`status`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id_esp`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_status`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pac` (`paciente`),
  ADD KEY `fk_doc` (`doctor`),
  ADD KEY `fk_ser` (`servicio`),
  ADD KEY `fk_status` (`status`),
  ADD KEY `fk_status_pago` (`status_pago`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_fac`),
  ADD KEY `id_pac` (`id_pac`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD PRIMARY KEY (`id_fac`),
  ADD KEY `tip_fac` (`tip_fac`),
  ADD KEY `status` (`status`),
  ADD KEY `ck_concepto` (`concepto`),
  ADD KEY `ck_ent` (`entidad`);

--
-- Indices de la tabla `historial_pac`
--
ALTER TABLE `historial_pac`
  ADD PRIMARY KEY (`id_hist`),
  ADD KEY `FK__historial__id_pa__6A30C649` (`id_pac`),
  ADD KEY `FK__historial__id_ci__6B24EA82` (`id_cit`),
  ADD KEY `FK__historial__id_se__6C190EBB` (`id_ser`);

--
-- Indices de la tabla `hist_cxc`
--
ALTER TABLE `hist_cxc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cxc` (`id_cxc`),
  ADD KEY `met_pag` (`met_pag`),
  ADD KEY `status` (`status`);

--
-- Indices de la tabla `hist_cxp`
--
ALTER TABLE `hist_cxp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cxp` (`id_cxp`),
  ADD KEY `status` (`status`),
  ADD KEY `ck_metpag` (`met_pag`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ck_id_art` (`id_art`);

--
-- Indices de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_lab`),
  ADD KEY `FK__laboratorio__lab__73BA3083` (`lab`),
  ADD KEY `FK__laborator__statu__74AE54BC` (`status`);

--
-- Indices de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id_med`),
  ADD KEY `FK__medicamen__id_la__778AC167` (`id_lab`),
  ADD KEY `FK__medicamen__statu__787EE5A0` (`status`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`id_met`),
  ADD KEY `status` (`status`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id_mun`),
  ADD KEY `FK__municipio__id_pr__2B3F6F97` (`id_prov`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id_niv`);

--
-- Indices de la tabla `nivel_vs_privilegio`
--
ALTER TABLE `nivel_vs_privilegio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__nivel_vs___nivel__114A936A` (`nivel`),
  ADD KEY `FK__nivel_vs_p__priv__123EB7A3` (`priv`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_pac`),
  ADD KEY `FK__paciente__id_per__59FA5E80` (`id_per`),
  ADD KEY `FK__paciente__seg_pa__5AEE82B9` (`seg_pac`),
  ADD KEY `FK__paciente__status__5BE2A6F2` (`status`);

--
-- Indices de la tabla `pac_vs_alerg`
--
ALTER TABLE `pac_vs_alerg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pac` (`id_pac`),
  ADD KEY `id_alerg` (`id_alerg`);

--
-- Indices de la tabla `pac_vs_padec`
--
ALTER TABLE `pac_vs_padec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pac` (`id_pac`),
  ADD KEY `id_pad` (`id_pad`);

--
-- Indices de la tabla `padecimientos`
--
ALTER TABLE `padecimientos`
  ADD PRIMARY KEY (`id_pad`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id_pais`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_per`),
  ADD KEY `FK__persona__ape_per__4BAC3F29` (`ape_per`),
  ADD KEY `FK__persona__datos__4CA06362` (`datos`),
  ADD KEY `fk_stat` (`status`);

--
-- Indices de la tabla `privilegios`
--
ALTER TABLE `privilegios`
  ADD PRIMARY KEY (`id_priv`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id_prov`),
  ADD KEY `FK__provincia__pais__286302EC` (`pais`);

--
-- Indices de la tabla `receta`
--
ALTER TABLE `receta`
  ADD PRIMARY KEY (`id_rec`),
  ADD KEY `FK__receta__id_pac__7B5B524B` (`id_pac`),
  ADD KEY `FK__receta__id_doc__7C4F7684` (`id_doc`);

--
-- Indices de la tabla `seguro`
--
ALTER TABLE `seguro`
  ADD PRIMARY KEY (`id_seg`),
  ADD KEY `FK__seguro__nom_seg__534D60F1` (`nom_seg`),
  ADD KEY `FK__seguro__seg_afi__5441852A` (`seg_afi`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_ser`),
  ADD KEY `FK__servicios__statu__571DF1D5` (`status`),
  ADD KEY `espec_req` (`espec_req`),
  ADD KEY `tip_ser` (`tip_ser`);

--
-- Indices de la tabla `suplidores`
--
ALTER TABLE `suplidores`
  ADD PRIMARY KEY (`id_sup`),
  ADD KEY `id_ent` (`id_ent`),
  ADD KEY `status` (`status`);

--
-- Indices de la tabla `sysdiagrams`
--
ALTER TABLE `sysdiagrams`
  ADD PRIMARY KEY (`diagram_id`),
  ADD UNIQUE KEY `UK_principal_name` (`principal_id`,`name`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`id_telf`),
  ADD KEY `FK__telefono__tip_te__4316F928` (`tip_telf`);

--
-- Indices de la tabla `tip_articulo`
--
ALTER TABLE `tip_articulo`
  ADD PRIMARY KEY (`id_tip`);

--
-- Indices de la tabla `tip_documento`
--
ALTER TABLE `tip_documento`
  ADD PRIMARY KEY (`id_tip`);

--
-- Indices de la tabla `tip_factura`
--
ALTER TABLE `tip_factura`
  ADD PRIMARY KEY (`id_tip`);

--
-- Indices de la tabla `tip_servicio`
--
ALTER TABLE `tip_servicio`
  ADD PRIMARY KEY (`id_tip`);

--
-- Indices de la tabla `tip_telf`
--
ALTER TABLE `tip_telf`
  ADD PRIMARY KEY (`id_tip`);

--
-- Indices de la tabla `todontograma`
--
ALTER TABLE `todontograma`
  ADD PRIMARY KEY (`codigoOdontograma`),
  ADD KEY `fk_pac_odontogr` (`codigoPaciente`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id_unidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `FK__usuarios__nivel__151B244E` (`nivel`),
  ADD KEY `FK__usuarios__status__160F4887` (`status`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id_art` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `concepto_fact`
--
ALTER TABLE `concepto_fact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cxc`
--
ALTER TABLE `cxc`
  MODIFY `id_cxc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cxp`
--
ALTER TABLE `cxp`
  MODIFY `id_cxp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `detalle_fact_com`
--
ALTER TABLE `detalle_fact_com`
  MODIFY `id_det` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de la tabla `empl_vs_espec`
--
ALTER TABLE `empl_vs_espec`
  MODIFY `id_vs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  MODIFY `id_fac` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `hist_cxc`
--
ALTER TABLE `hist_cxc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `hist_cxp`
--
ALTER TABLE `hist_cxp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_met` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pac_vs_alerg`
--
ALTER TABLE `pac_vs_alerg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pac_vs_padec`
--
ALTER TABLE `pac_vs_padec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `suplidores`
--
ALTER TABLE `suplidores`
  MODIFY `id_sup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tip_articulo`
--
ALTER TABLE `tip_articulo`
  MODIFY `id_tip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tip_factura`
--
ALTER TABLE `tip_factura`
  MODIFY `id_tip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tip_servicio`
--
ALTER TABLE `tip_servicio`
  MODIFY `id_tip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `todontograma`
--
ALTER TABLE `todontograma`
  MODIFY `codigoOdontograma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `unidades`
--
ALTER TABLE `unidades`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `antecedentes`
--
ALTER TABLE `antecedentes`
  ADD CONSTRAINT `FK__anteceden__id_al__70DDC3D8` FOREIGN KEY (`id_alerg`) REFERENCES `alergias` (`id_alerg`),
  ADD CONSTRAINT `FK__anteceden__id_pa__6EF57B66` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `FK__anteceden__id_pa__6FE99F9F` FOREIGN KEY (`id_pad`) REFERENCES `padecimientos` (`id_pad`);

--
-- Filtros para la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`tip_art`) REFERENCES `tip_articulo` (`id_tip`),
  ADD CONSTRAINT `articulos_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`),
  ADD CONSTRAINT `articulos_ibfk_3` FOREIGN KEY (`unidad`) REFERENCES `unidades` (`id_unidad`);

--
-- Filtros para la tabla `calles`
--
ALTER TABLE `calles`
  ADD CONSTRAINT `FK__calles__id_mun__2E1BDC42` FOREIGN KEY (`id_mun`) REFERENCES `municipio` (`id_mun`);

--
-- Filtros para la tabla `carrito_usuarios`
--
ALTER TABLE `carrito_usuarios`
  ADD CONSTRAINT `carrito_usuarios_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `articulos` (`id_art`),
  ADD CONSTRAINT `ck_concepto_carrito` FOREIGN KEY (`concepto`) REFERENCES `concepto_fact` (`id`);

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `FK__citas__id_doc__66603565` FOREIGN KEY (`id_doc`) REFERENCES `doctor` (`id_doc`),
  ADD CONSTRAINT `FK__citas__id_pac__656C112C` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `FK__citas__status__6754599E` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_ent`) REFERENCES `entidad` (`id_ent`),
  ADD CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `cxc`
--
ALTER TABLE `cxc`
  ADD CONSTRAINT `cxc_ibfk_1` FOREIGN KEY (`id_fac`) REFERENCES `factura_compra` (`id_fac`),
  ADD CONSTRAINT `cxc_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `cxp`
--
ALTER TABLE `cxp`
  ADD CONSTRAINT `cxp_ibfk_1` FOREIGN KEY (`id_fac`) REFERENCES `factura_compra` (`id_fac`),
  ADD CONSTRAINT `cxp_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `datos`
--
ALTER TABLE `datos`
  ADD CONSTRAINT `FK__datos__dat_dir__46E78A0C` FOREIGN KEY (`dat_dir`) REFERENCES `direccion` (`id_dir`),
  ADD CONSTRAINT `FK__datos__dat_docu__48CFD27E` FOREIGN KEY (`dat_docu`) REFERENCES `documentos` (`id_docu`),
  ADD CONSTRAINT `FK__datos__dat_telf__47DBAE45` FOREIGN KEY (`dat_telf`) REFERENCES `telefono` (`id_telf`),
  ADD CONSTRAINT `FK__datos__email__45F365D3` FOREIGN KEY (`email`) REFERENCES `email` (`id_email`);

--
-- Filtros para la tabla `detalle_fact_com`
--
ALTER TABLE `detalle_fact_com`
  ADD CONSTRAINT `detalle_fact_com_ibfk_1` FOREIGN KEY (`id_fac`) REFERENCES `factura_compra` (`id_fac`),
  ADD CONSTRAINT `detalle_fact_com_ibfk_2` FOREIGN KEY (`id_art`) REFERENCES `articulos` (`id_art`);

--
-- Filtros para la tabla `detalle_receta`
--
ALTER TABLE `detalle_receta`
  ADD CONSTRAINT `FK__detalle_r__id_me__00200768` FOREIGN KEY (`id_med`) REFERENCES `medicamentos` (`id_med`),
  ADD CONSTRAINT `FK__detalle_r__id_re__7F2BE32F` FOREIGN KEY (`id_rec`) REFERENCES `receta` (`id_rec`);

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `FK__direccion__calle__31EC6D26` FOREIGN KEY (`calle`) REFERENCES `calles` (`id_calle`),
  ADD CONSTRAINT `FK__direccion__provi__30F848ED` FOREIGN KEY (`provincia`) REFERENCES `provincia` (`id_prov`),
  ADD CONSTRAINT `fk_stat_dir` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `FK__doctor__doc_esp__619B8048` FOREIGN KEY (`doc_esp`) REFERENCES `especialidad` (`id_esp`),
  ADD CONSTRAINT `FK__doctor__id_per__60A75C0F` FOREIGN KEY (`id_per`) REFERENCES `persona` (`id_per`),
  ADD CONSTRAINT `FK__doctor__status__628FA481` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `FK__documento__tip_d__3E52440B` FOREIGN KEY (`tip_docu`) REFERENCES `tip_documento` (`id_tip`);

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`id_per`) REFERENCES `persona` (`id_per`),
  ADD CONSTRAINT `empleados_ibfk_2` FOREIGN KEY (`cargo`) REFERENCES `cargos` (`id_cargo`),
  ADD CONSTRAINT `empleados_ibfk_3` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`),
  ADD CONSTRAINT `fk_id_horario` FOREIGN KEY (`horario`) REFERENCES `horarios` (`id_horario`);

--
-- Filtros para la tabla `empl_vs_espec`
--
ALTER TABLE `empl_vs_espec`
  ADD CONSTRAINT `empl_vs_espec_ibfk_1` FOREIGN KEY (`empl`) REFERENCES `empleados` (`id_empl`),
  ADD CONSTRAINT `empl_vs_espec_ibfk_2` FOREIGN KEY (`espec`) REFERENCES `especialidad` (`id_esp`);

--
-- Filtros para la tabla `entidad`
--
ALTER TABLE `entidad`
  ADD CONSTRAINT `FK__entidad__datos__4F7CD00D` FOREIGN KEY (`datos`) REFERENCES `datos` (`id_datos`),
  ADD CONSTRAINT `FK__entidad__status__5070F446` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `fk_doc` FOREIGN KEY (`doctor`) REFERENCES `empleados` (`id_empl`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pac` FOREIGN KEY (`paciente`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `fk_ser` FOREIGN KEY (`servicio`) REFERENCES `servicios` (`id_ser`),
  ADD CONSTRAINT `fk_status` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`),
  ADD CONSTRAINT `fk_status_pago` FOREIGN KEY (`status_pago`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `evento` (`id`);

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `ck_concepto` FOREIGN KEY (`concepto`) REFERENCES `concepto_fact` (`id`),
  ADD CONSTRAINT `ck_ent` FOREIGN KEY (`entidad`) REFERENCES `entidad` (`id_ent`),
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`tip_fac`) REFERENCES `tip_factura` (`id_tip`),
  ADD CONSTRAINT `factura_compra_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `historial_pac`
--
ALTER TABLE `historial_pac`
  ADD CONSTRAINT `FK__historial__id_ci__6B24EA82` FOREIGN KEY (`id_cit`) REFERENCES `citas` (`id_cit`),
  ADD CONSTRAINT `FK__historial__id_pa__6A30C649` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `FK__historial__id_se__6C190EBB` FOREIGN KEY (`id_ser`) REFERENCES `servicios` (`id_ser`);

--
-- Filtros para la tabla `hist_cxc`
--
ALTER TABLE `hist_cxc`
  ADD CONSTRAINT `hist_cxc_ibfk_1` FOREIGN KEY (`id_cxc`) REFERENCES `cxc` (`id_cxc`),
  ADD CONSTRAINT `hist_cxc_ibfk_2` FOREIGN KEY (`met_pag`) REFERENCES `metodo_pago` (`id_met`),
  ADD CONSTRAINT `hist_cxc_ibfk_3` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `hist_cxp`
--
ALTER TABLE `hist_cxp`
  ADD CONSTRAINT `ck_metpag` FOREIGN KEY (`met_pag`) REFERENCES `metodo_pago` (`id_met`),
  ADD CONSTRAINT `hist_cxp_ibfk_1` FOREIGN KEY (`id_cxp`) REFERENCES `cxp` (`id_cxp`),
  ADD CONSTRAINT `hist_cxp_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `ck_id_art` FOREIGN KEY (`id_art`) REFERENCES `articulos` (`id_art`);

--
-- Filtros para la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD CONSTRAINT `FK__laborator__statu__74AE54BC` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`),
  ADD CONSTRAINT `FK__laboratorio__lab__73BA3083` FOREIGN KEY (`lab`) REFERENCES `entidad` (`id_ent`);

--
-- Filtros para la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD CONSTRAINT `FK__medicamen__id_la__778AC167` FOREIGN KEY (`id_lab`) REFERENCES `laboratorios` (`id_lab`),
  ADD CONSTRAINT `FK__medicamen__statu__787EE5A0` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD CONSTRAINT `metodo_pago_ibfk_1` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `FK__municipio__id_pr__2B3F6F97` FOREIGN KEY (`id_prov`) REFERENCES `provincia` (`id_prov`);

--
-- Filtros para la tabla `nivel_vs_privilegio`
--
ALTER TABLE `nivel_vs_privilegio`
  ADD CONSTRAINT `FK__nivel_vs___nivel__114A936A` FOREIGN KEY (`nivel`) REFERENCES `niveles` (`id_niv`),
  ADD CONSTRAINT `FK__nivel_vs_p__priv__123EB7A3` FOREIGN KEY (`priv`) REFERENCES `privilegios` (`id_priv`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `FK__paciente__id_per__59FA5E80` FOREIGN KEY (`id_per`) REFERENCES `persona` (`id_per`),
  ADD CONSTRAINT `FK__paciente__seg_pa__5AEE82B9` FOREIGN KEY (`seg_pac`) REFERENCES `seguro` (`id_seg`),
  ADD CONSTRAINT `FK__paciente__status__5BE2A6F2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `pac_vs_alerg`
--
ALTER TABLE `pac_vs_alerg`
  ADD CONSTRAINT `pac_vs_alerg_ibfk_1` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `pac_vs_alerg_ibfk_2` FOREIGN KEY (`id_alerg`) REFERENCES `alergias` (`id_alerg`);

--
-- Filtros para la tabla `pac_vs_padec`
--
ALTER TABLE `pac_vs_padec`
  ADD CONSTRAINT `pac_vs_padec_ibfk_1` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `pac_vs_padec_ibfk_2` FOREIGN KEY (`id_pad`) REFERENCES `padecimientos` (`id_pad`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `FK__persona__ape_per__4BAC3F29` FOREIGN KEY (`ape_per`) REFERENCES `apellidos` (`id_ape`),
  ADD CONSTRAINT `FK__persona__datos__4CA06362` FOREIGN KEY (`datos`) REFERENCES `datos` (`id_datos`),
  ADD CONSTRAINT `fk_stat` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `FK__provincia__pais__286302EC` FOREIGN KEY (`pais`) REFERENCES `pais` (`id_pais`);

--
-- Filtros para la tabla `receta`
--
ALTER TABLE `receta`
  ADD CONSTRAINT `FK__receta__id_doc__7C4F7684` FOREIGN KEY (`id_doc`) REFERENCES `doctor` (`id_doc`),
  ADD CONSTRAINT `FK__receta__id_pac__7B5B524B` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id_pac`);

--
-- Filtros para la tabla `seguro`
--
ALTER TABLE `seguro`
  ADD CONSTRAINT `FK__seguro__nom_seg__534D60F1` FOREIGN KEY (`nom_seg`) REFERENCES `entidad` (`id_ent`),
  ADD CONSTRAINT `FK__seguro__seg_afi__5441852A` FOREIGN KEY (`seg_afi`) REFERENCES `persona` (`id_per`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `FK__servicios__statu__571DF1D5` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`),
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`espec_req`) REFERENCES `especialidad` (`id_esp`),
  ADD CONSTRAINT `servicios_ibfk_2` FOREIGN KEY (`tip_ser`) REFERENCES `tip_servicio` (`id_tip`);

--
-- Filtros para la tabla `suplidores`
--
ALTER TABLE `suplidores`
  ADD CONSTRAINT `suplidores_ibfk_1` FOREIGN KEY (`id_ent`) REFERENCES `entidad` (`id_ent`),
  ADD CONSTRAINT `suplidores_ibfk_2` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `FK__telefono__tip_te__4316F928` FOREIGN KEY (`tip_telf`) REFERENCES `tip_telf` (`id_tip`);

--
-- Filtros para la tabla `todontograma`
--
ALTER TABLE `todontograma`
  ADD CONSTRAINT `fk_pac_odont` FOREIGN KEY (`codigoPaciente`) REFERENCES `paciente` (`id_pac`),
  ADD CONSTRAINT `fk_pac_odontogr` FOREIGN KEY (`codigoPaciente`) REFERENCES `paciente` (`id_pac`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK__usuarios__nivel__151B244E` FOREIGN KEY (`nivel`) REFERENCES `niveles` (`id_niv`),
  ADD CONSTRAINT `FK__usuarios__status__160F4887` FOREIGN KEY (`status`) REFERENCES `estados` (`id_status`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `actualizar_citas_programado` ON SCHEDULE EVERY 1 MINUTE STARTS '2023-04-18 18:03:32' ON COMPLETION NOT PRESERVE ENABLE DO CALL actualizar_citas()$$

CREATE DEFINER=`root`@`localhost` EVENT `CheckProductQuantityEvent` ON SCHEDULE EVERY 1 MINUTE STARTS '2023-12-19 16:05:56' ON COMPLETION NOT PRESERVE ENABLE DO CALL CheckProductQuantity111(1)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
