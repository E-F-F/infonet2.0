<?php

namespace Modules\IMS\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\IMS\Models\IMSStock;
use Modules\IMS\Transformers\Marketing\IMSMarketingStockResource;

class IMSStockMarketingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branchId = 1;

        $flattened = collect();

        $stocks = IMSStock::with([
            'variants.batches.quantities' => fn($q) => $q->where('branch_id', $branchId)
        ])->get();

        foreach ($stocks as $stock) {
            foreach ($stock->variants as $variant) {
                foreach ($variant->batches as $batch) {
                    foreach ($batch->quantities as $quantity) {
                        $flattened->push(new IMSMarketingStockResource((object) [
                            'stock' => $stock,
                            'variant' => $variant,
                            'batch' => $batch,
                            'quantity' => $quantity,
                        ]));
                    }
                }
            }
        }
        return response()->json($flattened);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ims::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('ims::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('ims::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
