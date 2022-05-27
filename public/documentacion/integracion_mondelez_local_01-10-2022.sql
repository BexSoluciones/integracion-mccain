/*
SQLyog Trial v13.1.8 (64 bit)
MySQL - 10.4.19-MariaDB : Database - integracion_mondelez
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`integracion_mondelez` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `integracion_mondelez`;

/*Table structure for table `tbl_comando` */

DROP TABLE IF EXISTS `tbl_comando`;

CREATE TABLE `tbl_comando` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_comando` */

insert  into `tbl_comando`(`codigo`,`schedule`,`created_at`,`updated_at`) values 
(1,'integracion:verificar-tablas',NULL,NULL),
(2,'integracion:guardar-informacion',NULL,NULL),
(3,'integracion:generar-planos',NULL,NULL),
(4,'integracion:enviar-planos',NULL,NULL);

/*Table structure for table `tbl_conexion` */

DROP TABLE IF EXISTS `tbl_conexion`;

CREATE TABLE `tbl_conexion` (
  `codigo` int(11) NOT NULL,
  `url` text DEFAULT NULL,
  `conexion` varchar(150) DEFAULT NULL,
  `cia` int(11) DEFAULT NULL,
  `proveedor` varchar(150) DEFAULT NULL,
  `usuario` varchar(45) DEFAULT NULL,
  `clave` varchar(45) DEFAULT NULL,
  `us_priv` varchar(45) DEFAULT NULL,
  `pass_priv` varchar(45) DEFAULT NULL,
  `consulta` varchar(45) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_conexion` */

insert  into `tbl_conexion`(`codigo`,`url`,`conexion`,`cia`,`proveedor`,`usuario`,`clave`,`us_priv`,`pass_priv`,`consulta`,`estado`,`created_at`,`updated_at`) values 
(1,'http://wspandapruebas.siesacloud.com:8043/WSUNOEE/WSUNOEE.asmx?WSDL','UnoEE_Pandapan_Pruebas',1,'PIMOVIL','unoee importaciones','4ntaresstar',NULL,NULL,'SIESA',1,NULL,'2021-12-27 12:54:28'),
(2,'http://wspandapanreal.siesacloud.com:8080/WSUNOEE/WSUNOEE.asmx?WSDL','UnoEE_Pandapan_Real',2,'PIMOVIL','unoee importaciones','4ntaresstar',NULL,NULL,'SIESA',0,NULL,'2021-12-27 12:54:22');

/*Table structure for table `tbl_consulta` */

DROP TABLE IF EXISTS `tbl_consulta`;

