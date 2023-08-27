<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contributors = Contributor::paginate(10);

        return response()->json($contributors, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $collection_id)
    {
        $data = $request->validate([
            'user_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);
        
        // Перевіряємо існування збору з указаним $collection_id
        $collection = Collection::findOrFail($collection_id);
        
        if ($collection){
            $data['collection_id'] = $collection_id;
            $contributor = Contributor::create($data);
            $contributor->save();
            
            return response()->json($contributor, Response::HTTP_CREATED);
        } else {
            return response(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor)
    {
        return response()->json($contributor, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contributor $contributor)
    {
        $data = $request->validate([
            'user_name' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
        ]);

        $contributor->update($data);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor)
    {
        $contributor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
