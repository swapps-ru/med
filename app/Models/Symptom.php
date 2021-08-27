<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Symptom
 * 
 * @property int $id
 * @property string|null $names
 * @property string|null $names_scientific
 * @property string|null $icon_class
 * @property string|null $body_part_ids
 * @property array|null $options_default_json
 * @property string $type
 * @property int $wordstat_queries
 * @property string|null $wordstat_query_names
 * @property int $article_main_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Symptom extends Model
{
	protected $table = 'symptoms';

	protected $casts = [
		'options_default_json' => 'json',
		'wordstat_queries' => 'int',
		'article_main_id' => 'int',
        'body_system_ids' => \App\Casts\IDStringCast::class
	];

	protected $fillable = [
		'names',
		'names_scientific',
		'icon_class',
		'body_system_ids',
		'options_default_json',
		'type',
		'wordstat_queries',
		'wordstat_query_names',
		'article_main_id'
	];


    public function bodySystems()
    {
        return BodySystem::whereIn('id', $this->body_system_ids);
    }

    public function articleMain()
    {
        return $this->hasOne(Article::class, 'id', 'article_main_id');
    }

    public function syndromes()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Syndrome::where('symptom_include_ids', 'like', '%_' . $this->id . '_%');
    }

    public function articles()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Article::where('symptom_ids', 'like', '%_' . $this->id . '_%');
    }
}
