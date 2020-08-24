<?php

namespace App\Exports;

use App\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatosFiscalesExport implements FromCollection, WithHeadings
{
    public function __construct($fecha, $oficina_id)
    {
        $this->fecha = $fecha;
        $this->oficina_id = $oficina_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ventas = Venta::where('requiere_factura',1);

        if($this->fecha){
            $ventas = $ventas->where('fecha', $this->fecha);
        }

        if($this->oficina_id){
            $ventas = $ventas->where('oficina_id', $this->oficina_id);
        }

        return $ventas->get()->pluck('productos')
            ->flatten()
            ->map(function ($producto) {
                $venta = Venta::find($producto->pivot->venta_id);
                $array = array($venta->paciente->datoFiscal->rfc,$venta->paciente->datoFiscal->homoclave);
                $RFC_ = implode("", $array);
                
                return [
                    'paciente' => $venta->paciente->datoFiscal->paciente ? $venta->paciente->datoFiscal->paciente->fullname : '',
                    'tipo_persona' => $venta->paciente->datoFiscal->tipo_persona,
                    'nombre_o_razon_social' => $venta->paciente->datoFiscal->nombre_o_razon_social,
                    'regimen_fiscal' => $venta->paciente->datoFiscal->regimen_fiscal,
                    //'homoclave' => $venta->paciente->datoFiscal->homoclave,
                    'correo' => $venta->paciente->datoFiscal->correo,
                    'rfc' => $RFC_,
                    'calle' => $venta->paciente->datoFiscal->calle,
                    'num_ext' => $venta->paciente->datoFiscal->num_ext,
                    'num_int' => $venta->paciente->datoFiscal->num_int,
                    'colonia' => $venta->paciente->datoFiscal->colonia,
                    'ciudad' => $venta->paciente->datoFiscal->ciudad,
                    'alcaldia_o_municipio' => $venta->paciente->datoFiscal->alcaldia_o_municipio,
                    'estado' => $venta->paciente->datoFiscal->estado,
                    'codigo_postal' => $venta->paciente->datoFiscal->codigo_postal,
                    'porcentaje_descuento' => '',
                    'nombre_descuento' => '',
                    'uso_cfdi' => $venta->paciente->datoFiscal->uso_cfdi,
                    'fecha' => substr($venta->fecha,0,11)
                    // 'precio_sin_iva' => $producto->precio_publico,
                    // 'precio_con_iva' => $producto->precio_publico_iva,
                    // 'descuento' => $venta->promocion ? $venta->promocion->descuento_de . " (" . $venta->promocion->unidad_descuento . ")" : '',
                    // 'cantidad' => $producto->pivot->cantidad,
                    // 'sku' => $producto->sku,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'PACIENTE',
            'TIPO PERSONA',
            'NOMBRE O RAZÓN SOCIAL',
            'REGIMEN FISCAL',
            //'HOMOCLAVE',
            'CORREO',
            'RFC',
            'CALLE',
            'NUM. EXT',
            'NUM. INT',
            'COLONIA',
            'CIUDAD',
            'ALCALDIA O MUNICIPIO',
            'ESTADO',
            'CODIGO POSTAL',
            'PORCENTAJE DESCUENTO',
            'NOMBRE DESCUENTO',
            'USO CFDI',
            'FECHA'
            // 'PRECIO SIN IVA',
            // 'PRECIO CON IVA',
            // 'DESCUENTO',
            // 'CANTIDAD',
            // 'SKU'
        ];
    }
}
