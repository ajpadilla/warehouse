<?php

namespace App\Http\Controllers;

use App\Repositories\IngredientRepository;
use App\Services\PurchaseClientService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class IngredientController extends Controller
{

    /**
     * @var IngredientRepository
     */
    private $ingredientRepository;

    /**
     * @var PurchaseClientService
     */
    private $clientService;

    /**
     * @param IngredientRepository $ingredientRepository
     * @param PurchaseClientService $clientService
     */
    public function __construct(IngredientRepository $ingredientRepository, PurchaseClientService $clientService)
    {
        $this->ingredientRepository = $ingredientRepository;
        $this->clientService = $clientService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $ingredients = $this->ingredientRepository->search([])->get();
            return response()->json($ingredients);
        } catch (Exception $e) {
            logger('Ingredients error');
            logger($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request, $name)
    {
        try {
            $ingredient = $ingredient = $this->ingredientRepository->getByName($name);
            return response()->json($ingredient);
        } catch (Exception $e) {
            logger('Ingredients error');
            logger($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listIngredients(Request $request)
    {
        try {
            $data = $request->json()->all();
            $ingredients = $this->ingredientRepository->search(['name' => $data['name']])->get();
            return response()->json($ingredients);
        } catch (Exception $e) {
            logger('Ingredients error');
            logger($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function increase(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'quantity' => 'required|integer',
            ]);
            $data = $request->json()->all();

            $ingredient = $this->ingredientRepository->getByName($data['name']);

            $quantity = $ingredient->quantity + $data['quantity'];

            $ingredients = $this->ingredientRepository->update($ingredient, ['quantity' => $quantity]);

            return response()->json($ingredients);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function decrease(Request $request)
    {
        try {
            $requestData = $request->json()->all();
            $ingredientsFromRequest = $requestData['ingredients'];

            $ingredientNames = array_column($ingredientsFromRequest, 'name');
            $ingredients = $this->ingredientRepository->search(['name' => $ingredientNames])->get();

            foreach ($ingredients as $ingredient) {
                if ($quantity = $this->ingredientRepository->getIngredientByName($ingredientsFromRequest, $ingredient->name)) {
                    $quantityToUpdate = $ingredient->quantity - $quantity;
                    $this->ingredientRepository->update($ingredient, ['quantity' => $quantityToUpdate]);
                }
            }
        }catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function buyIngredientInWareHouse($ingredient)
    {
        try {
            $quantitySold = $this->clientService->createRequest($ingredient);
            return response()->json($quantitySold);
        } catch (GuzzleException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
