<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleClass extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function studentModuleClasses(): HasMany
    {
        return $this->hasMany(StudentModuleClass::class);
    }

    /**
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * @return BelongsTo
     */
    public  function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
