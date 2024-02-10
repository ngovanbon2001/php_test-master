<?php

namespace App\Constants;

class CommonConstant
{
    const ACTIVE_STATUS    = 1;
    const UN_ACTIVE_STATUS = 0;
    const TWO_DAYS         = 2;
    const PER_PAGE_LIMIT   = 10;
    const PENDING_STATUS   = 2;
    const SUCCESS_STATUS   = 1;
    const FAIL_STATUS      = 0;
    const USER_TYPE        = "user";
    const ADMIN_TYPE       = "admin";

    const RESTAURANT = [
        "mc-donalds" => "Mc Donalds",
        "taco-bell" => "Taco Bell",
        "bbq-hut" => "BBQ Hut",
        "vege-deli" => "Vege Deli",
        "pizzeria" => "Pizzeria",
        "panda-express" => "Panda Express",
        "olive-garden" => "Olive Garden",
    ];
}
