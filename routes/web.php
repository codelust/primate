<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Response;
use Jenssegers\Optimus\Optimus;

$app->get('/', function () use ($app) {
    return $app->version();
});


$app->get('/encode/{num}', function ($num) use ($app) {
    
    $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_RANDOM'));

    $encoded = $optimus->encode($num);
    $length = strlen((string)$encoded);
    $diff= null;



    if ($length < 10)
    {

        $diff = 10 - $length;

         if ($diff)
            {

                for ($i = 1; $i <= $diff; $i++) {
                        $encoded = '0'.$encoded;
                    }
            }
            
        $return = $encoded;
    
    } else {

        $return = $encoded;


    }

   return $return;
    
    //return $num;
    //return response()->json(['error' => 'Unauthorized'], 401, ['X-Header-One' => 'Header Value']);
    //return response()->json(['error' => 'Unauthorized', $num], 200, ['X-Header-One' => 'Header Value']); 
});


//$original = $optimus->decode(1535832388);


$app->get('/decode/{num}', function ($num) use ($app) {
    
    $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_RANDOM'));

    $decoded = $optimus->decode($num);

    return $decoded;

    //return $num;
    //return response()->json(['error' => 'Unauthorized'], 401, ['X-Header-One' => 'Header Value']);
    //return response()->json(['error' => 'Unauthorized', $num], 200, ['X-Header-One' => 'Header Value']); 
});