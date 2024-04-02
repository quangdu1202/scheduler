<?php

namespace App\Providers;

use App\Repositories\Module\Contracts\ModuleRepositoryInterface;
use App\Repositories\Module\ModuleRepository;
use App\Repositories\PracticeRoom\Contracts\PracticeRoomRepositoryInterface;
use App\Repositories\PracticeRoom\PracticeRoomRepository;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\Module\ModuleService;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
