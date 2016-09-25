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

/*Group all requests to the encoder and decoder through a sanity check filter */

$app->group(['middleware' => 'sanitycheck'], function () use ($app) {

    $app->get('/encode/{num}', function ($num) use ($app) {
        
        $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_RANDOM'));

        $encoded = $optimus->encode($num);

        $length = strlen((string)$encoded);
        
        $diff = null;

        $return = null;

        $pad_result = env('PRIMATE_PAD_RESULT');

        $result_length = env('PRIMATE_RESULT_LENGTH');

        /* if the setting is to pad to a lenght and the encoded number is less than desired lenght. Pad it*/

        if ($pad_result && $length < $result_length)
        {

            $diff = $result_length - $length;

            /* Pad only where the difference is a positive number*/

            if ($diff > 0)
                {

                    for ($i = 1; $i <= $diff; $i++) {
                            $encoded = '0'.$encoded;
                        }
                }
                
            $return = $encoded;
        
        } else {

            $return = $encoded;


        }

    if ($return)
    {
        
        return response()->json(['status_code' => 200, 'result' =>  $return], 200);

    } else {

        return response()->json(['status_code' => 500, 'error' => 'Could not encode the number'], 500);

    }

    });

    

    $app->get('/decode/{num}', function ($num) use ($app) {
        
        $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_RANDOM'));

        $decoded = $optimus->decode($num);

        if (!$decoded || $decoded == 0)
        {

            return response()->json(['status_code' => 500, 'error' => 'Could not decode the hash'], 500);

        } else {

            return response()->json(['status_code' => 200, 'result' =>  $decoded], 200);

        } 
    });

});
