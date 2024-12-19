<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("total User",User::count()),
            Stat::make("total Admins",User::where("role",User::ROLE_ADMIN)->count()),
            Stat::make("total Editors",User::where("role",User::ROLE_EDITOR)->count()),
        ];
    }
}