CREATE TABLE `tbl_consulta` (
  `codigo` int(11) NOT NULL,
  `id_plano` int(11) DEFAULT NULL,
  `tabla_destino` varchar(450) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `sentencia` text DEFAULT NULL,
  `sentencia_alterna` text DEFAULT NULL,
  `criterio_sel` tinyint(4) DEFAULT 1,
  `criterio` varchar(15) DEFAULT NULL,
  `tipo_doc` varchar(15) DEFAULT NULL,
  `consecutivo` int(11) DEFAULT NULL,
  `campo_consecutivo` varchar(45) DEFAULT NULL,
  `consecutivo_b` int(11) DEFAULT NULL,
  `campo_consecutivo_b` varchar(45) DEFAULT NULL,
  `top` int(11) DEFAULT NULL,
  `top_tabla` int(11) DEFAULT NULL,
  `desde_items` int(15) DEFAULT NULL,
  `hasta_items` int(15) DEFAULT NULL,
  `id_plan` varchar(45) DEFAULT NULL,
  `truncate` tinyint(4) DEFAULT 0,
  `drop_table` tinyint(4) DEFAULT 0,
  `orderBy` varchar(45) DEFAULT NULL,
  `orderType` varchar(45) DEFAULT NULL,
  `buscar_campo` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_plano_idx` (`id_plano`),
  CONSTRAINT `id_plano` FOREIGN KEY (`id_plano`) REFERENCES `tbl_plano` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_consulta` */

insert  into `tbl_consulta`(`codigo`,`id_plano`,`tabla_destino`,`estado`,`descripcion`,`prioridad`,`sentencia`,`sentencia_alterna`,`criterio_sel`,`criterio`,`tipo_doc`,`consecutivo`,`campo_consecutivo`,`consecutivo_b`,`campo_consecutivo_b`,`top`,`top_tabla`,`desde_items`,`hasta_items`,`id_plan`,`truncate`,`drop_table`,`orderBy`,`orderType`,`buscar_campo`,`created_at`,`updated_at`) values 
(1,1,'tbl_ws_cliente_pedido',1,'Consulta informacion de clientes facturas',0,'SET QUOTED_IDENTIFIER OFF; \r\nSELECT TOP @top \r\n	ISNULL(f200_razon_social,\"\") as nombreCliente\r\n	,ISNULL(IIF(f200_id_tipo_ident = \"C\",\"0\",\"1\"),\"\") as tipo_cliente\r\n	,ISNULL(\"\",\"\") as nombre_1\r\n	,ISNULL(\"\",\"\") as nombre_2\r\n	,ISNULL(\"\",\"\") as apellido_1\r\n	,ISNULL(\"\",\"\") as apellido_2\r\n	,ISNULL(f200_nombre_est,\"\") as nom_comercial\r\n	,ISNULL(85,\"\") as cod_clase_cliente\r\n	,ISNULL(f200_nit,\"\") as nit\r\n	,ISNULL(IIF(f200_id_tipo_ident = \"C\",\"1\",\"0\"),\"\") as tipo_identificacion\r\n	,case when len(ISNULL(f015_celular,\"0\")) &lt; 10 then f015_telefono else f015_celular end as telefono\r\n	,ISNULL(f015_celular,\"\") as celular\r\n	,ISNULL(f015_email,\"\") as mail\r\n	,ISNULL(f015_id_depto,\"\") as departamento\r\n	,ISNULL(f015_id_depto+f015_id_ciudad,\"\") as ciudad\r\n	,ISNULL(\"A\",\"\") as estado\r\n	,ISNULL(f200_razon_social,\"\") as razon_social\r\n	,ISNULL(f015_direccion1,\"\") as dir\r\n	,ISNULL(3,\"\") as nivel\r\n	,ISNULL(f013_descripcion,\"\") as localidad\r\n	,ISNULL(f015_id_barrio,\"\") as barrio\r\n	,ISNULL(f201_id_sucursal,\"\") as sucursal\r\n	,1 as TipoRegimen\r\n	,ISNULL(f350_consec_docto,\"\") as consecutivo_factura\r\n\r\nFROM t350_co_docto_contable \r\n	LEFT JOIN t461_cm_docto_factura_venta ON t350_co_docto_contable.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and t350_co_docto_contable.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia \r\n	LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia \r\n	LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) \r\n	LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia \r\n	LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia\r\n	LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      \r\n	LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid \r\n	LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor \r\n	LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto\r\n	LEFT JOIN t013_mm_ciudades on f013_id_pais = f015_id_pais and f013_id_depto = f015_id_depto and f013_id = f015_id_ciudad\r\n\r\nWHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" \r\n	and t350_co_docto_contable.f350_id_tipo_docto = \"@tipoDoc\" \r\n	and t350_co_docto_contable.f350_consec_docto >= @conseDoc \r\n	and t350_co_docto_contable.f350_id_cia = @Cia \r\n	and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems \r\n	and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n	and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio \r\n	and f201_id_cia = @Cia\r\n	and f015_id_cia = @Cia \r\n	and f200_ind_estado = @idEstado\r\n	and f201_ind_estado_activo = @idEstadoActivo\r\n	and f201_id_cond_pago is not null\r\n	and t350_co_docto_contable.f350_id_sucursal is not null\r\n\r\nGROUP BY f350_consec_docto,f015_telefono,f201_id_sucursal,f015_id_barrio,f013_descripcion,f015_direccion1,f015_id_ciudad,f015_id_depto,f015_email,f015_celular,f200_nombre_est,f200_id_tipo_ident,f200_razon_social,f200_nit, f201_id_sucursal\r\nORDER BY f350_consec_docto ASC \r\nSET QUOTED_IDENTIFIER ON;',NULL,0,'41,46','FTM',403812,'consecutivo_factura',0,'',1000,1,18000,20000,'2',0,0,'codigo','asc','nit',NULL,'2022-01-11 03:10:51'),
(2,2,'tbl_ws_cabecera_pedido',1,'Consulta informacion total facturado',0,'SET QUOTED_IDENTIFIER OFF; \r\nSELECT TOP @top \r\n	ISNULL(f200_nit,\"\") as codigoCliente\r\n	,ISNULL(cons1.f350_fecha,\"\") as fechaHoraPedido\r\n	,ISNULL(cons1.f350_consec_docto,\"\") as numeroPedido\r\n	,(\r\n		SELECT TOP @top \r\n        	SUM(f470_vlr_bruto)\r\n\r\n		FROM t350_co_docto_contable AS cons2\r\n			LEFT JOIN t461_cm_docto_factura_venta ON cons2.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and cons2.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia \r\n			LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia \r\n			LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) \r\n			LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia \r\n			LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia\r\n			LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      \r\n			LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid \r\n			LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor \r\n			LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto\r\n\r\n		WHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" \r\n			and cons2.f350_consec_docto = cons1.f350_consec_docto\r\n	        and cons2.f350_id_tipo_docto = \"@tipoDoc\" \r\n			and cons2.f350_consec_docto &gt; @conseDoc \r\n			and cons2.f350_id_cia = @Cia \r\n			and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems \r\n			and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n			and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio \r\n			and	f201_id_cia = @Cia\r\n			and f015_id_cia = @Cia \r\n			and f200_ind_estado = @idEstado\r\n			and f201_ind_estado_activo = @idEstadoActivo\r\n			and f201_id_cond_pago is not null\r\n			and cons2.f350_id_sucursal is not null\r\n	) as valorBruto\r\n	,(\r\n		SELECT TOP @top \r\n        	SUM(f470_vlr_imp)\r\n\r\n		FROM t350_co_docto_contable AS cons3\r\n			LEFT JOIN t461_cm_docto_factura_venta ON cons3.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and cons3.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia \r\n			LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia \r\n			LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) \r\n			LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia \r\n			LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia\r\n			LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      \r\n			LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid \r\n			LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor \r\n			LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto\r\n\r\n		WHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" \r\n			and cons3.f350_consec_docto = cons1.f350_consec_docto\r\n	        and cons3.f350_id_tipo_docto = \"@tipoDoc\" \r\n			and cons3.f350_consec_docto &gt; @conseDoc \r\n			and cons3.f350_id_cia = @Cia \r\n			and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems \r\n			and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n			and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio \r\n			and	f201_id_cia = @Cia\r\n			and f015_id_cia = @Cia \r\n			and f200_ind_estado = @idEstado\r\n			and f201_ind_estado_activo = @idEstadoActivo\r\n			and f201_id_cond_pago is not null\r\n			and cons3.f350_id_sucursal is not null\r\n	) as valorIva\r\n	,(\r\n		SELECT TOP @top \r\n        	\r\n        	(SUM(f470_vlr_imp) + SUM(f470_vlr_bruto))\r\n\r\n		FROM t350_co_docto_contable AS cons4\r\n			LEFT JOIN t461_cm_docto_factura_venta ON cons4.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and cons4.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia \r\n			LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia \r\n			LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) \r\n			LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia \r\n			LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia\r\n			LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      \r\n			LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid \r\n			LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor \r\n			LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto\r\n\r\n		WHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" \r\n			and cons4.f350_consec_docto = cons1.f350_consec_docto\r\n	        and cons4.f350_id_tipo_docto = \"@tipoDoc\" \r\n			and cons4.f350_consec_docto &gt; @conseDoc \r\n			and cons4.f350_id_cia = @Cia \r\n			and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems \r\n			and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n			and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio \r\n			and	f201_id_cia = @Cia\r\n			and f015_id_cia = @Cia \r\n			and f200_ind_estado = @idEstado\r\n			and f201_ind_estado_activo = @idEstadoActivo\r\n			and f201_id_cond_pago is not null\r\n			and cons4.f350_id_sucursal is not null\r\n	) as valorTotal\r\n	,(\r\n		SELECT TOP 1 \r\n        	\r\n        	ISNULL(f5790_id_vendedor,\"\")\r\n\r\n		FROM t350_co_docto_contable AS consVend\r\n			LEFT JOIN t461_cm_docto_factura_venta ON consVend.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and consVend.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia \r\n			LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia \r\n			LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) \r\n			LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia \r\n			LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia\r\n			LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      \r\n			LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid \r\n			LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor \r\n			LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto\r\n			LEFT JOIN t5791_sm_ruta_frecuencia on f5791_rowid_tercero = f200_rowid and f200_id_cia = @Cia\r\n			LEFT JOIN t5790_sm_ruta on f5791_rowid_ruta = f5790_rowid and f5790_id_cia = @Cia\r\n\r\n		WHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" \r\n			and consVend.f350_consec_docto = cons1.f350_consec_docto\r\n	        and consVend.f350_id_tipo_docto = \"@tipoDoc\" \r\n			and consVend.f350_consec_docto &gt; @conseDoc \r\n			and consVend.f350_id_cia = @Cia \r\n			and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems \r\n			and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n			and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio \r\n			and	f201_id_cia = @Cia\r\n			and f015_id_cia = @Cia \r\n			and f200_ind_estado = @idEstado\r\n			and f201_ind_estado_activo = @idEstadoActivo\r\n			and f201_id_cond_pago is not null\r\n			and consVend.f350_id_sucursal is not null\r\n\r\n		ORDER BY consVend.f350_consec_docto ASC \r\n\r\n	) as codigoVendedor\r\n\r\nFROM t350_co_docto_contable AS cons1\r\n	LEFT JOIN t461_cm_docto_factura_venta ON cons1.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and cons1.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia \r\n	LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia \r\n	LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) \r\n	LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia \r\n	LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia\r\n	LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      \r\n	LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid \r\n	LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor \r\n	LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto\r\n\r\nWHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" \r\n	and cons1.f350_id_tipo_docto = \"@tipoDoc\" \r\n	and cons1.f350_consec_docto &gt; @conseDoc \r\n	and cons1.f350_id_cia = @Cia \r\n	and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems \r\n	and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n	and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio \r\n	and	f201_id_cia = @Cia\r\n	and f015_id_cia = @Cia \r\n	and f200_ind_estado = @idEstado\r\n	and f201_ind_estado_activo = @idEstadoActivo\r\n	and f201_id_cond_pago is not null\r\n	and cons1.f350_id_sucursal is not null\r\n\r\nGROUP BY cons1.f350_fecha,cons1.f350_consec_docto,f200_nit\r\nORDER BY cons1.f350_consec_docto ASC \r\nSET QUOTED_IDENTIFIER ON;',NULL,0,'41,46','FTM',403812,'numeroPedido',0,'',1000,1,18000,20000,'2',0,0,'codigoCliente','asc','codigoCliente',NULL,'2022-01-11 03:04:24'),
(3,3,'tbl_ws_detalle_pedido',1,'Consulta informacion detalle facturas',0,'SET QUOTED_IDENTIFIER OFF; \r\nSELECT TOP @top 	\r\n	ISNULL(f200_nit,\"\") as codigoCliente	\r\n	,ISNULL(f350_consec_docto,\"\") as numeroPedido	\r\n	,ISNULL(f124_referencia,\"\") as codigoArticulo	\r\n	,ISNULL(0,\"\") as Displays	\r\n	,(f470_cant_base*f470_factor) as unidadesSueltas	\r\n	,(f470_vlr_neto/iif((f470_cant_base*f470_factor)=0,1,(f470_cant_base*f470_factor))) as precioUnitario	\r\n	,ISNULL(f470_vlr_bruto,\"\") as valorBrutoVenta	\r\n	,ISNULL(f470_vlr_imp,\"\") as valorIva	\r\n	,(f470_vlr_bruto+f470_vlr_imp) as valorFinal \r\n	,ISNULL(f120_rowid,\"\") as consecutivo_item\r\n\r\nFROM t350_co_docto_contable 	\r\n	LEFT JOIN t461_cm_docto_factura_venta ON t350_co_docto_contable.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and t350_co_docto_contable.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia 	\r\n	LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia 	\r\n	LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) 	\r\n	LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia 	\r\n	LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia	\r\n	LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia \r\n        LEFT JOIN t124_mc_items_referencias ON f124_rowid_item = f120_rowid     	\r\n	LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid 	\r\n	LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor 	\r\n	LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto \r\n\r\nWHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" 	\r\n	and t350_co_docto_contable.f350_id_tipo_docto = \"@tipoDoc\" 	\r\n	and t350_co_docto_contable.f350_consec_docto >= @conseDoc \r\n	and t120_mc_items.f120_rowid &gt; @conseItem \r\n	and t350_co_docto_contable.f350_id_cia = @Cia 	\r\n	and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems 	\r\n	and LTRIM(RTRIM(f106_id_plan)) = @idPlan \r\n        and t124_mc_items_referencias.f124_ts >= \"2022-01-01\"	\r\n	and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio 	\r\n	and	f201_id_cia = @Cia	\r\n	and f015_id_cia = @Cia 	\r\n	and f200_ind_estado = @idEstado	\r\n	and f201_ind_estado_activo = @idEstadoActivo	\r\n	and f201_id_cond_pago is not null	\r\n	and t350_co_docto_contable.f350_id_sucursal is not null \r\n\r\nGROUP BY f120_rowid,f124_referencia,f121_rowid_item,f470_cant_base,f470_factor,f470_vlr_neto,f470_vlr_imp,f470_vlr_bruto,f350_consec_docto,f200_nit \r\nORDER BY f350_consec_docto,f121_rowid_item ASC \r\nSET QUOTED_IDENTIFIER ON;','SET QUOTED_IDENTIFIER OFF; \r\nSELECT TOP @top 	\r\n	ISNULL(f200_nit,\"\") as codigoCliente	\r\n	,ISNULL(f350_consec_docto,\"\") as numeroPedido	\r\n	,ISNULL(f124_referencia,\"\") as codigoArticulo	\r\n	,ISNULL(0,\"\") as Displays	\r\n	,(f470_cant_base*f470_factor) as unidadesSueltas	\r\n	,(f470_vlr_neto/iif((f470_cant_base*f470_factor)=0,1,(f470_cant_base*f470_factor))) as precioUnitario	\r\n	,ISNULL(f470_vlr_bruto,\"\") as valorBrutoVenta	\r\n	,ISNULL(f470_vlr_imp,\"\") as valorIva	\r\n	,(f470_vlr_bruto+f470_vlr_imp) as valorFinal \r\n	,ISNULL(f120_rowid,\"\") as consecutivo_item\r\n\r\nFROM t350_co_docto_contable 	\r\n	LEFT JOIN t461_cm_docto_factura_venta ON t350_co_docto_contable.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and t350_co_docto_contable.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia 	\r\n	LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia 	\r\n	LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) 	\r\n	LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia 	\r\n	LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia	\r\n	LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia  \r\n        LEFT JOIN t124_mc_items_referencias ON f124_rowid_item = f120_rowid    	\r\n	LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid 	\r\n	LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor 	\r\n	LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto \r\n\r\nWHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" 	\r\n	and t350_co_docto_contable.f350_id_tipo_docto = \"@tipoDoc\" 	\r\n	and t350_co_docto_contable.f350_consec_docto &gt; @conseDoc \r\n	and t350_co_docto_contable.f350_id_cia = @Cia 	\r\n	and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems 	\r\n	and LTRIM(RTRIM(f106_id_plan)) = @idPlan 	\r\n	and t124_mc_items_referencias.f124_ts >= \"2022-01-01\"\r\n	and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio 	\r\n	and	f201_id_cia = @Cia	\r\n	and f015_id_cia = @Cia 	\r\n	and f200_ind_estado = @idEstado	\r\n	and f201_ind_estado_activo = @idEstadoActivo	\r\n	and f201_id_cond_pago is not null	\r\n	and t350_co_docto_contable.f350_id_sucursal is not null \r\n\r\nGROUP BY f120_rowid,f124_referencia,f121_rowid_item,f470_cant_base,f470_factor,f470_vlr_neto,f470_vlr_imp,f470_vlr_bruto,f350_consec_docto,f200_nit \r\nORDER BY f350_consec_docto,f121_rowid_item ASC \r\nSET QUOTED_IDENTIFIER ON;',0,'41,46','FTM',403812,'numeroPedido',10722,'consecutivo_item',1000,1,18000,20000,'2',0,0,'codigoCliente','asc','codigoCliente',NULL,'2022-01-11 03:04:33'),
(4,4,'tbl_ws_rutero_pedido',1,'Consulta informacion de ruteros facturas',0,'SET QUOTED_IDENTIFIER OFF; \r\nSELECT TOP @top 	\r\n	ISNULL(f200_nit,\"\") as codigoCliente	\r\n	,ISNULL(f5791_ind_frecuencia,\"\") as frecuencia_visita	\r\n	,ISNULL(f350_fecha,\"\") as dia_visita	\r\n	,ISNULL(f5791_orden,\"\") as orden_visita	\r\n	,ISNULL(f5790_id_vendedor,\"\") as cod_vendedor \r\n	,ISNULL(f350_consec_docto,\"\") as consecutivo_factura\r\n\r\nFROM t350_co_docto_contable 	\r\n	LEFT JOIN t461_cm_docto_factura_venta ON t350_co_docto_contable.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and t350_co_docto_contable.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia 	\r\n	LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia 	\r\n	LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia) 	\r\n	LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia 	\r\n	LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia	\r\n	LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia      	\r\n	LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid 	\r\n	LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor 	\r\n	LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto	\r\n	LEFT JOIN t5791_sm_ruta_frecuencia on f5791_rowid_tercero = f200_rowid and f200_id_cia = @Cia	\r\n	LEFT JOIN t5790_sm_ruta on f5791_rowid_ruta = f5790_rowid and f5790_id_cia = @Cia\r\n\r\nWHERE RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; \"\" 	\r\n	and t350_co_docto_contable.f350_id_tipo_docto = \"@tipoDoc\" 	\r\n	and t350_co_docto_contable.f350_consec_docto >= @conseDoc \r\n	and t350_co_docto_contable.f350_id_cia = @Cia 	\r\n	and RIGHT(\"\"+CAST(f120_id AS VARCHAR(20)),20) >= @desdeItems 	\r\n	and LTRIM(RTRIM(f106_id_plan)) = @idPlan 	\r\n	and LTRIM(RTRIM(f125_id_criterio_mayor)) = @idCriterio 	\r\n	and f201_id_cia = @Cia	\r\n	and f015_id_cia = @Cia 	\r\n	and f200_ind_estado = @idEstado	\r\n	and f201_ind_estado_activo = @idEstadoActivo	\r\n	and f201_id_cond_pago is not null	\r\n	and t350_co_docto_contable.f350_id_sucursal is not null \r\n	\r\nGROUP BY f350_consec_docto,f5790_id_vendedor,f5791_ind_frecuencia,f5791_orden,f350_fecha,f200_nit \r\nORDER BY f350_consec_docto ASC \r\nSET QUOTED_IDENTIFIER ON;',NULL,0,'41,46','FTM',403812,'consecutivo_factura',0,'',1000,1,18000,20000,'2',0,0,'codigoCliente','asc','codigoCliente',NULL,'2022-01-11 03:41:57');

/*Table structure for table `tbl_consulta_condicion` */

DROP TABLE IF EXISTS `tbl_consulta_condicion`;

CREATE TABLE `tbl_consulta_condicion` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_consulta` int(11) DEFAULT NULL,
  `condicion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_consulta_condicion` */

insert  into `tbl_consulta_condicion`(`codigo`,`id_consulta`,`condicion`) values 
(1,1,'nit'),
(7,2,'numeroPedido'),
(10,3,'numeroPedido,codigoArticulo'),
(11,4,'codigoCliente,consecutivo_factura,cod_vendedor');

/*Table structure for table `tbl_correo` */

DROP TABLE IF EXISTS `tbl_correo`;

CREATE TABLE `tbl_correo` (
  `codigo` int(11) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_correo` */

insert  into `tbl_correo`(`codigo`,`correo`,`estado`,`created_at`,`updated_at`) values 
(1,'jose.marin@bexsoluciones.com',1,NULL,NULL),
(2,'jwmg10@hotmail.com',1,NULL,NULL),
(3,'jander.torres@bexsoluciones.com',0,NULL,NULL),
(4,'diego.paniagua@bexsoluciones.com',0,NULL,NULL),
(5,'diego.marin@bexsoluciones.com',0,NULL,NULL);

/*Table structure for table `tbl_formato` */

DROP TABLE IF EXISTS `tbl_formato`;

CREATE TABLE `tbl_formato` (
  `codigo` int(11) NOT NULL,
  `id_consulta` varchar(1) DEFAULT NULL,
  `tipo` longtext DEFAULT NULL,
  `longitud` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_formato` */

insert  into `tbl_formato`(`codigo`,`id_consulta`,`tipo`,`longitud`,`created_at`,`updated_at`) values 
(1,'1','[\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\']','[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]',NULL,NULL),
(2,'2','[\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\']','[0,0,0,0,0,0,0,0]',NULL,'2021-12-29 13:03:06'),
(3,'3','[\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\']','[0,0,0,0,0,0,0,0,0,0]',NULL,'2021-12-29 13:02:49'),
(4,'4','[\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\',\'numerico\']','[0,0,0,0,0,0]',NULL,'2021-12-29 13:02:27');

/*Table structure for table `tbl_log` */

DROP TABLE IF EXISTS `tbl_log`;

CREATE TABLE `tbl_log` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_log` */

insert  into `tbl_log`(`codigo`,`descripcion`,`created_at`,`updated_at`) values 
(1,'´tbl_ws_cliente_pedido´ => Could not connect to host','2022-01-11 03:10:38','2022-01-11 03:10:38'),
(2,'´tbl_ws_cliente_pedido´ => Could not connect to host','2022-01-11 03:11:19','2022-01-11 03:11:19'),
(3,'´tbl_ws_cabecera_pedido´ => Could not connect to host','2022-01-11 03:25:03','2022-01-11 03:25:03'),
(4,'´tbl_ws_cabecera_pedido´ => Could not connect to host','2022-01-11 03:33:05','2022-01-11 03:33:05'),
(5,'´tbl_ws_cabecera_pedido´ => Could not connect to host','2022-01-11 03:34:44','2022-01-11 03:34:44'),
(6,'´tbl_ws_cabecera_pedido´ => Could not connect to host','2022-01-11 03:36:09','2022-01-11 03:36:09'),
(7,'´tbl_ws_detalle_pedido´ => SOAP-ERROR: Parsing WSDL: Couldn\'t load from \'http://wspandapruebas.siesacloud.com:8043/WSUNOEE/WSUNOEE.asmx?WSDL\' : failed to load external entity \"http://wspandapruebas.siesacloud.com:8043/WSUNOEE/WSUNOEE.asmx?WSDL\"\n','2022-01-11 03:36:53','2022-01-11 03:36:53'),
(8,'´tbl_ws_detalle_pedido´ => Could not connect to host','2022-01-11 03:37:33','2022-01-11 03:37:33'),
(9,'´tbl_ws_detalle_pedido´ => SOAP-ERROR: Parsing WSDL: Couldn\'t load from \'http://wspandapruebas.siesacloud.com:8043/WSUNOEE/WSUNOEE.asmx?WSDL\' : failed to load external entity \"http://wspandapruebas.siesacloud.com:8043/WSUNOEE/WSUNOEE.asmx?WSDL\"\n','2022-01-11 03:40:45','2022-01-11 03:40:45'),
(10,'´tbl_ws_detalle_pedido´ => Could not connect to host','2022-01-11 03:41:15','2022-01-11 03:41:15');

/*Table structure for table `tbl_plano` */

DROP TABLE IF EXISTS `tbl_plano`;

CREATE TABLE `tbl_plano` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `seccion_a` varchar(45) DEFAULT NULL,
  `seccion_b` varchar(45) DEFAULT NULL,
  `seccion_c` varchar(45) DEFAULT NULL,
  `seccion_d` varchar(45) DEFAULT NULL,
  `seccion_e` varchar(45) DEFAULT NULL,
  `seccion_campo_a` varchar(45) DEFAULT NULL,
  `seccion_campo_b` varchar(45) DEFAULT NULL,
  `seccion_campo_c` varchar(45) DEFAULT NULL,
  `seccion_campo_d` varchar(45) DEFAULT NULL,
  `seccion_campo_e` varchar(45) DEFAULT NULL,
  `seccion_default` varchar(45) DEFAULT '_',
  `extension` varchar(5) DEFAULT NULL,
  `separador` varchar(1) DEFAULT NULL,
  `salto_linea` tinyint(4) DEFAULT NULL,
  `ruta` varchar(150) DEFAULT NULL,
  `display_codigo` tinyint(4) DEFAULT 0,
  `entre_columna` varchar(3) DEFAULT NULL,
  `storage` int(11) DEFAULT NULL,
  `ftp` tinyint(1) DEFAULT 0,
  `sftp` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_plano` */

insert  into `tbl_plano`(`codigo`,`nombre`,`seccion_a`,`seccion_b`,`seccion_c`,`seccion_d`,`seccion_e`,`seccion_campo_a`,`seccion_campo_b`,`seccion_campo_c`,`seccion_campo_d`,`seccion_campo_e`,`seccion_default`,`extension`,`separador`,`salto_linea`,`ruta`,`display_codigo`,`entre_columna`,`storage`,`ftp`,`sftp`,`created_at`,`updated_at`) values 
(1,'CLIENTES PEDIDO','clientes_','AA','MM','DD',NULL,NULL,NULL,NULL,NULL,NULL,'_','.txt','|',1,'/var/www/html/integracion-mondelez/public/plano/',1,NULL,0,1,0,NULL,'2021-12-27 13:38:48'),
(2,'CABECERA PEDIDOS','pedidosCabecera_','AA','MM','DD',NULL,NULL,NULL,NULL,NULL,NULL,'_','.txt','|',1,'/var/www/html/integracion-mondelez/public/plano/',0,NULL,0,1,0,NULL,'2021-12-27 13:38:12'),
(3,'DETALLE PEDIDO','pedidosDetalle_','AA','MM','DD',NULL,NULL,NULL,NULL,NULL,NULL,'_','.txt','|',1,'/var/www/html/integracion-mondelez/public/plano/',0,NULL,0,1,0,NULL,'2021-12-27 13:38:29'),
(4,'RUTEROS PEDIDO','ruteros_','AA','MM','DD',NULL,NULL,NULL,NULL,NULL,NULL,'_','.txt','|',1,'/var/www/html/integracion-mondelez/public/plano/',0,NULL,0,1,0,NULL,'2021-12-27 13:38:39');

/*Table structure for table `tbl_plano_funcion` */

DROP TABLE IF EXISTS `tbl_plano_funcion`;

CREATE TABLE `tbl_plano_funcion` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_consulta` int(11) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `tipo_campo` varchar(45) DEFAULT NULL,
  `longitud` int(11) DEFAULT NULL,
  `posicion` int(11) DEFAULT NULL,
  `consulta` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_plano_funcion` */

insert  into `tbl_plano_funcion`(`codigo`,`id_consulta`,`nombre`,`tipo`,`tipo_campo`,`longitud`,`posicion`,`consulta`,`created_at`,`updated_at`) values 
(1,4,'dia_visita','fecha_b','numerico',0,3,NULL,NULL,'2021-12-28 23:01:36'),
(2,1,'nombre_1','exploy_name_a','numerico',0,3,NULL,NULL,'2021-12-28 21:53:33'),
(3,1,'nombre_2','exploy_name_b','numerico',0,4,NULL,NULL,'2021-12-28 21:53:38'),
(4,1,'apellido_1','exploy_name_c','numerico',0,5,NULL,NULL,'2021-12-28 21:53:50'),
(5,1,'apellido_2','exploy_name_d','numerico',0,6,NULL,NULL,'2021-12-28 21:53:57'),
(6,1,'obtener_nombre','name_us','numerico',0,1,NULL,NULL,'2021-12-28 21:53:12'),
(7,4,'nit','buscar_codigo','numerico',0,1,1,NULL,'2021-12-29 14:53:29'),
(8,4,'agregar_cero','agregar_cero','numerico',0,2,NULL,NULL,NULL),
(9,3,'nit','buscar_codigo','numerico',0,1,1,NULL,NULL),
(10,2,'nit','buscar_codigo','numerico',0,1,1,NULL,NULL);

/*Table structure for table `tbl_quemado` */

DROP TABLE IF EXISTS `tbl_quemado`;

CREATE TABLE `tbl_quemado` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_consulta` int(11) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `longitud` int(11) DEFAULT NULL,
  `valor` varchar(45) DEFAULT NULL,
  `posicion` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_quemado` */

/*Table structure for table `tbl_ws_cabecera_pedido` */

DROP TABLE IF EXISTS `tbl_ws_cabecera_pedido`;

CREATE TABLE `tbl_ws_cabecera_pedido` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigoCliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fechaHoraPedido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numeroPedido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorBruto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorIva` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorTotal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigoVendedor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planoRegistro` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tbl_ws_cabecera_pedido` */

insert  into `tbl_ws_cabecera_pedido`(`codigo`,`codigoCliente`,`fechaHoraPedido`,`numeroPedido`,`valorBruto`,`valorIva`,`valorTotal`,`codigoVendedor`,`planoRegistro`,`created_at`,`updated_at`) values 
(1,'43476370','2021-12-02T00:00:00-05:00','403808','504669.0000','95887.0000','600556.0000','NO',1,NULL,'2022-01-11 04:40:55'),
(2,'8056528','2021-12-02T00:00:00-05:00','403809','504669.0000','95887.0000','600556.0000','NO',1,NULL,'2022-01-11 04:40:55'),
(3,'15334221','2021-12-02T00:00:00-05:00','403810','504669.0000','95887.0000','600556.0000','008',1,NULL,'2022-01-11 04:40:55'),
(4,'3621254','2021-12-02T00:00:00-05:00','403811','504669.0000','95887.0000','600556.0000','NO',1,NULL,'2022-01-11 04:40:55'),
(5,'98661227','2021-12-02T00:00:00-05:00','403812','504669.0000','95887.0000','600556.0000','008',1,NULL,'2022-01-11 04:40:55');

/*Table structure for table `tbl_ws_cliente_pedido` */

DROP TABLE IF EXISTS `tbl_ws_cliente_pedido`;

CREATE TABLE `tbl_ws_cliente_pedido` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombreCliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_comercial` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_clase_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nit` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_identificacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departamento` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nivel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barrio` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sucursal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TipoRegimen` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consecutivo_factura` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planoRegistro` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tbl_ws_cliente_pedido` */

insert  into `tbl_ws_cliente_pedido`(`codigo`,`nombreCliente`,`tipo_cliente`,`nombre_1`,`nombre_2`,`apellido_1`,`apellido_2`,`nom_comercial`,`cod_clase_cliente`,`nit`,`tipo_identificacion`,`telefono`,`celular`,`mail`,`departamento`,`ciudad`,`estado`,`razon_social`,`dir`,`nivel`,`localidad`,`barrio`,`sucursal`,`TipoRegimen`,`consecutivo_factura`,`planoRegistro`,`created_at`,`updated_at`) values 
(1,'ARBOLEDA ORTIZ LUZ MARNA','0','NO','NO','NO','NO','RICURAS DE COPACABANA','85','43476370','1','2746226','NO','factura720@gmail.com','05','05212','A','ARBOLEDA ORTIZ LUZ MARNA','CL 50 53 84','3','COPACABANA','PARQUE','001','1','403808',1,NULL,'2022-01-11 04:40:51'),
(2,'COTERA MEDINA MAURICIO NARINO','0','NO','NO','NO','NO','MINIMERCADO SAN DIEGO','85','8056528','1','2329858','NO','factura720@gmail.com','05','05001','A','COTERA MEDINA MAURICIO NARINO','CR 43 31 40','3','MEDELLIN','SAN DIEGO','001','1','403809',1,NULL,'2022-01-11 04:40:51'),
(3,'CASTAÑEDA  ADAN DE JESUS','0','NO','NO','NO','NO','LA TIENDA DE ADAN','85','15334221','1','4875684','NO','factura720@gmail.com','05','05001','A','CASTAÑEDA  ADAN DE JESUS','TV 48 B S 61 C 09','3','MEDELLIN','SAN ANTONIO DE PRADO','001','1','403810',1,NULL,'2022-01-11 04:40:51'),
(4,'MEJIA  ARTURO','0','NO','NO','NO','NO','LA BANCA','85','3621254','1','3122410137','NO','factura720@gmail.com','05','05001','A','MEJIA  ARTURO','LAS CAMELIAS','3','MEDELLIN','LAS CAMELIAS','000','1','403811',1,NULL,'2022-01-11 04:40:51'),
(5,'TORO LOPEZ NELSON ALBEIRO','0','NO','NO','NO','NO','MERKAFULL','85','98661227','1','3163894978','3163894978','ntoro1513@hotmail.com','05','05360','A','TORO LOPEZ NELSON ALBEIRO','CL 34 61 29','3','ITAGUI','DITAIRES','001','1','403812',1,NULL,'2022-01-11 04:40:51');

/*Table structure for table `tbl_ws_detalle_pedido` */

DROP TABLE IF EXISTS `tbl_ws_detalle_pedido`;

CREATE TABLE `tbl_ws_detalle_pedido` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigoCliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numeroPedido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigoArticulo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Displays` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unidadesSueltas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precioUnitario` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorBrutoVenta` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorIva` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorFinal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consecutivo_item` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planoRegistro` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tbl_ws_detalle_pedido` */

insert  into `tbl_ws_detalle_pedido`(`codigo`,`codigoCliente`,`numeroPedido`,`codigoArticulo`,`Displays`,`unidadesSueltas`,`precioUnitario`,`valorBrutoVenta`,`valorIva`,`valorFinal`,`consecutivo_item`,`planoRegistro`,`created_at`,`updated_at`) values 
(1,'43476370','403808','76222017679200','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10537',1,NULL,'2022-01-11 04:40:59'),
(2,'43476370','403808','76222017832600','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10538',1,NULL,'2022-01-11 04:40:59'),
(3,'43476370','403808','76222018107500','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10539',1,NULL,'2022-01-11 04:40:59'),
(4,'43476370','403808','76222018093800','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10540',1,NULL,'2022-01-11 04:40:59'),
(5,'43476370','403808','77021330101200','0','3.000000','5627.66666666666666666','14188.0000','2695.0000','16883.0000','10542',1,NULL,'2022-01-11 04:40:59'),
(6,'43476370','403808','77021330066411','0','4.000000','5628.25000000000000000','18918.0000','3595.0000','22513.0000','10543',1,NULL,'2022-01-11 04:40:59'),
(7,'43476370','403808','76222108602300','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10547',1,NULL,'2022-01-11 04:40:59'),
(8,'43476370','403808','76222106612700','0','4.000000','1638.00000000000000000','5506.0000','1046.0000','6552.0000','10548',1,NULL,'2022-01-11 04:40:59'),
(9,'43476370','403808','77501680016910','0','5.000000','3276.00000000000000000','13765.0000','2615.0000','16380.0000','10557',1,NULL,'2022-01-11 04:40:59'),
(10,'43476370','403808','76222017766700','0','4.000000','12390.00000000000000000','41647.0000','7913.0000','49560.0000','10567',1,NULL,'2022-01-11 04:40:59'),
(11,'43476370','403808','77021330118100','0','5.000000','12390.00000000000000000','52059.0000','9891.0000','61950.0000','10577',1,NULL,'2022-01-11 04:40:59'),
(12,'43476370','403808','77021330112000','0','4.000000','9324.00000000000000000','31341.0000','5955.0000','37296.0000','10587',1,NULL,'2022-01-11 04:40:59'),
(13,'43476370','403808','76222109630400','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10597',1,NULL,'2022-01-11 04:40:59'),
(14,'43476370','403808','76222107845200','0','5.000000','23603.80000000000000000','99176.0000','18843.0000','118019.0000','10607',1,NULL,'2022-01-11 04:40:59'),
(15,'43476370','403808','76222017702400','0','4.000000','4872.00000000000000000','16376.0000','3112.0000','19488.0000','10637',1,NULL,'2022-01-11 04:40:59'),
(16,'43476370','403808','77021330113400','0','4.000000','4871.75000000000000000','16376.0000','3111.0000','19487.0000','10647',1,NULL,'2022-01-11 04:40:59'),
(17,'43476370','403808','76222108317800','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10707',1,NULL,'2022-01-11 04:40:59'),
(18,'43476370','403808','77021334556110','0','5.000000','6384.00000000000000000','26824.0000','5096.0000','31920.0000','10722',1,NULL,'2022-01-11 04:40:59'),
(19,'8056528','403809','76222017679200','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10537',1,NULL,'2022-01-11 04:40:59'),
(20,'8056528','403809','76222017832600','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10538',1,NULL,'2022-01-11 04:40:59'),
(21,'8056528','403809','76222018107500','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10539',1,NULL,'2022-01-11 04:40:59'),
(22,'8056528','403809','76222018093800','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10540',1,NULL,'2022-01-11 04:40:59'),
(23,'8056528','403809','77021330101200','0','3.000000','5627.66666666666666666','14188.0000','2695.0000','16883.0000','10542',1,NULL,'2022-01-11 04:40:59'),
(24,'8056528','403809','77021330066411','0','4.000000','5628.25000000000000000','18918.0000','3595.0000','22513.0000','10543',1,NULL,'2022-01-11 04:40:59'),
(25,'8056528','403809','76222108602300','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10547',1,NULL,'2022-01-11 04:40:59'),
(26,'8056528','403809','76222106612700','0','4.000000','1638.00000000000000000','5506.0000','1046.0000','6552.0000','10548',1,NULL,'2022-01-11 04:40:59'),
(27,'8056528','403809','77501680016910','0','5.000000','3276.00000000000000000','13765.0000','2615.0000','16380.0000','10557',1,NULL,'2022-01-11 04:40:59'),
(28,'8056528','403809','76222017766700','0','4.000000','12390.00000000000000000','41647.0000','7913.0000','49560.0000','10567',1,NULL,'2022-01-11 04:40:59'),
(29,'8056528','403809','77021330118100','0','5.000000','12390.00000000000000000','52059.0000','9891.0000','61950.0000','10577',1,NULL,'2022-01-11 04:40:59'),
(30,'8056528','403809','77021330112000','0','4.000000','9324.00000000000000000','31341.0000','5955.0000','37296.0000','10587',1,NULL,'2022-01-11 04:40:59'),
(31,'8056528','403809','76222109630400','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10597',1,NULL,'2022-01-11 04:40:59'),
(32,'8056528','403809','76222107845200','0','5.000000','23603.80000000000000000','99176.0000','18843.0000','118019.0000','10607',1,NULL,'2022-01-11 04:40:59'),
(33,'8056528','403809','76222017702400','0','4.000000','4872.00000000000000000','16376.0000','3112.0000','19488.0000','10637',1,NULL,'2022-01-11 04:40:59'),
(34,'8056528','403809','77021330113400','0','4.000000','4871.75000000000000000','16376.0000','3111.0000','19487.0000','10647',1,NULL,'2022-01-11 04:40:59'),
(35,'8056528','403809','76222108317800','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10707',1,NULL,'2022-01-11 04:40:59'),
(36,'8056528','403809','77021334556110','0','5.000000','6384.00000000000000000','26824.0000','5096.0000','31920.0000','10722',1,NULL,'2022-01-11 04:40:59'),
(37,'15334221','403810','76222017679200','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10537',1,NULL,'2022-01-11 04:40:59'),
(38,'15334221','403810','76222017832600','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10538',1,NULL,'2022-01-11 04:40:59'),
(39,'15334221','403810','76222018107500','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10539',1,NULL,'2022-01-11 04:40:59'),
(40,'15334221','403810','76222018093800','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10540',1,NULL,'2022-01-11 04:40:59'),
(41,'15334221','403810','77021330101200','0','3.000000','5627.66666666666666666','14188.0000','2695.0000','16883.0000','10542',1,NULL,'2022-01-11 04:40:59'),
(42,'15334221','403810','77021330066411','0','4.000000','5628.25000000000000000','18918.0000','3595.0000','22513.0000','10543',1,NULL,'2022-01-11 04:40:59'),
(43,'15334221','403810','76222108602300','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10547',1,NULL,'2022-01-11 04:40:59'),
(44,'15334221','403810','76222106612700','0','4.000000','1638.00000000000000000','5506.0000','1046.0000','6552.0000','10548',1,NULL,'2022-01-11 04:40:59'),
(45,'15334221','403810','77501680016910','0','5.000000','3276.00000000000000000','13765.0000','2615.0000','16380.0000','10557',1,NULL,'2022-01-11 04:40:59'),
(46,'15334221','403810','76222017766700','0','4.000000','12390.00000000000000000','41647.0000','7913.0000','49560.0000','10567',1,NULL,'2022-01-11 04:40:59'),
(47,'15334221','403810','77021330118100','0','5.000000','12390.00000000000000000','52059.0000','9891.0000','61950.0000','10577',1,NULL,'2022-01-11 04:40:59'),
(48,'15334221','403810','77021330112000','0','4.000000','9324.00000000000000000','31341.0000','5955.0000','37296.0000','10587',1,NULL,'2022-01-11 04:40:59'),
(49,'15334221','403810','76222109630400','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10597',1,NULL,'2022-01-11 04:40:59'),
(50,'15334221','403810','76222107845200','0','5.000000','23603.80000000000000000','99176.0000','18843.0000','118019.0000','10607',1,NULL,'2022-01-11 04:40:59'),
(51,'15334221','403810','76222017702400','0','4.000000','4872.00000000000000000','16376.0000','3112.0000','19488.0000','10637',1,NULL,'2022-01-11 04:40:59'),
(52,'15334221','403810','77021330113400','0','4.000000','4871.75000000000000000','16376.0000','3111.0000','19487.0000','10647',1,NULL,'2022-01-11 04:40:59'),
(53,'15334221','403810','76222108317800','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10707',1,NULL,'2022-01-11 04:40:59'),
(54,'15334221','403810','77021334556110','0','5.000000','6384.00000000000000000','26824.0000','5096.0000','31920.0000','10722',1,NULL,'2022-01-11 04:40:59'),
(55,'3621254','403811','76222017679200','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10537',1,NULL,'2022-01-11 04:40:59'),
(56,'3621254','403811','76222017832600','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10538',1,NULL,'2022-01-11 04:40:59'),
(57,'3621254','403811','76222018107500','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10539',1,NULL,'2022-01-11 04:40:59'),
(58,'3621254','403811','76222018093800','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10540',1,NULL,'2022-01-11 04:40:59'),
(59,'3621254','403811','77021330101200','0','3.000000','5627.66666666666666666','14188.0000','2695.0000','16883.0000','10542',1,NULL,'2022-01-11 04:40:59'),
(60,'3621254','403811','77021330066411','0','4.000000','5628.25000000000000000','18918.0000','3595.0000','22513.0000','10543',1,NULL,'2022-01-11 04:40:59'),
(61,'3621254','403811','76222108602300','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10547',1,NULL,'2022-01-11 04:40:59'),
(62,'3621254','403811','76222106612700','0','4.000000','1638.00000000000000000','5506.0000','1046.0000','6552.0000','10548',1,NULL,'2022-01-11 04:40:59'),
(63,'3621254','403811','77501680016910','0','5.000000','3276.00000000000000000','13765.0000','2615.0000','16380.0000','10557',1,NULL,'2022-01-11 04:40:59'),
(64,'3621254','403811','76222017766700','0','4.000000','12390.00000000000000000','41647.0000','7913.0000','49560.0000','10567',1,NULL,'2022-01-11 04:40:59'),
(65,'3621254','403811','77021330118100','0','5.000000','12390.00000000000000000','52059.0000','9891.0000','61950.0000','10577',1,NULL,'2022-01-11 04:40:59'),
(66,'3621254','403811','77021330112000','0','4.000000','9324.00000000000000000','31341.0000','5955.0000','37296.0000','10587',1,NULL,'2022-01-11 04:40:59'),
(67,'3621254','403811','76222109630400','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10597',1,NULL,'2022-01-11 04:40:59'),
(68,'3621254','403811','76222107845200','0','5.000000','23603.80000000000000000','99176.0000','18843.0000','118019.0000','10607',1,NULL,'2022-01-11 04:40:59'),
(69,'3621254','403811','76222017702400','0','4.000000','4872.00000000000000000','16376.0000','3112.0000','19488.0000','10637',1,NULL,'2022-01-11 04:40:59'),
(70,'3621254','403811','77021330113400','0','4.000000','4871.75000000000000000','16376.0000','3111.0000','19487.0000','10647',1,NULL,'2022-01-11 04:40:59'),
(71,'3621254','403811','76222108317800','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10707',1,NULL,'2022-01-11 04:40:59'),
(72,'3621254','403811','77021334556110','0','5.000000','6384.00000000000000000','26824.0000','5096.0000','31920.0000','10722',1,NULL,'2022-01-11 04:40:59'),
(73,'98661227','403812','76222017679200','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10537',1,NULL,'2022-01-11 04:40:59'),
(74,'98661227','403812','76222017832600','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10538',1,NULL,'2022-01-11 04:40:59'),
(75,'98661227','403812','76222018107500','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10539',1,NULL,'2022-01-11 04:40:59'),
(76,'98661227','403812','76222018093800','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10540',1,NULL,'2022-01-11 04:40:59'),
(77,'98661227','403812','77021330101200','0','3.000000','5627.66666666666666666','14188.0000','2695.0000','16883.0000','10542',1,NULL,'2022-01-11 04:40:59'),
(78,'98661227','403812','77021330066411','0','4.000000','5628.25000000000000000','18918.0000','3595.0000','22513.0000','10543',1,NULL,'2022-01-11 04:40:59'),
(79,'98661227','403812','76222108602300','0','5.000000','5628.00000000000000000','23647.0000','4493.0000','28140.0000','10547',1,NULL,'2022-01-11 04:40:59'),
(80,'98661227','403812','76222106612700','0','4.000000','1638.00000000000000000','5506.0000','1046.0000','6552.0000','10548',1,NULL,'2022-01-11 04:40:59'),
(81,'98661227','403812','77501680016910','0','5.000000','3276.00000000000000000','13765.0000','2615.0000','16380.0000','10557',1,NULL,'2022-01-11 04:40:59'),
(82,'98661227','403812','76222017766700','0','4.000000','12390.00000000000000000','41647.0000','7913.0000','49560.0000','10567',1,NULL,'2022-01-11 04:40:59'),
(83,'98661227','403812','77021330118100','0','5.000000','12390.00000000000000000','52059.0000','9891.0000','61950.0000','10577',1,NULL,'2022-01-11 04:40:59'),
(84,'98661227','403812','77021330112000','0','4.000000','9324.00000000000000000','31341.0000','5955.0000','37296.0000','10587',1,NULL,'2022-01-11 04:40:59'),
(85,'98661227','403812','76222109630400','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10597',1,NULL,'2022-01-11 04:40:59'),
(86,'98661227','403812','76222107845200','0','5.000000','23603.80000000000000000','99176.0000','18843.0000','118019.0000','10607',1,NULL,'2022-01-11 04:40:59'),
(87,'98661227','403812','76222017702400','0','4.000000','4872.00000000000000000','16376.0000','3112.0000','19488.0000','10637',1,NULL,'2022-01-11 04:40:59'),
(88,'98661227','403812','77021330113400','0','4.000000','4871.75000000000000000','16376.0000','3111.0000','19487.0000','10647',1,NULL,'2022-01-11 04:40:59'),
(89,'98661227','403812','76222108317800','0','4.000000','7476.00000000000000000','25129.0000','4775.0000','29904.0000','10707',1,NULL,'2022-01-11 04:40:59'),
(90,'98661227','403812','77021334556110','0','5.000000','6384.00000000000000000','26824.0000','5096.0000','31920.0000','10722',1,NULL,'2022-01-11 04:40:59');

/*Table structure for table `tbl_ws_rutero_pedido` */

DROP TABLE IF EXISTS `tbl_ws_rutero_pedido`;

CREATE TABLE `tbl_ws_rutero_pedido` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigoCliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frecuencia_visita` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_visita` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden_visita` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_vendedor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consecutivo_factura` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consecutivo_item` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planoRegistro` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tbl_ws_rutero_pedido` */

insert  into `tbl_ws_rutero_pedido`(`codigo`,`codigoCliente`,`frecuencia_visita`,`dia_visita`,`orden_visita`,`cod_vendedor`,`consecutivo_factura`,`consecutivo_item`,`planoRegistro`,`created_at`,`updated_at`) values 
(1,'43476370','0','2021-12-02T00:00:00-05:00','0','NO','403808','NO',1,NULL,'2022-01-11 04:41:02'),
(2,'8056528','0','2021-12-02T00:00:00-05:00','0','NO','403809','NO',1,NULL,'2022-01-11 04:41:02'),
(3,'15334221','1','2021-12-02T00:00:00-05:00','100','008','403810','NO',1,NULL,'2022-01-11 04:41:02'),
(4,'15334221','1','2021-12-02T00:00:00-05:00','90','047','403810','NO',1,NULL,'2022-01-11 04:41:02'),
(5,'15334221','1','2021-12-02T00:00:00-05:00','90','057','403810','NO',1,NULL,'2022-01-11 04:41:02'),
(6,'3621254','0','2021-12-02T00:00:00-05:00','0','NO','403811','NO',1,NULL,'2022-01-11 04:41:02'),
(7,'98661227','1','2021-12-02T00:00:00-05:00','110','046','403812','NO',1,NULL,'2022-01-11 04:41:02'),
(8,'98661227','1','2021-12-02T00:00:00-05:00','190','008','403812','NO',1,NULL,'2022-01-11 04:41:02'),
(9,'98661227','1','2021-12-02T00:00:00-05:00','110','056','403812','NO',1,NULL,'2022-01-11 04:41:02');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
