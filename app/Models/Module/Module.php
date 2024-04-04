<?php

namespace App\Models\Module;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\ModuleClass\ModuleClass;
use App\Models\PracticeClass\PracticeClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Module
 *
 * @property integer $id
 * @property string $module_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ModuleClass $moduleClasses
 * @property PracticeClass $practiceClasses
 */
class Module extends BaseModel
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['module_name', 'created_at', 'updated_at'];

    /**
     * @return HasMany
     */
    public function moduleClasses(): HasMany
    {
        return $this->hasMany(ModuleClass::class);
    }

    /**
     * @return HasMany
     */
    public function practiceClasses(): HasMany
    {
        return $this->hasMany(PracticeClass::class);
    }
}
