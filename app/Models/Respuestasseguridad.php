<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Respuestasseguridad
 * 
 * @property int $idRespuestasSeguridad
 * @property string $RespuestaSeguridad_hash
 * @property int $PreguntasSeguridad_idPreguntasSeguridad
 * @property int $Usuarios_idUsuario
 * 
 * @property Preguntasseguridad $preguntasseguridad
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Respuestasseguridad extends Model
{
	protected $table = 'respuestasseguridad';
	protected $primaryKey = 'idRespuestasSeguridad';
	public $timestamps = false;

	protected $casts = [
		'PreguntasSeguridad_idPreguntasSeguridad' => 'int',
		'Usuarios_idUsuario' => 'int'
	];

	protected $fillable = [
		'RespuestaSeguridad_hash',
		'PreguntasSeguridad_idPreguntasSeguridad',
		'Usuarios_idUsuario'
	];

	public function preguntasseguridad()
	{
		return $this->belongsTo(Preguntasseguridad::class, 'PreguntasSeguridad_idPreguntasSeguridad');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'Usuarios_idUsuario');
	}
}
