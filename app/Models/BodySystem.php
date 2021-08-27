<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DB;

/**
 * Class BodySystem
 * 
 * @property int $id
 * @property string|null $names
 * @property string|null $icon_class
 * @property string|null $svg_url
 * @property string|null $svg_element_id
 *
 * @package App\Models
 */
class BodySystem extends Model
{
	protected $table = 'body_systems';
	public $timestamps = false;

	protected $fillable = [
		'names',
		'icon_class',
		'svg_url',
		'svg_element_id'
	];

    protected $casts = [
        'names' => \App\Casts\ExplodeCast::class
    ];

    public function diseaseGroups()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return DiseaseGroup::where('body_system_ids', 'like', '%_' . $this->id . '_%');
    }

    public function diseases()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Disease::where('body_system_ids', 'like', '%_' . $this->id . '_%');
    }

    public function symptoms()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Symptom::where('body_system_ids', 'like', '%_' . $this->id . '_%');
    }
}
