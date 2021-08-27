<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * 
 * @property int $id
 * @property string|null $title
 * @property string|null $desc_short
 * @property array $block_ids
 * @property array $disease_ids
 * @property array $syndrome_ids
 * @property array $symptom_ids
 * @property array $aid_ids
 * @property int $views
 * @property int $views_recently
 * @property int $user_id
 * @property int $is_published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|ArticleHistory[] $articles_histories
 *
 * @package App\Models
 */
class Article extends Model
{
	protected $table = 'articles';

	protected $casts = [
		'views' => 'int',
		'views_recently' => 'int',
		'user_id' => 'int',
		'is_published' => 'int',
        'block_ids' => \App\Casts\IDStringCast::class,
        'disease_ids' => \App\Casts\IDStringCast::class,
        'syndrome_ids' => \App\Casts\IDStringCast::class,
        'symptom_ids' => \App\Casts\IDStringCast::class,
        'aid_ids' => \App\Casts\IDStringCast::class,
    ];

	protected $fillable = [
		'title',
		'desc_short',
		'block_ids',
		'disease_ids',
		'syndrome_ids',
		'symptom_ids',
		'aid_ids',
		'views',
		'views_recently',
		'user_id',
		'is_published'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

    public function blocks()
    {
        return Block::whereIn('id', $this->block_ids);
    }

    public function diseases()
    {
        return Disease::whereIn('id', $this->disease_ids);
    }

    public function diseaseMain()
    {
        return $this->hasMany(Disease::class, 'article_main_id');
    }

    public function syndromes()
    {
        return Syndrome::whereIn('id', $this->syndrome_ids);
    }

    public function symptoms()
    {
        return Symptom::whereIn('id', $this->symptom_ids);
    }

    public function symptomMain()
    {
        return $this->hasMany(Symptom::class, 'article_main_id');
    }

    public function aids()
    {
        return Aid::whereIn('id', $this->aid_ids);
    }

	public function articleHistory()
	{
		return $this->hasMany(ArticleHistory::class, 'article_id');
	}
}
