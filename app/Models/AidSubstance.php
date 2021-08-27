<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AidSubstance
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc_short
 * @property string|null $desc_full
 * @property int $aid_group_id
 * @property array $restricted_aid_group_ids
 * @property array $restricted_aid_substance_ids
 * @property array $careful_aid_groups_ids
 * @property array $careful_aid_substance_ids
 * @property string $allowed_pregnant
 * @property string $allowed_alco
 * @property string $allowed_driving
 * @property int $allowed_age_min
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property AidGroup $aid_group
 *
 * @package App\Models
 */
class AidSubstance extends Model
{
	protected $table = 'aid_substances';

	protected $casts = [
		'aid_group_id' => 'int',
		'allowed_age_min' => 'int',
        'restricted_aid_group_ids' => \App\Casts\IDStringCast::class,
        'restricted_aid_substance_ids' => \App\Casts\IDStringCast::class,
        'careful_aid_groups_ids' => \App\Casts\IDStringCast::class,
        'careful_aid_substance_ids' => \App\Casts\IDStringCast::class,
    ];

	protected $fillable = [
		'name',
		'desc_short',
		'desc_full',
		'aid_group_id',
		'restricted_aid_group_ids',
		'restricted_aid_substance_ids',
		'careful_aid_groups_ids',
		'careful_aid_substance_ids',
		'allowed_pregnant',
		'allowed_alco',
		'allowed_driving',
		'allowed_age_min'
	];

	public function aidGroup()
	{
		return $this->belongsTo(AidGroup::class);
	}

    public function restrictedAidGroups()
    {
        return AidGroup::whereIn('id', $this->restricted_aid_group_ids);
    }

    public function restrictedAidSubstances()
    {
        return AidSubstance::whereIn('id', $this->restricted_aid_substance_ids);
    }

    public function carefulAidGroups()
    {
        return AidGroup::whereIn('id', $this->careful_aid_groups_ids);
    }

    public function carefulAidSubstances()
    {
        return AidSubstance::whereIn('id', $this->careful_aid_substance_ids);
    }

    public function aids()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Aid::where('aid_substance_ids', 'like', '%_' . $this->id . '_%');
    }
}
