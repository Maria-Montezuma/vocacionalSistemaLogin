<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property int $idUsuario
 * @property string $NombreUsuario
 * @property string $ApellidoUsuario
 * @property string $CorreoUsuario
 * @property string $ContrasenaUsuario
 * @property string $FechaNacimientoUsuario
 * @property int $CedulaUsuario
 * @property string $DireccionUsuario
 * @property string $DescripcionUsuario
 * @property int $Generos_idGenero
 * @property int $Nacionalidades_idNacionalidad
 * @property bool $verificado
 * 
 * @property Genero $genero
 * @property Nacionalidade $nacionalidade
 * @property Collection|Redessociale[] $redessociales
 * @property Collection|Respuestasseguridad[] $respuestasseguridads
 * @property Collection|Token[] $tokens
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuarios';
	protected $primaryKey = 'idUsuario';
	public $timestamps = false;

	protected $casts = [
		'CedulaUsuario' => 'int',
		'Generos_idGenero' => 'int',
		'Nacionalidades_idNacionalidad' => 'int',
		'verificado' => 'bool'
	];

	protected $fillable = [
		'NombreUsuario',
		'ApellidoUsuario',
		'CorreoUsuario',
		'ContrasenaUsuario',
		'FechaNacimientoUsuario',
		'CedulaUsuario',
		'DireccionUsuario',
		'DescripcionUsuario',
		'Generos_idGenero',
		'Nacionalidades_idNacionalidad',
		'verificado'
	];

	public function genero()
	{
		return $this->belongsTo(Genero::class, 'Generos_idGenero');
	}

	public function nacionalidade()
	{
		return $this->belongsTo(Nacionalidade::class, 'Nacionalidades_idNacionalidad');
	}

	public function redessociales()
	{
		return $this->hasMany(Redessociale::class, 'Usuarios_idUsuario');
	}

	public function respuestasseguridads()
	{
		return $this->hasMany(Respuestasseguridad::class, 'Usuarios_idUsuario');
	}

	public function tokens()
	{
		return $this->hasMany(Token::class, 'Usuarios_idUsuario');
	}
}
