<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Disease
 * 
 * @property int $id
 * @property string|null $names
 * @property string|null $names_scientific
 * @property array $body_system_ids
 * @property array $disease_groups_ids
 * @property array $diseases_complication_ids
 * @property int $wordstat_queries
 * @property string|null $wordstat_query_names
 * @property int $article_main_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Disease extends Model
{
	protected $table = 'diseases';

	protected $casts = [
		'wordstat_queries' => 'int',
		'article_main_id' => 'int',
        'names' => \App\Casts\ExplodeCast::class,
        'names_scientific' => \App\Casts\ExplodeCast::class,
        'disease_groups_ids' => \App\Casts\IDStringCast::class,
        'diseases_complication_ids' => \App\Casts\IDStringCast::class,
        'body_system_ids' => \App\Casts\IDStringCast::class,
	];

	protected $fillable = [
		'names',
		'names_scientific',
		'body_system_ids',
		'disease_groups_ids',
		'diseases_complication_ids',
		'wordstat_queries',
		'wordstat_query_names',
		'article_main_id'
	];

    public function bodySystems()
    {
        return BodySystem::whereIn('id', $this->body_system_ids);
    }

    public function diseaseGroups()
    {
        return DiseaseGroup::whereIn('id', $this->disease_groups_ids);
    }

    public function diseaseComplication()
    {
        return Disease::whereIn('id', $this->diseases_complication_ids);
    }

    public function articleMain()
    {
        return $this->hasOne(Article::class, 'id', 'article_main_id');
    }

    public function articles()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Article::where('disease_ids', 'like', '%_' . $this->id . '_%');
    }
}
