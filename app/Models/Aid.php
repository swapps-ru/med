<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aid
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc_short
 * @property string|null $desc_full
 * @property array $aid_substance_ids
 * @property int $popularity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Aid extends Model
{
	protected $table = 'aids';

	protected $casts = [
		'popularity' => 'int',
        'aid_substance_ids' => \App\Casts\IDStringCast::class
	];

	protected $fillable = [
		'name',
		'desc_short',
		'desc_full',
		'aid_substance_ids',
		'popularity'
	];

    public function aidGroup()
    {
        return $this->belongsTo(AidGroup::class, 'aid_group_id');
    }

    public function aidSubstances()
    {
        return AidSubstance::whereIn('id', $this->aid_substance_ids);
    }

    public function articles()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Article::where('aid_ids', 'like', '%_' . $this->id . '_%');
    }
}
