<?php

namespace App\Providers;

use App\Repositories\MarkType\Contracts\MarkTypeRepositoryInterface;
use App\Repositories\MarkType\MarkTypeRepository;
use App\Repositories\Module\Contracts\ModuleRepositoryInterface;
use App\Repositories\Module\ModuleRepository;
use App\Repositories\ModuleClass\Contracts\ModuleClassRepositoryInterface;
use App\Repositories\ModuleClass\ModuleClassRepository;
use App\Repositories\PracticeClass\Contracts\PracticeClassRepositoryInterface;
use App\Repositories\PracticeClass\PracticeClassRepository;
use App\Repositories\PracticeRoom\Contracts\PracticeRoomRepositoryInterface;
use App\Repositories\PracticeRoom\PracticeRoomRepository;
use App\Repositories\Registration\Contracts\RegistrationRepositoryInterface;
use App\Repositories\Registration\RegistrationRepository;
use App\Repositories\Student\Contracts\StudentRepositoryInterface;
use App\Repositories\Student\StudentRepository;
use App\Repositories\StudentMark\Contracts\StudentMarkRepositoryInterface;
use App\Repositories\StudentMark\StudentMarkRepository;
use App\Repositories\StudentModuleClass\Contracts\StudentModuleClassRepositoryInterface;
use App\Repositories\StudentModuleClass\StudentModuleClassRepository;
use App\Repositories\Teacher\Contracts\TeacherRepositoryInterface;
use App\Repositories\Teacher\TeacherRepository;
use App\Services\MarkType\Contracts\MarkTypeServiceInterface;
use App\Services\MarkType\MarkTypeService;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\Module\ModuleService;
use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Services\ModuleClass\ModuleClassService;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeClass\PracticeClassService;
use App\Services\PracticeRoom\Contracts\PracticeRoomServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use App\Services\Registration\Contracts\RegistrationServiceInterface;
use App\Services\Registration\RegistrationService;
use App\Services\Student\Contracts\StudentServiceInterface;
use App\Services\Student\StudentService;
use App\Services\StudentMark\Contracts\StudentMarkServiceInterface;
use App\Services\StudentMark\StudentMarkService;
use App\Services\StudentModuleClass\Contracts\StudentModuleClassServiceInterface;
use App\Services\StudentModuleClass\StudentModuleClassService;
use App\Services\Teacher\Contracts\TeacherServiceInterface;
use App\Services\Teacher\TeacherService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->singleton(ModuleServiceInterface::class, ModuleService::class);
        $this->app->singleton(PracticeRoomRepositoryInterface::class, PracticeRoomRepository::class);
        $this->app->singleton(PracticeRoomServiceInterface::class, PracticeRoomService::class);
        $this->app->singleton(ModuleClassRepositoryInterface::class, ModuleClassRepository::class);
        $this->app->singleton(ModuleClassServiceInterface::class, ModuleClassService::class);
        $this->app->singleton(PracticeClassRepositoryInterface::class, PracticeClassRepository::class);
        $this->app->singleton(PracticeClassServiceInterface::class, PracticeClassService::class);
        $this->app->singleton(RegistrationRepositoryInterface::class, RegistrationRepository::class);
        $this->app->singleton(RegistrationServiceInterface::class, RegistrationService::class);
        $this->app->singleton(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->singleton(TeacherServiceInterface::class, TeacherService::class);
        $this->app->singleton(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->singleton(StudentServiceInterface::class, StudentService::class);
        $this->app->singleton(MarkTypeRepositoryInterface::class, MarkTypeRepository::class);
        $this->app->singleton(MarkTypeServiceInterface::class, MarkTypeService::class);
        $this->app->singleton(StudentMarkRepositoryInterface::class, StudentMarkRepository::class);
        $this->app->singleton(StudentMarkServiceInterface::class, StudentMarkService::class);
        $this->app->singleton(StudentModuleClassRepositoryInterface::class, StudentModuleClassRepository::class);
        $this->app->singleton(StudentModuleClassServiceInterface::class, StudentModuleClassService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
