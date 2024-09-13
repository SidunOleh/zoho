<?php

namespace App\Http\Controllers;

use App\Services\Zoho\OAuth;
use Exception;
use Illuminate\Http\Request;

class GetTokenController extends Controller
{
    public function __invoke(Request $request)
    {        
        try {
            (new OAuth)->accessToken($request->input('code'));

            return response(['message' => 'OK',]);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage(),], 400);
        }
    }
}
