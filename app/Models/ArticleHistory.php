<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleHistory
 * 
 * @property int $id
 * @property int $article_id
 * @property int $user_id
 * @property array|null $blocks_json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Article $article
 * @property User $user
 *
 * @package App\Models
 */
class ArticleHistory extends Model
{
	protected $table = 'articles_history';

	protected $casts = [
		'article_id' => 'int',
		'user_id' => 'int',
		'blocks_json' => 'json'
	];

	protected $fillable = [
		'article_id',
		'user_id',
		'blocks_json'
	];

	public function article()
	{
		return $this->belongsTo(Article::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
