<?php

namespace App\Providers;

use App\Repositories\Module\Contracts\ModuleRepositoryInterface;
use App\Repositories\Module\ModuleRepository;
use App\Repositories\ModuleClass\Contracts\ModuleClassRepositoryInterface;
use App\Repositories\ModuleClass\ModuleClassRepository;
use App\Repositories\PracticeClass\Contracts\PracticeClassRepositoryInterface;
use App\Repositories\PracticeClass\PracticeClassRepository;
use App\Repositories\PracticeRoom\Contracts\PracticeRoomRepositoryInterface;
use App\Repositories\PracticeRoom\PracticeRoomRepository;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\Module\ModuleService;
use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Services\ModuleClass\ModuleClassService;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeClass\PracticeClassService;
use App\Services\PracticeRoom\Contracts\PracticeRoomServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $this->app->singleton(PostRepositoryInterface::class, PostRepository::class);
//        $this->app->singleton(PostServiceInterface::class, PostService::class);
        $this->app->singleton(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->singleton(ModuleServiceInterface::class, ModuleService::class);
        $this->app->singleton(PracticeRoomRepositoryInterface::class, PracticeRoomRepository::class);
        $this->app->singleton(PracticeRoomServiceInterface::class, PracticeRoomService::class);
        $this->app->singleton(ModuleClassRepositoryInterface::class, ModuleClassRepository::class);
        $this->app->singleton(ModuleClassServiceInterface::class, ModuleClassService::class);
        $this->app->singleton(PracticeClassRepositoryInterface::class, PracticeClassRepository::class);
        $this->app->singleton(PracticeClassServiceInterface::class, PracticeClassService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
