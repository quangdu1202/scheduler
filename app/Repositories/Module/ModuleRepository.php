<?php

namespace App\Repositories\Module;

use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;
use App\Models\Module\Module;
use App\Repositories\Module\Contracts\ModuleRepositoryInterface;

/**
 * Class ModuleRepository
 */
class ModuleRepository extends BaseRepository implements ModuleRepositoryInterface
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Module::class;
    }
}
