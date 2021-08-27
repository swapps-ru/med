<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BlockType
 * 
 * @property int $id
 * @property string|null $description
 * @property string|null $icon_class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Block[] $blocks
 *
 * @package App\Models
 */
class BlockType extends Model
{
	protected $table = 'block_types';

	protected $fillable = [
		'description',
		'icon_class'
	];

    public $timestamps = false;

    public function blocks()
	{
		return $this->hasMany(Block::class, 'type_id');
	}
}
