<?php

namespace Kenkioko\OTP;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Builder;

class OTP extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'OTP';
    }
}
