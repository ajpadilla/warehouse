<?php

namespace App\Http\Controllers;

use App\Repositories\PurchaseRepository;
use App\Services\PurchaseClientService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    public function index(Request $request)
    {
        try {
            $purchases = $this->purchaseRepository->search([])->get();
            return response()->json($purchases);
        } catch (Exception $e) {
            logger('Ingredients error');
            logger($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string',
                'product_name' => 'required|string',
                'quantity' => 'required|integer',
            ]);
            $data = $request->json()->all();

            $purchase = $this->purchaseRepository->create($data);

            return response()->json($purchase);
        }
        catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
