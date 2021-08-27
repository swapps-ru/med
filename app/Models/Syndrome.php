<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Syndrome
 *
 * @property int $id
 * @property string|null $names
 * @property string|null $names_scientific
 * @property array $symptom_include_ids
 * @property int $wordstat_queries
 * @property string|null $wordstat_query_names
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Syndrome extends Model
{
    protected $table = 'syndromes';

    protected $casts = [
        'wordstat_queries' => 'int',

        'names' => \App\Casts\ExplodeCast::class,
        'names_scientific' => \App\Casts\ExplodeCast::class,

        'symptom_include_ids' => \App\Casts\IDStringCast::class,
    ];

    protected $fillable = [
        'names',
        'names_scientific',
        'symptom_include_ids',
        'wordstat_queries',
        'wordstat_query_names'
    ];

    public function articles()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Article::where('syndrome_ids', 'like', '%_' . $this->id . '_%');
    }

    public function symptomsInclude()
    {
        return Symptom::whereIn('id', $this->symptom_include_ids);
    }
}
