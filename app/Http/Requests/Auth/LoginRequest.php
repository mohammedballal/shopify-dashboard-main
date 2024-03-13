<?php

namespace App\Http\Requests\Auth;

use App\Events\UserLoginFailed;
use App\Events\UserLoginSuccessful;
use App\Listeners\UserFailedLogin;
use App\Listeners\UserSuccessfulLogin;
use App\Models\IpAddress;
use App\Models\SystemLogin;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        $request_ip = request()->ip();
        $request = request();
        $ip_details = IpAddress::where('ip_address',$request_ip)->first();
        if (empty($ip_details)){
            $ip_details = IpAddress::create(
                ['type'=>'auto','status'=>'1','ip_address'=>$request_ip,
                    'successful_logins'=>0,'un_successful_logins'=>0,'created_at'=>now(),'updated_at'=>now()]);
        }
        if (@$ip_details && $ip_details->status != 0){
            $user = Auth::attempt(['email'=>$this->get("email"), 'password'=>$this->get("password")], $this->boolean('remember'));
            if ($user){
                $user_id = \auth()->user()->id;
                if (Auth::user()->status == 0){
                    Auth::logout();
                    event(new UserLoginFailed(
                        $ip_details->id,
                        $this->get('email'),
                        $this->get('password'),
                        $request->header('Referer'),
                        $request->session()->getId(),
                        $request->userAgent(),
                        $user_id,
                        'User Account is suspended ( In-active )'));
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => __('auth.inactive'),
                    ]);
                }
                event(new UserLoginSuccessful(
                    $ip_details->id,
                    $this->get('email'),
                    $this->get('password'),
                    $request->header('Referer'),
                    ($request->session()->getId()),
                    $request->userAgent(),
                    $user_id
                ));
            }else{
                $user_id = User::where("email",$this->get("email"))->first();
                event(new UserLoginFailed(
                    $ip_details->id,
                    $this->get('email'),
                    $this->get('password'),
                    $request->header('Referer'),
                    ($request->session()->getId()),
                    $request->userAgent(),
                    $user_id->id??null,
                    'User Tried to Login with Invalid Credentials'));
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }
        }else{
            $user_id = User::where("email",$this->get("email"))->first();
            event(new UserLoginFailed(
                $ip_details->id,
                $this->get('email'),
                $this->get('password'),
                $request->header('Referer'),
                ($request->session()->getId()),
                $request->userAgent(),
                $user_id->id??null,
                "User Tried to Login with BLOCKED IP address"));
            throw ValidationException::withMessages([
                'email' => 'Your IP is blocked for Some Reason Contact Our Support Please',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), (env("MAX_LOGIN_ATTEMPTS") ?? 5))) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
