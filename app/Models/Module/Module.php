<?php

namespace App\Models\Module;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\ModuleClass\ModuleClass;
use App\Models\PracticeClass\PracticeClass;
use App\Models\Teacher\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

/**
 * Class Module
 *
 * @property integer $id
 * @property string $module_code
 * @property string $module_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Teacher[]|Collection $teachers
 * @property ModuleClass[]|Collection $moduleClasses
 * @property PracticeClass[]|Collection $practiceClasses
 */
class Module extends BaseModel
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['module_code', 'module_name', 'created_at', 'updated_at'];

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

    /**
     * @return BelongsToMany
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'module_classes', 'module_id', 'teacher_id');
    }
}
