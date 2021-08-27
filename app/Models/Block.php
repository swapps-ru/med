<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Block
 * 
 * @property int $id
 * @property int $type_id
 * @property array|null $data_json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property BlockType $block_type
 *
 * @package App\Models
 */
class Block extends Model
{
	protected $table = 'blocks';

	protected $casts = [
		'type_id' => 'int',
		'data_json' => 'json'
	];

	protected $fillable = [
		'type_id',
		'data_json'
	];

	public function blockType()
	{
		return $this->belongsTo(BlockType::class, 'type_id');
	}

    public function articles()
    { //не рекомендуется к частому использованию без приведения к более высоким уровням НФ

        return Article::where('block_ids', 'like', '%_' . $this->id . '_%');
    }
}
