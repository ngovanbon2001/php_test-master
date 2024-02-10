<?php

namespace App\Helpers;

use App\Constants\CommonConstant;
use App\Repositories\TenantRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantHelper
{
    /**
     * @param $tenant
     *
     * @return bool
     */
    public static function switchTenantConnection($tenant): bool
    {
        $switch = false;

        if ($tenant) {
            // Disable connect to current database
            DB::purge('mysql');
            DB::purge('tenant');
            // Set new config by Tenant
            Config::set('database.connections.tenant.database', $tenant['database']);
            DB::connection('tenant')->reconnect();
            DB::setDefaultConnection('tenant');

            Artisan::call('cache:forget spatie.permission.cache');
            Artisan::call('cache:clear');

            $switch = true;
        }

        return $switch;
    }

    /**
     * @return bool
     */
    public function revertSwitchTenantConnection(): bool
    {
        // Disable connect to current database
        DB::purge('mysql');
        DB::purge('tenant');
        // Set new config by Tenant
        Config::set('database.connections.tenant.database', config('chatgpt.database_system'));
        DB::connection('tenant')->reconnect();
        DB::setDefaultConnection('tenant');

        Artisan::call('cache:forget spatie.permission.cache');
        Artisan::call('cache:clear');

        return true;
    }

    /**
     * Make switch tenant connection by condition
     *
     * @param array $condition (['column_name' => 'value']
     * @return bool
     */
    public static function switchTenantConnectionByCondition(array $condition): bool
    {
        $serviceTenant = app(TenantRepository::class)->getByConditions($condition)->first();

        if (empty($serviceTenant)) {
            return false;
        }

        return self::switchTenantConnection($serviceTenant);
    }

    /**
     * @return bool
     */
    public static function checkSystemAdmin(): bool
    {
        $connection      = DB::getDefaultConnection();
        $currentDatabase = DB::connection($connection)->getDatabaseName();

        return $currentDatabase == config('chatgpt.database_system');
    }
}
