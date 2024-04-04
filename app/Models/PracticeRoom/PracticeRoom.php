<?php

namespace App\Models\PracticeRoom;

use App\Models\PracticeClass\PracticeClass;
use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PracticeRoom
 *
 * @property integer $id
 * @property string $name
 * @property string $location
 * @property integer $pc_qty
 * @property boolean $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property PracticeClass $practiceClasses
 */
class PracticeRoom extends BaseModel
{
    use HasFactory;

    /**
	 * @var array
	 */
	protected $fillable = [
		'name',
		'location',
		'pc_qty',
		'status',
		'created_at',
		'updated_at'
	];

    /**
     * @return HasMany
     */
    public function practiceClasses(): HasMany
    {
        return $this->hasMany(PracticeClass::class);
    }
}
