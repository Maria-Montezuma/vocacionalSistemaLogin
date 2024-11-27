<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Preguntasseguridad
 * 
 * @property int $idPreguntasSeguridad
 * @property string $PreguntasSeguridad
 * 
 * @property Collection|Respuestasseguridad[] $respuestasseguridads
 *
 * @package App\Models
 */
class Preguntasseguridad extends Model
{
	protected $table = 'preguntasseguridad';
	protected $primaryKey = 'idPreguntasSeguridad';
	public $timestamps = false;

	protected $fillable = [
		'PreguntasSeguridad'
	];

	public function respuestasseguridads()
	{
		return $this->hasMany(Respuestasseguridad::class, 'PreguntasSeguridad_idPreguntasSeguridad');
	}
}
