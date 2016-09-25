<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Optimus\Optimus;

class SanityCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
        Add check to ensure that the env file has the right numbers 
        */
        
        $optimus_prime = env('OPTIMUS_PRIME', null);
        $optimus_inverse = env('OPTIMUS_INVERSE', null);
        $optimus_random = env('OPTIMUS_RANDOM', null);
        
        if (is_null($optimus_prime) || is_null($optimus_inverse) || is_null($optimus_random))
        {

                return response()->json(['error' => 'Bad configuration']);
        }

        $optimus = new Optimus($optimus_prime, $optimus_inverse, $optimus_random);

        $encoded = $optimus->encode(20);

        if ($encoded <= 0) {

                return response()->json(['error' => 'Bad prime/random/inverse in configuration']);
        }

        return $next($request);
    }
}
