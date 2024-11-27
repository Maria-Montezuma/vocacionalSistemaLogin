<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Redessociale
 * 
 * @property int $idRedSocial
 * @property string|null $RedSocial
 * @property string|null $UrlRedSocial
 * @property string|null $WebPersonal
 * @property int $Usuarios_idUsuario
 * 
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Redessociale extends Model
{
	protected $table = 'redessociales';
	protected $primaryKey = 'idRedSocial';
	public $timestamps = false;

	protected $casts = [
		'Usuarios_idUsuario' => 'int'
	];

	protected $fillable = [
		'RedSocial',
		'UrlRedSocial',
		'WebPersonal',
		'Usuarios_idUsuario'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'Usuarios_idUsuario');
	}
}
