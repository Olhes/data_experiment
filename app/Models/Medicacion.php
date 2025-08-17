<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicacion extends Model
{
    use HasFactory;

    protected $table = 'medicacion';
    protected $primaryKey = 'id_medicina';
    public $timestamps = false;

    protected $fillable = [
        'nombre_comercial',
        'nombre_generico',
        'presentacion',
        'dosis_estandar',
        'fabricante',
        'descripcion',
    ];

    // Scope para buscar por nombre comercial
    public function scopePorNombreComercial($query, $nombre)
    {
        return $query->where('nombre_comercial', 'LIKE', "%{$nombre}%");
    }

    // Scope para buscar por nombre genérico
    public function scopePorNombreGenerico($query, $nombre)
    {
        return $query->where('nombre_generico', 'LIKE', "%{$nombre}%");
    }

    // Scope para buscar por fabricante
    public function scopePorFabricante($query, $fabricante)
    {
        return $query->where('fabricante', 'LIKE', "%{$fabricante}%");
    }

    // Método para búsqueda general
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre_comercial', 'LIKE', "%{$termino}%")
                    ->orWhere('nombre_generico', 'LIKE', "%{$termino}%")
                    ->orWhere('fabricante', 'LIKE', "%{$termino}%");
    }
}
