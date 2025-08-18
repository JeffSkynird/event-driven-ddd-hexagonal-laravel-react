<?php

namespace App\Purchases\UI\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Purchases\Domain\DTOs\GenericResponseObject;
use App\Purchases\Domain\Services\PurchaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    /*
    * Controller to get all purchases
    * @param Request $request (perPage)
    * @return JsonResponse
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getAllPurchases(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 5);
            $purchases = $this->purchaseService->getAllPurchases($perPage);
            $response = new GenericResponseObject(
                __('messages.success'),
                __('messages.data_retrieved_successfully'),
                $purchases
            );
            return response()->json($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = new GenericResponseObject(
                __('messages.error'),
                __('messages.data_retrieval_error', [
                    'entity' => 'recipes',           
                    'message' => $e->getMessage(),   
                ]),
                null
            );
            return response()->json($response, 500);
        }
    }
}
