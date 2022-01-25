<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\ConsultaCondicion;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\LogTable;

class WebService extends Command
{
    protected $signature = 'integracion:webservice';
    protected $description = 'Obtener información y generar guardado en base de datos';

    public function __construct(){ parent::__construct(); }

    public function handle(){

        $dataConexion = Conexion::where('estado',1)->first(); $listaConsulta = Consulta::where('estado',1)->orderBy('codigo','asc')->get(); $resultado = array();
        
        $sentencia = 'SET QUOTED_IDENTIFIER OFF; 
SELECT TOP 10   
    ISNULL(f200_nit,"") as codigoCliente    
    ,ISNULL(f5791_ind_frecuencia,"") as frecuencia_visita   
    ,ISNULL(f350_fecha,"") as dia_visita    
    ,ISNULL(f5791_orden,"") as orden_visita 
    ,ISNULL(f5790_id_vendedor,"") as cod_vendedor 
    ,ISNULL(f350_consec_docto,"") as consecutivo_factura

FROM t350_co_docto_contable     
    LEFT JOIN t461_cm_docto_factura_venta ON t350_co_docto_contable.f350_rowid = t461_cm_docto_factura_venta.f461_rowid_docto and t350_co_docto_contable.f350_id_cia = t461_cm_docto_factura_venta.f461_id_cia  
    LEFT JOIN t470_cm_movto_invent ON t461_cm_docto_factura_venta.f461_rowid_docto = t470_cm_movto_invent.f470_rowid_docto_fact and t461_cm_docto_factura_venta.f461_id_cia = t470_cm_movto_invent.f470_id_cia  
    LEFT JOIN t201_mm_clientes ON (f461_rowid_tercero_fact = f201_rowid_tercero AND f461_id_sucursal_fact = f201_id_sucursal and f461_id_cia = f201_id_cia)     
    LEFT JOIN t200_mm_terceros ON f200_rowid = f201_rowid_tercero and f200_id_cia = f201_id_cia     
    LEFT JOIN t121_mc_items_extensiones ON f121_rowid = f470_rowid_item_ext and f121_id_cia = f470_id_cia   
    LEFT JOIN t120_mc_items ON t120_mc_items.f120_rowid = f121_rowid_item and f120_id_cia = f121_id_cia         
    LEFT JOIN t125_mc_items_criterios ON f125_rowid_item = f120_rowid   
    LEFT JOIN t106_mc_criterios_item_mayores ON f106_id = f125_id_criterio_mayor    
    LEFT JOIN t015_mm_contactos on f015_rowid = f201_rowid_contacto 
    LEFT JOIN t5791_sm_ruta_frecuencia on f5791_rowid_tercero = f200_rowid and f200_id_cia = 1  
    LEFT JOIN t5790_sm_ruta on f5791_rowid_ruta = f5790_rowid and f5790_id_cia = 1

WHERE RIGHT(""+CAST(f120_id AS VARCHAR(20)),20) &lt;&gt; ""     
    and t350_co_docto_contable.f350_id_tipo_docto = "FTM"   
    and t350_co_docto_contable.f350_consec_docto >= 1 
    and t350_co_docto_contable.f350_id_cia = 1  
    and RIGHT(""+CAST(f120_id AS VARCHAR(20)),20) >= 18000  
    and LTRIM(RTRIM(f106_id_plan)) = 2  
    and LTRIM(RTRIM(f125_id_criterio_mayor)) = 41   
    and f201_id_cia = 1 
    and f015_id_cia = 1     
    and f200_ind_estado = 1 
    and f201_ind_estado_activo = 1  
    and f201_id_cond_pago is not null   
    and t350_co_docto_contable.f350_id_sucursal is not null 
    
GROUP BY f350_consec_docto,f5790_id_vendedor,f5791_ind_frecuencia,f5791_orden,f350_fecha,f200_nit 
ORDER BY f350_consec_docto ASC 
SET QUOTED_IDENTIFIER ON;';

        $xml = Funciones::consultaStructuraXML($dataConexion->conexion,$dataConexion->cia,$dataConexion->proveedor,$dataConexion->usuario,$dataConexion->clave,$sentencia,$dataConexion->consulta,1,0);
        $resultado = Funciones::SOAP($dataConexion->url, $xml, 'CONSULTA => ');
        
        echo "<br>================= [ RESULTADO CONSULTA ] ==================<br>";
        if ($resultado != null) {
            foreach ($resultado as $key => $value) {
                echo "<br><br><br><br>== RESULTADO : [ ".$key." ] ==";
                foreach ($value as $keyA => $valueA) {
                    echo "<br> &nbsp&nbsp&nbsp&nbsp ------> ".$valueA;
                }
            }
        }else{
            echo "NO SE ENCONTRARÓN RESULTADOS";
        }
        
        echo "<br><br><br><br>===========================================================<br><br>";
        echo "<br>---------------------- DUMP --------------------<br>";
        print_r($resultado);
        echo "<br>------------------------------------------------<br>";

        // $resultado = explode(',', $resultado);
        // print($resultado);

    }  

}