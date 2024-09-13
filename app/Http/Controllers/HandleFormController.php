<?php

namespace App\Http\Controllers;

use App\Http\Requests\HandleFormRequest;
use App\Services\Zoho\Records;
use Exception;

class HandleFormController extends Controller
{
    public function __invoke(HandleFormRequest $request)
    {
        $validated = $request->validated();

        try {
            $validated = $request->validated();
            
            $account = (new Records('Accounts'))->insert($validated['account']);

            $validated['deal']['Account_Name']['id'] = 
                $account['details']['id'];
            $validated['deal']['Account_Name']['name'] = 
                $validated['account']['Account_Name'];

            (new Records('Deals'))->insert($validated['deal']);

            return response(['message' => 'Successfully created.']);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage(),], 400);
        }
    }
}
