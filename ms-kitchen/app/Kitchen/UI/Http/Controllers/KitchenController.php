<?php

namespace App\Kitchen\UI\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Kitchen\Application\Commands\PrepareDishCommand;
use App\Kitchen\Application\Handlers\PrepareDishCommandHandler;
use App\Kitchen\Domain\DTOs\GenericResponseList;
use App\Kitchen\Domain\DTOs\GenericResponseObject;
use App\Kitchen\Domain\Services\KitchenService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Controller class to manage the kitchen operations
 * Class KitchenController
 * @package App\Kitchen\UI\Http\Controllers
 */
class KitchenController extends Controller
{
    private KitchenService $kitchenService;
    public function __construct(KitchenService $kitchenService)
    {
        $this->kitchenService = $kitchenService;
    }
    /*
    * Controller to prepare a dish
    * @param Request $request (order_id)
    * @return JsonResponse
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function prepareDish(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            // Initialize the command and handler to prepare the dish
            $command = new PrepareDishCommand($orderId);
            $this->kitchenService->isOrderAllowedToRetry($orderId);
            $handler = app(PrepareDishCommandHandler::class);
            $handler->handle($command);

            $response = new GenericResponseObject(
                __('messages.success'),
                __('messages.order_prepared'),
                null
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /*
    * Controller to get recipes
    * @param Request $request
    * @return JsonResponse (perPage)
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getRecipesPaginated(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('perPage', 5);
            // Call the service to get the recipes paginated
            $recipes = $this->kitchenService->getRecipesPaginated($perPage);
            $response = new GenericResponseObject(
                __('messages.success'),
                __('messages.data_retrieved_successfully'),
                $recipes
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
    * Controller to get orders
    * @return JsonResponse
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getOrders()
    {
        try {
            // Call the service to get all orders
            $orders = $this->kitchenService->getOrders();
            $response = new GenericResponseObject(
                __('messages.success'),
                __('messages.data_retrieved_successfully'),
                $orders
            );
            return response()->json($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = new GenericResponseObject(
                __('messages.error'),
                __('messages.data_retrieval_error', [
                    'entity' => 'orders',           
                    'message' => $e->getMessage(),  
                ]),
                null
            );
            return response()->json($response, 500);
        }
    }
    /*
    * Controller to get orders paginated
    * @param Request $request (perPage)
    * @return JsonResponse
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getOrdersPaginated(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 5);
            // Call the service to get orders paginated
            $orders = $this->kitchenService->getOrdersPaginated($perPage);
            $response = new GenericResponseObject(
                __('messages.success'),
                __('messages.data_retrieved_successfully'),
                $orders
            );
    
            return response()->json($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = new GenericResponseObject(
                __('messages.error'),
                __('messages.data_retrieval_error', [
                    'entity' => 'orders',          
                    'message' => $e->getMessage(),   
                ]),
                null
            );
            return response()->json($response, 500);
        }
    }
}
