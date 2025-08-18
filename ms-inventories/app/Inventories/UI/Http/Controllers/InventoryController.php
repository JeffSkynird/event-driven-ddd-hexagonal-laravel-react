<?php

namespace App\Inventories\UI\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Inventories\Domain\DTOs\GenericResponseList;
use App\Inventories\Domain\DTOs\GenericResponseObject;
use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
* Controller class for Inventory
* Class InventoryController
* @package App\Inventories\UI\Http\Controllers
*/
class InventoryController extends Controller
{
    protected $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    /*
    * Controller to get all ingredients paginated
    * @param Request $request (perPage) 
    * @return JsonResponse
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getInventoryPaginated(Request $request)
    {
        try {
            $perpage = $request->input('perPage', 5);
            // Call the service to get all ingredients paginated         
            $ingredients = $this->inventoryRepository->getAllIngredientsPaginated($perpage);
            $response = new GenericResponseObject(
                __('messages.success'),
                __('messages.data_retrieved_successfully'),
                $ingredients
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

    /*
    * Controller to get inventory movements
    * @return JsonResponse
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getInventoryMovements()
    {
        try {
            // Call the service to get inventory movements
            $movements = $this->inventoryRepository->getInventoryMovements();
            return response()->json($movements);
            $response = new GenericResponseList(
                __('messages.success'),
                __('messages.data_retrieved_successfully'),
                $orders
            );
            return response()->json($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = new GenericResponseList(
                __('messages.error'),
                __('messages.data_retrieval_error', [
                    'entity' => 'recipes',           
                    'message' => $e->getMessage(),   
                ]),
                []
            );
            return response()->json($response, 500);
        }
    }
}
