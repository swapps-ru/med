<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DiseaseGroup
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $name_scientific
 * @property array $body_system_ids
 * @property string|null $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class DiseaseGroup extends Model
{
	protected $table = 'disease_groups';

    protected $casts = [
        'names' => \App\Casts\ExplodeCast::class,
        'names_scientific' => \App\Casts\ExplodeCast::class,
        'body_system_ids' => \App\Casts\IDStringCast::class,
    ];

	protected $fillable = [
		'name',
		'name_scientific',
		'body_system_ids'
	];

    public function diseases()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Disease::where('disease_groups_ids', 'like', '%_' . $this->id . '_%');
    }
}
