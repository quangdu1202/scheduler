<?php

namespace App\Models\PracticeClass;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\Module\Module;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Registration\Registration;
use App\Models\Teacher\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PracticeClass
 *
 * @property integer $id
 * @property string $practice_class_name
 * @property Carbon $schedule_date
 * @property integer $session
 * @property integer $module_id
 * @property integer $practice_room_id
 * @property integer $teacher_id
 * @property integer $recurring_id
 * @property integer $recurring_interval
 * @property integer $recurring_order
 * @property integer $registered_qty
 * @property integer $max_qty
 * @property integer $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Module $module
 * @property Teacher $teacher
 * @property PracticeRoom $practiceRoom
 * @property Registration $registrations
 */
class PracticeClass extends BaseModel
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'practice_class_name',
        'schedule_date',
        'session',
        'module_id',
        'practice_room_id',
        'teacher_id',
        'recurring_order',
        'recurring_id',
        'recurring_interval',
        'registered_qty',
        'max_qty',
        'status',
        'created_at',
        'updated_at'
    ];

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
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function practiceRoom(): BelongsTo
    {
        return $this->belongsTo(PracticeRoom::class);
    }

    /**
     * @return HasMany
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
