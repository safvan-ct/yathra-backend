<?php
namespace App\Providers;

use App\Repositories\Eloquent\BusRepository;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\Eloquent\DistrictRepository;
use App\Repositories\Eloquent\OperatorRepository;
use App\Repositories\Eloquent\RewardRepository;
use App\Repositories\Eloquent\RouteNodeRepository;
use App\Repositories\Eloquent\StaffRepository;
use App\Repositories\Eloquent\StateRepository;
use App\Repositories\Eloquent\StationRepository;
use App\Repositories\Eloquent\SuggestionRepository;
use App\Repositories\Eloquent\TransitRouteRepository;
use App\Repositories\Eloquent\TripRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\BusRepositoryInterface;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\Interfaces\OperatorRepositoryInterface;
use App\Repositories\Interfaces\RewardRepositoryInterface;
use App\Repositories\Interfaces\RouteNodeRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use App\Repositories\Interfaces\StateRepositoryInterface;
use App\Repositories\Interfaces\StationRepositoryInterface;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use App\Repositories\Interfaces\TransitRouteRepositoryInterface;
use App\Repositories\Interfaces\TripRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(StationRepositoryInterface::class, StationRepository::class);
        $this->app->bind(TransitRouteRepositoryInterface::class, TransitRouteRepository::class);
        $this->app->bind(RouteNodeRepositoryInterface::class, RouteNodeRepository::class);
        $this->app->bind(OperatorRepositoryInterface::class, OperatorRepository::class);
        $this->app->bind(BusRepositoryInterface::class, BusRepository::class);
        $this->app->bind(TripRepositoryInterface::class, TripRepository::class);
        $this->app->bind(SuggestionRepositoryInterface::class, SuggestionRepository::class);
        $this->app->bind(RewardRepositoryInterface::class, RewardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
