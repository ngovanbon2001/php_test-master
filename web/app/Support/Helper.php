<?php

use App\Constants\Common;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

if (!function_exists('handleImage')) {
    function handleImage($fileImage): string
    {
        $imageName = "";

        if ($_FILES['image_url']['name']) {
            $image = $fileImage;
            $imageName = $image->getClientOriginalName();
            $image->move('images',  $imageName);
        }

        return $imageName;
    }
}

if (!function_exists('handleImage')) {
    function handleImage($fileImage): string
    {
        $imageName = "";

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $timeNow = Carbon::now()->format('Y-m-d_H-i-s');
        $path = 'public/images/' . '/' . $currentYear . '/' . $currentMonth;
        Storage::makeDirectory($path);
        
        if (isset($fileImage)) {
            $imageName = $timeNow . '_' . $fileImage->getClientOriginalName();
            $pathFile = $fileImage->storeAs($path, $imageName);
        
            // Đường dẫn đầy đủ đến ảnh gốc trong storage
            $imageFullPath = storage_path('app/' . $pathFile);
        
            // Resize ảnh với kích thước mới (ví dụ: 200x300)
            $resizedImage = Image::make($imageFullPath)->fit(200, 300);
        
            // Lưu ảnh đã resize lại vào storage
            $resizedImagePath = $path . '/' . $imageName;
            $resizedImage->save(storage_path('app/' . $resizedImagePath));
        }
        
        return $imageName ?? null;
    }
}

if (!function_exists('test')) {
    function test($fileImage): string
    {
        return '111122 test';
    }
}

function removeSession($session)
{
    if (Session::has($session)) {
        Session::forget($session);
    }
    return true;
}

function randomString($length, $type = 'token')
{
    if ($type == 'password') {
        $chars =
            "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    } elseif ($type == 'username') {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    } else {
        $chars =
            "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    $token = substr(str_shuffle($chars), 0, $length);
    return $token;
}

function activeRoute($route, $isClass = false): string
{
    $requestUrl = request()->fullUrl() === $route ? true : false;

    if ($isClass) {
        return $requestUrl ? $isClass : '';
    } else {
        return $requestUrl ? 'active' : '';
    }
}

function checkRecordExist($table_list, $column_name, $id)
{
    if (count($table_list) > 0) {
        foreach ($table_list as $table) {
            $check_data = DB::table($table)->where($column_name, $id)->count();
            if ($check_data > 0) {
                return false;
            }
        }
        return true;
    }
    return true;
}

// Model file save to storage by spatie media library
function storeMediaFile($model, $file, $name)
{
    if ($file) {
        $model->clearMediaCollection($name);
        if (is_array($file)) {
            foreach ($file as $value) {
                $model->addMedia($value)->toMediaCollection($name);
            }
        } else {
            $model->addMedia($file)->toMediaCollection($name);
        }
    }
    return true;
}

// Model file get by storage by spatie media library
function getSingleMedia($model, $collection = 'image_icon', $skip = true)
{
    if (!Auth::check() && $skip) {
        return asset('images/avatars/01.png');
    }
    if ($model !== null) {
        $media = $model->getFirstMedia($collection);
    }
    $imgurl = isset($media) ? $media->getPath() : '';
    if (file_exists($imgurl)) {
        return $media->getFullUrl();
    } else {
        switch ($collection) {
            case 'image_icon':
                $media = asset('images/avatars/01.png');
                break;
            case 'profile_image':
                $media = asset('images/avatars/01.png');
                break;
            default:
                $media = asset('images/common/add.png');
                break;
        }
        return $media;
    }
}

// File exist check
function getFileExistsCheck($media)
{
    $mediaCondition = false;
    if ($media) {
        if ($media->disk == 'public') {
            $mediaCondition = file_exists($media->getPath());
        } else {
            $mediaCondition =
                Storage::disk($media->disk)->exists($media->getPath());
        }
    }
    return $mediaCondition;
}

function getFileInfo($path)
{
    $fileInfo = false;

    if ($path) {
        $fileName = File::name($path);
        $name     = substr($fileName, 20);
        $info     = [
            'name' => $name, 'type' => File::extension($path),
            'size' => Storage::size($path),
        ];
        return json_encode($info);
    }
    return $fileInfo;
}

function formatFileSize($bytes)
{
    if ($bytes === 0) {
        return '0 Bytes';
    }

    $sizes         = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    $i             = floor(log($bytes, 1024));
    $formattedSize = number_format($bytes / pow(1024, $i), 2);

    return $formattedSize . ' ' . $sizes[$i];
}

/**
 * @return mixed
 */
function checkRoleAdmin()
{
    # Service role
    $serviceRole = app(RoleService::class);

    # Check if role name has admin rights
    return $serviceRole->checkRoleAdmin(auth()->user());
}

/**
 * @return bool
 */
function checkSystemAdmin(): bool
{
    $connection      = DB::getDefaultConnection();
    $currentDatabase = DB::connection($connection)->getDatabaseName();

    return $currentDatabase == Common::DATABASE_SYSTEM;
}

/**
 * Get user info from token
 */
if (!function_exists('getUserInfoFromToken')) {
    /**
     * @param $request
     *
     * @return string|null
     */
    function getUserInfoFromToken($request): ?array
    {
        $token = explode('.', explode(
            "Bearer",
            $request->header('authorization')
        )[1])[1];

        $userInfo = json_decode(base64_decode($token), true);

        return $userInfo ?? [];
    }
}

/**
 * Get tenant from token
 */
if (!function_exists('getTenant')) {
    /**
     * @param $request
     *
     * @return string|null
     */
    function getTenant($request): ?string
    {
        $userInfo = getUserInfoFromToken($request);

        return $userInfo['tenant'] ?? null;
    }
}

/**
 * Get domain
 */
if (!function_exists('getDomain')) {
    /**
     * @param $request
     *
     * @return string|null
     */
    function getDomain($tenant): ?string
    {
        $domain = $tenant;

        $host = parse_url($tenant);

        if (isset($host['host'])) {
            $domain = $host['host'];
        }

        return $domain ?? null;
    }
}

/**
 * @param $id
 *
 * @return mixed
 */
function getPromptName($id): mixed
{
    $promptService = app(\Modules\Prompt\Services\PromptService::class);

    return $promptService->findById($id);
}


function switchTenantConnection($tenant): bool
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


function revertSwitchTenantConnection(): bool
{
    // Disable connect to current database
    DB::purge('mysql');
    DB::purge('tenant');
    // Set new config by Tenant
    Config::set('database.connections.tenant.database', Common::DATABASE_SYSTEM);
    DB::connection('tenant')->reconnect();
    DB::setDefaultConnection('tenant');

    Artisan::call('cache:forget spatie.permission.cache');
    Artisan::call('cache:clear');

    return true;
}
