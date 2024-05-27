<?php

namespace App\Models\Teacher;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\Module\Module;
use App\Models\ModuleClass\ModuleClass;
use App\Models\PracticeClass\PracticeClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Ramsey\Collection\Collection;

/**
 * Class Teacher
 *
 * @property integer $id
 * @property string $department
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Module[]|Collection $modules
 * @property ModuleClass[]|Collection $moduleClasses
 * @property PracticeClass[]|Collection $practiceClasses
 */
class Teacher extends BaseModel
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'department',
        'created_at',
        'updated_at'
    ];

    /**
     * @return MorphOne
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

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
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_classes', 'teacher_id', 'module_id');
    }
}
