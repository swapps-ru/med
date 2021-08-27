<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AidGroup
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc_short
 * @property string|null $desc_full
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|AidSubstance[] $aid_substances
 *
 * @package App\Models
 */
class AidGroup extends Model
{
	protected $table = 'aid_groups';

	protected $fillable = [
		'name',
		'desc_short',
		'desc_full'
	];

	public function aidSubstances()
	{
		return $this->hasMany(AidSubstance::class, 'aid_group_id');
	}

    public function aids()
    {
        return $this->hasMany(Aid::class, 'aid_group_id');
    }
}
