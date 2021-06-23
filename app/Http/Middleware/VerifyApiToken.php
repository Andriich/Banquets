<?php

namespace App\Http\Middleware;

use App\Models\ImperiaUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (App::environment() === 'production') {
            $token = $request->header('api-token');

            $isValid = false;
            if (!empty($token)) {
                $user = ImperiaUser::where('api_token', '=', $token)->first();

                if ($user) {
                    $isValid = true;
                }
            }

            if (!$isValid) {
                return response()->make(
                    [
                        'success' => false,
                        'message' => 'Forbidden.',
                        'errors' => [
                            'Forbidden.'
                        ]
                    ],
                    403
                );
            }
        }

        return $next($request);
    }
}
