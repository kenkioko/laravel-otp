<?php

namespace Kenkioko\OTP\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OTP extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'otps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'token', 'validity'
    ];

    /**
     * The OTP owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @param \App\User $identifier
     * @param int $digits
     * @param int $validity
     * @return mixed
     */
    public function generate(User $identifier, int $digits = 4, int $validity = 10) : object
    {
        $this->get_saved_otp($identifier)->where('valid', true)->delete();

        $token = str_pad($this->generatePin(), 4, '0', STR_PAD_LEFT);

        if ($digits == 5)
            $token = str_pad($this->generatePin(5), 5, '0', STR_PAD_LEFT);

        if ($digits == 6)
            $token = str_pad($this->generatePin(6), 6, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($identifier, $token, $validity) {
           $otp_new = new self([
              'token' => $token,
              'validity' => $validity
          ]);

          $otp_new->user()->associate($identifier);
          $otp_new->save();
        });

        return (object)[
            'status' => true,
            'token' => $token,
            'message' => 'OTP generated'
        ];
    }

    /**
     * @param \App\User $identifier
     * @param string $token
     * @return mixed
     */
    public function validate(User $identifier, string $token) : object
    {
        $otp = $this->get_saved_otp($identifier)->where('token', $token)->first();

        if ($otp == null) {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        } else {
            if ($otp->valid == true) {
                $carbon = new Carbon;
                $now = $carbon->now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                if (strtotime($validity) < strtotime($now)) {
                    $otp->valid = false;
                    $otp->save();

                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                } else {
                    $otp->valid = false;
                    $otp->save();

                    return (object)[
                        'status' => true,
                        'message' => 'OTP is valid'
                    ];
                }
            } else {
                return (object)[
                    'status' => false,
                    'message' => 'OTP is not valid'
                ];
            }
        }
    }

    /**
     * @param int $digits
     * @return string
     */
    private function generatePin($digits = 4)
    {
        $i = 0;
        $pin = "";

        while ($i < $digits) {
            $pin .= mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }

    /**
     * @param \App\User $identifier
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function get_saved_otp($identifier)
    {
        return self::whereHas('user', function (Builder $query) use ($identifier) {
          $query->where('user_id', $identifier->id);
        });
    }
}
