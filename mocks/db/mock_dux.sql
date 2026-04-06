-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla mock_dux.carreras
CREATE TABLE IF NOT EXISTS `carreras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `CodCar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NombCar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Mencion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Ucrs` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Resp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RespGra` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RespProg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codPrograma` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Reglas` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Atras` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Adelante` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Tipo` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Regimen` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Semestres` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Curso_Obligatorio` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PrecioUC` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla mock_dux.carreras: ~4 rows (aproximadamente)
INSERT INTO `carreras` (`id`, `CodCar`, `NombCar`, `Mencion`, `Ucrs`, `Resp`, `RespGra`, `RespProg`, `Status`, `codPrograma`, `Reglas`, `Atras`, `Adelante`, `Tipo`, `Regimen`, `Semestres`, `Curso_Obligatorio`, `PrecioUC`) VALUES
	(1, '011', 'DERECHO', NULL, '18', NULL, NULL, NULL, 'I', '9', '0', NULL, NULL, 'LICoING', 'Anual', '5', NULL, NULL),
	(2, '010', 'INFORMATICA', NULL, '19', NULL, NULL, NULL, 'A', '10', '0', NULL, NULL, 'ING', 'Semestral', '10', NULL, NULL),
	(3, '012', 'VETERINARIA', NULL, '20', NULL, NULL, NULL, 'A', '9', '0', NULL, NULL, 'LICoING', 'Anual', '5', NULL, NULL),
	(4, '013', 'PETROLEO', NULL, '21', NULL, NULL, NULL, 'A', '10', '0', NULL, NULL, 'ING', 'Semestral', '10', NULL, NULL);

-- Volcando estructura para tabla mock_dux.carreras_est
CREATE TABLE IF NOT EXISTS `carreras_est` (
  `ConexEst` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CodCar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sede` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1',
  `Turno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'D'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla mock_dux.carreras_est: ~10 rows (aproximadamente)
INSERT INTO `carreras_est` (`ConexEst`, `Status`, `CodCar`, `Sede`, `Turno`) VALUES
	('24823972', 'I', '011', '1', 'D'),
	('10873836', 'A', '011', '2', 'D'),
	('27510231', 'A', '012', '1', 'D'),
	('12345678', 'A', '012', '2', 'D'),
	('87654321', 'A', '010', '1', 'D'),
	('10873836', 'A', '010', '2', 'D'),
	('10873836', 'I', '013', '1', 'D'),
	('741852963', 'A', '013', '2', 'D'),
	('25252525', 'A', '011', '1', 'D'),
	('10101010', 'A', '011', '2', 'D');

-- Volcando estructura para tabla mock_dux.estudiantes
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `Cedula` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombres` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nacionalidad` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sexo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sede` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'BARINAS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla simulada de estudiantes de dux';

-- Volcando datos para la tabla mock_dux.estudiantes: ~8 rows (aproximadamente)
INSERT INTO `estudiantes` (`Cedula`, `nombres`, `apellidos`, `nacionalidad`, `Sexo`, `Sede`) VALUES
	('24823972', 'YORDHIS', 'OSUNA', 'V', 'M', 'BARINAS'),
	('27510231', 'DEBORA', 'DE OSUNA', 'V', 'F', 'BARINAS'),
	('10873836', 'YENNY', 'AGUIRRE', 'V', 'F', 'BARINAS'),
	('12345678', 'PEDRO', 'PEREZ', 'V', 'M', 'BARINAS'),
	('87654321', 'JUAN', 'JIMENEZ', 'V', 'M', 'BARINAS'),
	('741852963', 'Fabian Jose', 'Benite', 'V', 'M', 'BARINAS'),
	('25252525', 'Pablo Jose', 'Benite', 'V', 'M', 'BARINAS'),
	('10101010', 'Angi Tahyn', 'Benite', 'V', 'F', 'BARINAS');

-- Volcando estructura para tabla mock_dux.programas
CREATE TABLE IF NOT EXISTS `programas` (
  `codPrograma` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jefe` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rutaImagen` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsables` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla mock_dux.programas: ~2 rows (aproximadamente)
INSERT INTO `programas` (`codPrograma`, `nombre`, `jefe`, `cargo`, `rutaImagen`, `responsables`) VALUES
	('9', 'VPDS', 'PEPITO 1', 'JEFE', NULL, 'CASESORA'),
	('10', 'CIENCIAS SOCIALES Y ECONOMICAS', 'PEPITO 2', 'JEFE', NULL, 'CASESORA');

-- Volcando estructura para tabla mock_dux.sede
CREATE TABLE IF NOT EXISTS `sede` (
  `CodSede` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sede` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Zona` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codVice` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Municipio` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SubSede` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TipoSede` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oestado` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oparroquia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ociudad` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osector` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Est_Dist` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Arse` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla mock_dux.sede: ~2 rows (aproximadamente)
INSERT INTO `sede` (`CodSede`, `Sede`, `Zona`, `codVice`, `Municipio`, `Tipo`, `SubSede`, `TipoSede`, `oestado`, `oparroquia`, `ociudad`, `osector`, `Est_Dist`, `Arse`) VALUES
	('1', 'Barinas - Don samuel', 'BARINAS', '1', 'Barinas', 'Ampliación', 'NoEs', 'LICoING', 'BARINAS', 'ALTO BARINAS', 'BARINAS', 'DON SAMUEL', '0', 'ARSE_BARINAS'),
	('2', 'PRINCIPAL', 'BARINAS - PUNTO FRESCO', '2', 'BARINAS', 'PRINCIPAL', 'NoEs', 'LICoING', 'BARINAS', 'ALTO BARINAS', 'BARINAS', '23 DE ENERO', '0', 'ARSE_BARINAS');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
