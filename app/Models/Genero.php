<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Genero
 * 
 * @property int $idGenero
 * @property string $NombreGenero
 * 
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Genero extends Model
{
	protected $table = 'generos';
	protected $primaryKey = 'idGenero';
	public $timestamps = false;

	protected $fillable = [
		'NombreGenero'
	];

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'Generos_idGenero');
	}
}
