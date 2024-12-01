<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Token
 * 
 * @property int $idToken
 * @property int $Usuarios_idUsuario
 * @property string $Token
 * @property string $TipoToken
 * @property Carbon $TiempoExpiracion
 * @property int $Usado
 * 
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Token extends Model
{
	protected $table = 'tokens';
	protected $primaryKey = 'idToken';
	public $timestamps = false;

	protected $casts = [
		'Usuarios_idUsuario' => 'int',
		'TiempoExpiracion' => 'datetime',
		'Usado' => 'int'
	];

	protected $fillable = [
		'Usuarios_idUsuario',
		'Token',
		'TipoToken',
		'TiempoExpiracion',
		'Usado'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'Usuarios_idUsuario');
	}
}
