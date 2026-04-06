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

-- Volcando estructura para tabla comedor_empleados_mock.rrhh_personal
CREATE TABLE IF NOT EXISTS `rrhh_personal` (
  `per_cedula` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_status` tinyint NOT NULL,
  `per_sexo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`per_cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla comedor_empleados_mock.rrhh_personal: ~3 rows (aproximadamente)
INSERT INTO `rrhh_personal` (`per_cedula`, `per_status`, `per_sexo`) VALUES
	('10222333', 1, '1'),
	('10222334', 0, '2'),
	('10222335', 1, '1');

-- Volcando estructura para tabla comedor_empleados_mock.rrhh_vista_personal
CREATE TABLE IF NOT EXISTS `rrhh_vista_personal` (
  `per_cedula` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_nombres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_apellidos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vicn_descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Nombre_Completo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`per_cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla comedor_empleados_mock.rrhh_vista_personal: ~3 rows (aproximadamente)
INSERT INTO `rrhh_vista_personal` (`per_cedula`, `per_nombres`, `per_apellidos`, `vicn_descripcion`, `Nombre_Completo`) VALUES
	('10222333', 'Juan Carlos', 'Pérez', 'Sede Central', 'Av. Principal, Edif. Administrativo'),
	('10222334', 'María Fernanda', 'López', 'Sede Norte', 'Calle Secundaria, Piso 2'),
	('10222335', 'Luis Alberto', 'González', 'Sede Sur', 'Calle Tercera, Oficina 11');

-- Volcando estructura para tabla comedor_empleados_mock.tools_sexo
CREATE TABLE IF NOT EXISTS `tools_sexo` (
  `sex_codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex_descripcion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`sex_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla comedor_empleados_mock.tools_sexo: ~2 rows (aproximadamente)
INSERT INTO `tools_sexo` (`sex_codigo`, `sex_descripcion`) VALUES
	('1', 'MASCULINO'),
	('2', 'FEMENINO');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
