<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

class DataDev
{

    public $respuesta = [
        "mensaje" => "No Funcionó",
        "activo" => null,
        "estatus" => 404,
        "clases" => [
            "200" => "alert-success",
            "201" => "alert-success",
            "301" => "alert-warning",
            "401" => "alert-warning",
            "404" => "alert-danger",
            "500" => "alert-danger"
        ],
        "icono" => [
            "200" => "bi bi-check-circle me-1",
            "201" => "bi bi-check-circle me-1",
            "301" => "bi bi-exclamation-triangle me-1",
            "401" => "bi bi-exclamation-octagon me-1",
            "404" => "bi bi-exclamation-octagon me-1",
            "500" => "bi bi-exclamation-octagon me-1"
        ]
    ];

    public $notificaciones = [
        "total" => 5,
        "data"=>[
            ["descripcion"=>"Franklin Pago", "tipo"=>"Pago", "route"=> "admin.pagos.index"],
            ["descripcion"=>"Franklin 2 Pago", "tipo"=>"Pago", "route"=> "admin.pagos.index"],
            ["descripcion"=>"Franklin 3 Pago", "tipo"=>"Pago", "route"=> "admin.pagos.index"],
            ["descripcion"=>"Franklin 4 Pago", "tipo"=>"Pago", "route"=> "admin.pagos.index"],
            ["descripcion"=>"Franklin 5 Pago", "tipo"=>"Pago", "route"=> "admin.pagos.index"]
        ]
    ];

    public $dias = [
        "Lunes",
        "Martes",
        "Miercoles",
        "Jueves",
        "Viernes",
        "Sabado",
        "Domingo"
    ];

    public $metodosPagos = [ 
        "TD", 
        "TC", 
        "EFECTIVO", 
        "PAGO MOVIL", 
        "DIVISAS", 
        "TRANSFERENCIA", 
        "ZELLE", 
        "BIO PAGO", 
        "OTRO"
    ];
    
    public $estatus = [
        "0" => "Eliminado",
        "1" => "Activo",
        "2" => "Inactivo",
        "3" => "Completado",
        "4" => "Pendiente"
    ];

    public static $respuestaTail = [
        "mensaje" => "No Funcionó",
        "activo" => null,
        "estatus" => 404,
        "clases" => [
            "200" => "bg-green-500",
            "201" => "bg-green-500",
            "301" => "bg-yellow-500",
            "401" => "bg-yellow-500",
            "404" => "bg-red-500",
            "500" => "bg-red-500"
        ],
        "icono" => [
            "200" => "bi bi-check-circle me-1",
            "201" => "bi bi-check-circle me-1",
            "301" => "bi bi-exclamation-triangle me-1",
            "401" => "bi bi-exclamation-octagon me-1",
            "404" => "bi bi-exclamation-octagon me-1",
            "500" => "bi bi-exclamation-octagon me-1"
        ]
    ];

    public function getRespuesta(){
        return $this->respuesta;
    }

    public function getNotificaciones(){
        return $this->notificaciones;
    }

    public function getMetodosPagos(){
        return $this->metodosPagos;
    }

    public function getEstatusText(){
        return $this->estatus;
    }

}
