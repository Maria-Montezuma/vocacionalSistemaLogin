<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Nacionalidade
 * 
 * @property int $idNacionalidad
 * @property string $NombreNacionalidad
 * 
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Nacionalidade extends Model
{
	protected $table = 'nacionalidades';
	protected $primaryKey = 'idNacionalidad';
	public $timestamps = false;

	protected $fillable = [
		'NombreNacionalidad'
	];

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'Nacionalidades_idNacionalidad');
	}
}
