<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Collection;
use App\Models\Contributor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Collection::query();

        if ($request->has('filter') && in_array($request->input('filter'), ['less', 'greater', 'unfinished'])) {
            $operation = $request->input('filter');
            $amount = $request->input('amount');

            // Перевіряємо чи сума внесків менша за цільову суму
            if (in_array($operation, ['less', 'greater', 'unfinished'])){
                $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM contributors WHERE contributors.collection_id = collections.id) < target_amount');
            }
            
            // Перевіряємо чи різниця між сумою внесків та цільвою сумою менша\більша за задане число
            if ($operation === 'less') {
                $query->WhereRaw('target_amount - (SELECT COALESCE(SUM(amount), 0) FROM contributors WHERE contributors.collection_id = collections.id) < ?', [$amount]);
            } elseif ($operation === 'greater') {
                $query->WhereRaw('target_amount - (SELECT COALESCE(SUM(amount), 0) FROM contributors WHERE contributors.collection_id = collections.id) > ?', [$amount]);
            }
        }

        $collections = $query->paginate(10);

        return response()->json($collections, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:4294967295',
            'target_amount' => 'required|numeric',
            'link' => 'required|url',
        ]);

        $data['created_at'] = Carbon::now();
        $collection = Collection::create($data);
        $collection->save();

        return response()->json($collection, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        $contributors = $collection->contributors;

        $contributions = [];
        // Записуємо усі пов'язані внески у масив який буде додано до інформації про збори
        foreach($contributors as $contributor){
            $contributions[] = [
                'user_name' => $contributor->user_name, 
                'amount' => $contributor->amount,
            ];
        }

        $response = [
            'title' => $collection->title,
            'description' => $collection->description,
            'target_amount' => $collection->target_amount,
            'link' => $collection->link,
            'contributions' => $contributions,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:4294967295',
            'target_amount' => 'sometimes|numeric',
            'link' => 'sometimes|url',
        ]);

        $collection->update($data);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $collection->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
