-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2023 a las 15:32:18
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laravel_peru`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concursos`
--

CREATE TABLE `concursos` (
  `convocatoria` varchar(200) NOT NULL,
  `proceso` varchar(200) DEFAULT NULL,
  `numero` varchar(200) DEFAULT NULL,
  `nombre_o_sigla_de_la_entidad` varchar(200) DEFAULT NULL,
  `fecha_y_hora_de_publicacion` varchar(200) DEFAULT NULL,
  `nomenclatura` varchar(200) DEFAULT NULL,
  `reiniciado_desde` varchar(200) DEFAULT NULL,
  `objeto_de_contratacion` varchar(200) DEFAULT NULL,
  `descripcion_de_objeto` varchar(600) DEFAULT NULL,
  `codigo_snip` varchar(600) DEFAULT NULL,
  `codigo_unico_de_inversion` varchar(200) DEFAULT NULL,
  `valor_estimado` varchar(200) DEFAULT NULL,
  `moneda` varchar(200) DEFAULT NULL,
  `version_seace` varchar(200) DEFAULT NULL,
  `acciones` varchar(600) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `concursos`
--

INSERT INTO `concursos` (`convocatoria`, `proceso`, `numero`, `nombre_o_sigla_de_la_entidad`, `fecha_y_hora_de_publicacion`, `nomenclatura`, `reiniciado_desde`, `objeto_de_contratacion`, `descripcion_de_objeto`, `codigo_snip`, `codigo_unico_de_inversion`, `valor_estimado`, `moneda`, `version_seace`, `acciones`, `created_at`, `updated_at`) VALUES
('882485', '747520', '12', 'MUNICIPALIDAD DISTRITAL DE ALTO DE LA ALIANZA', '01/03/2023 15:36', 'AS-SM-24-2022-OEC-MDAA-1', 'Absolución de consultas y observaciones', 'Bien', 'ADQUISICION DE HOJUELAS DE QUINUA Y AVENA (1,680GR) PARA EL PROGRAMA VASO DE LECHE DE LA MUNICIPALIDAD DISTRITAL ALTO DE LA ALIANZA-TACNA', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_11_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:11:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:11:graCodSnip\'});});', '', '97,529.78', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'tbBuscador:idFormBuscarProceso:dtProcesos:11:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:11:j_idt222\',\'nidProceso\':\'747520\',\'nidConvocatoria\':\'882485\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:56', '2023-03-21 19:23:56'),
('883021', '747987', '13', 'MUNICIPALIDAD DISTRITAL DE HUACHIS', '01/03/2023 09:55', 'AS-SM-43-2022-MDHS/CS-1', 'Registrar otorgamiento de la Buena Pro', 'Obra', 'CREACION DE LOS SERVICIOS DEL LOCAL DE USOS MULTIPLES EN EL BARRI ALTO PERU DEL DISTRITO DE HUACHIS - PROVINCIA DE HUARI - DEPARTAMENTO DE ANCASH', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_12_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:12:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:12:graCodSnip\'});});', '', '355,463.75', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'tbBuscador:idFormBuscarProceso:dtProcesos:12:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:12:j_idt222\',\'nidSistema\':\'3\',\'nidProceso\':\'747987\',\'nidConvocatoria\':\'883021\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:59', '2023-03-21 19:23:59'),
('883116', '748004', '11', 'MUNICIPALIDAD DISTRITAL DE MEGANTONI', '01/03/2023 16:26', 'AS-SM-56-2022-CS-MDM/LC-1', 'Registrar otorgamiento de la Buena Pro', 'Bien', 'ADQUISICION DE MOTOCICLETA LINIEAL PARA EL PROYECTO PARA EL PROYECTO: MEJORAMIENTO DEL SERVICIO DE SEGURIDAD CIUDADANA EN EL DISTRITO DE MEGANTONI, LA CONVENCIÓN, CUSCO', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_10_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:10:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:10:graCodSnip\'});});', '', '229,250.00', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'nidProceso\':\'748004\',\'nidConvocatoria\':\'883116\',\'tbBuscador:idFormBuscarProceso:dtProcesos:10:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:10:j_idt222\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:53', '2023-03-21 19:23:53'),
('883137', '748029', '10', 'MUNICIPALIDAD DISTRITAL DE MEGANTONI', '01/03/2023 17:03', 'AS-SM-56-2022-CS-MDM/LC-1', 'Admisión de propuesta técnica', 'Bien', 'ADQUISICION DE MOTOCICLETA LINIEAL PARA EL PROYECTO PARA EL PROYECTO: MEJORAMIENTO DEL SERVICIO DE SEGURIDAD CIUDADANA EN EL DISTRITO DE MEGANTONI, LA CONVENCIÓN, CUSCO', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_9_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:9:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:9:graCodSnip\'});});', '', '229,250.00', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'tbBuscador:idFormBuscarProceso:dtProcesos:9:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:9:j_idt222\',\'nidProceso\':\'748029\',\'nidConvocatoria\':\'883137\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:49', '2023-03-21 19:23:49'),
('883177', '748062', '9', 'MUNICIPALIDAD DISTRITAL DE PONTO', '01/03/2023 18:31', 'AS-SM-42-2022-MDP-CS-1', 'Admisión de propuesta técnica', 'Obra', 'EJECUCIÓN DE OBRA: \"MEJORAMIENTO DE LA TRANSITABILIDAD PEATONAL, Y VEHICULAR DE LAS CALLES ALFONSO UGARTE Y 14 DE SEPTIEMBRE DE LA LOCALIDAD DE SAN MIGUEL , CENTRO POBLADO DE SAN MIGUEL, DISTRITO DE PONTO - PROVINCIA DE HUARI - DEPARTAMENTO DE ANCASH\"', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_8_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:8:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:8:graCodSnip\'});});', '', '1,260,248.38', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'tbBuscador:idFormBuscarProceso:dtProcesos:8:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:8:j_idt222\',\'nidSistema\':\'3\',\'nidProceso\':\'748062\',\'nidConvocatoria\':\'883177\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:46', '2023-03-21 19:23:46'),
('883257', '748126', '5', 'EMPRESA REGIONAL DE SERVICIO PUBLICO DE ELECTRICIDAD DEL CENTRO SA ELECTROCENTRO S.A.', '02/03/2023 16:27', 'AS-SM-108-2022-ELCTO S.A.-1', 'Admisión de propuesta técnica', 'Bien', 'ADQUISICIÓN DE BOTINES DIELÉCTRICOS PARA LA DOTACIÓN AL PERSONAL OPERATIVO DE ELECTROCENTRO S.A.', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_4_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:4:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:4:graCodSnip\'});});', '', '294,690.00', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'nidProceso\':\'748126\',\'nidConvocatoria\':\'883257\',\'tbBuscador:idFormBuscarProceso:dtProcesos:4:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:4:j_idt222\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:32', '2023-03-21 19:23:32'),
('883261', '748128', '8', 'SOCIEDAD ELECTRICA DEL SUR OESTE S.A.', '02/03/2023 09:42', 'AS-SM-50-2022-SEAL-1', 'Admisión de propuesta técnica', 'Obra', 'Ampliación y rehabilitación de las oficinas de SEAL Sucre para la Gerencia Técnica y de Proyectos', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_7_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:7:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:7:graCodSnip\'});});', '', '482,591.70', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'tbBuscador:idFormBuscarProceso:dtProcesos:7:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:7:j_idt222\',\'nidProceso\':\'748128\',\'nidConvocatoria\':\'883261\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:42', '2023-03-21 19:23:42'),
('883276', '748151', '7', 'GOBIERNO REGIONAL DE UCAYALI SEDE CENTRAL', '02/03/2023 10:01', 'CP-SM-10-2022-GRU-GR-CS-1', 'Absolución de consultas y observaciones', 'Consultoría de Obra', 'SERVICIO DE CONSULTORÍA DE OBRA PARA LA SUPERVISIÓN DE LA EJECUCIÓN DE LA OBRA: ¿MEJORAMIENTO DE SERVICIOS EDUCATIVOS DE LAS ESPECIALIDADES DE CONSTRUCCIÓN CIVIL, MECÁNICA AUTOMOTRIZ, ELECTROTECNÍA INDUSTRIAL, ADMINISTRACIÓN DE RECURSOS FORESTALES Y PRODUCCIÓN AGROPECUARIA DEL INSTITUTO SUPERIOR TECNOLÓGICO PUBLICO SUIZA ¿ UCAYALI¿ - CODIGO SNIP N°378918 y CODIGO UNICO DE INVERSIONES N°2340106.', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_6_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:6:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:6:graCodSnip\'});});', '', '12,045,059.33', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'nidProceso\':\'748151\',\'nidConvocatoria\':\'883276\',\'tbBuscador:idFormBuscarProceso:dtProcesos:6:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:6:j_idt222\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:39', '2023-03-21 19:23:39'),
('883306', '748130', '6', 'CENTRO NACIONAL DE ABASTECIMIENTO DE RECURSOS ESTRATEGICOS EN SALUD', '02/03/2023 10:47', 'AS-Homologacion-SM-33-2022-CENARES/MINSA-1', 'Admisión de propuesta técnica', 'Bien', 'ADQUISICION DE DISPOSITIVOS MEDICOS Y OTROS PRODUCTOS - COMPRA CORPORATIVA SECTORIAL PARA EL ABASTECIMIENTO DE DOCE (12) MESES - (06 ITEMS)', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_5_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:5:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:5:graCodSnip\'});});', '', '4,019,614.18', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'nidProceso\':\'748130\',\'nidConvocatoria\':\'883306\',\'tbBuscador:idFormBuscarProceso:dtProcesos:5:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:5:j_idt222\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:36', '2023-03-21 19:23:36'),
('883324', '748327', '4', 'PETROLEOS DEL PERU S.A.', '03/03/2023 10:23', 'SEL-PROC-34-2022-OTL / PETROPERU-2', '', 'Bien', 'ADQUISICIÓN DEREPUESTOS PARA VISORES NIVEL DE PLANTA TRATAMIENTOS (Normativa aplicable Acuerdo de Directorio N° 039-2021-PP Reglamento de Contrataciones de PETROPERU S.A.)', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_3_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:3:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:3:graCodSnip\'});});', '', 'Reservado', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'tbBuscador:idFormBuscarProceso:dtProcesos:3:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:3:j_idt222\',\'nidSistema\':\'3\',\'nidProceso\':\'748327\',\'nidConvocatoria\':\'883324\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:28', '2023-03-21 19:23:28'),
('883585', '748361', '3', 'SERVICIOS POSTALES DEL PERU S.A.', '03/03/2023 12:12', 'CP-SM-10-2022-SERPOST S.A.-1', 'Admisión de propuesta técnica', 'Servicio', 'SERVICIO DE CONCESIONARIO DE ALIMENTOS', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_2_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:2:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:2:graCodSnip\'});});', '', '652,080.00', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'nidProceso\':\'748361\',\'nidConvocatoria\':\'883585\',\'tbBuscador:idFormBuscarProceso:dtProcesos:2:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:2:j_idt222\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:25', '2023-03-21 19:23:25'),
('883685', '748440', '2', 'MINISTERIO DEL AMBIENTE', '03/03/2023 17:38', 'LP-SM-2-2022-MINAM/OGA-1', 'Admisión de propuesta técnica', 'Bien', 'Adquisición de equipamiento informático para el procesamiento de datos y respaldo de información para el Centro de Datos del Ministerio del Ambiente', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_1_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:1:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:1:graCodSnip\'});});', '', '2,395,535.24', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'nidSistema\':\'3\',\'nidProceso\':\'748440\',\'nidConvocatoria\':\'883685\',\'tbBuscador:idFormBuscarProceso:dtProcesos:1:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:1:j_idt222\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:21', '2023-03-21 19:23:21'),
('883699', '748505', '1', 'PETROLEOS DEL PERU S.A.', '03/03/2023 20:03', 'SEL-PROC-73-2022-OTL / PETROPERU-2', '', 'Servicio', 'SERVICIO DE MONITOREO Y RASTREO POR GPS PARA LAS UNIDADES PERTENECIENTES A LA FLOTA AUTOMOTRIZ LIVIANA DE REFINERÍA TALARA (Normativa aplicable Acuerdo de Directorio N° 039-2021-PP Reglamento de Contrataciones de PETROPERU S.A.)', 'Código SNIP$(function(){PrimeFaces.cw(\'Tooltip\',\'widget_tbBuscador_idFormBuscarProceso_dtProcesos_0_codSnipTT\',{id:\'tbBuscador:idFormBuscarProceso:dtProcesos:0:codSnipTT\',showEffect:\'fade\',hideEffect:\'fade\',target:\'tbBuscador:idFormBuscarProceso:dtProcesos:0:graCodSnip\'});});', '', 'Reservado', 'Soles', '3', 'PrimeFaces.addSubmitParam(\'tbBuscador:idFormBuscarProceso\',{\'tbBuscador:idFormBuscarProceso:dtProcesos:0:j_idt222\':\'tbBuscador:idFormBuscarProceso:dtProcesos:0:j_idt222\',\'nidSistema\':\'3\',\'nidProceso\':\'748505\',\'nidConvocatoria\':\'883699\'}).submit(\'tbBuscador:idFormBuscarProceso\');', '2023-03-21 19:23:18', '2023-03-21 19:23:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_concursos`
--

CREATE TABLE `detalle_concursos` (
  `id` int(11) NOT NULL,
  `key` varchar(500) DEFAULT NULL,
  `value` varchar(500) DEFAULT NULL,
  `convocatoria` varchar(100) DEFAULT NULL,
  `tipo_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2022_10_26_050015_tetx', 1),
(5, '2021_06_14_151701_create_stocks_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_detalle_concurso`
--

CREATE TABLE `tipo_detalle_concurso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_detalle_concurso`
--

INSERT INTO `tipo_detalle_concurso` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Información General', '2022-11-04 16:01:06', '2022-11-04 16:01:06'),
(2, 'Información general de la Entidad', '2022-11-04 16:01:06', '2022-11-04 16:01:06'),
(3, 'Información general del procedimiento', '2022-11-04 16:01:06', '2022-11-04 16:01:06'),
(4, 'Cronograma', '2022-11-04 16:01:06', '2022-11-04 16:01:06'),
(5, 'Entidad Contratante', '2022-11-04 16:01:06', '2022-11-04 16:01:06'),
(6, 'Documentos', '2022-11-04 16:01:06', '2022-11-04 16:01:06'),
(7, 'Opciones del Procedimiento', '2022-11-04 16:01:06', '2022-11-04 16:01:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `level`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Leo', 'leoorderv@gmail.com', NULL, '$2y$10$VkKPqkDsMyjeRCFBGqemJu9bpqbfgkGgrg/92BsNVz.rhSFAjvXTO', 1, NULL, '2022-10-26 10:06:47', '2022-10-26 10:06:47'),
(2, 'ETSTTSETSSET', 'leo_123.rojo@hotmail.com', NULL, '$2y$10$6ZP7VMdctWV.q35r3VRJt.3rK65csPyJN61jw.Gb93O3vxCRqeQOO', 1, NULL, '2022-10-26 10:13:07', '2022-10-26 10:13:07');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `concursos`
--
ALTER TABLE `concursos`
  ADD PRIMARY KEY (`convocatoria`);

--
-- Indices de la tabla `detalle_concursos`
--
ALTER TABLE `detalle_concursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convocatoria` (`convocatoria`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `tipo_detalle_concurso`
--
ALTER TABLE `tipo_detalle_concurso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_concursos`
--
ALTER TABLE `detalle_concursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_detalle_concurso`
--
ALTER TABLE `tipo_detalle_concurso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
