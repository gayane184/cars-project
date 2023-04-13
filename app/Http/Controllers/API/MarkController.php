<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $marks = Mark::all();

        return response()->json(['marks' => $marks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $values = $request->only('name');

        $validate = Validator::make($values, [
            'name' => 'required|max:255'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        Mark::query()->create($values);

        return response()->json([
            'success' => true,
            'message' => 'Mark created successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $mark = Mark::query()->findOrFail($id);

        $values = $request->only('name');

        $validate = Validator::make($values, [
            'name' => 'required|max:255'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $mark->update($values);

        return response()->json([
            'success' => true,
            'message' => 'Mark updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $mark = Mark::query()->findOrFail($id);

        $mark->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mark deleted successfully.'
        ]);
    }
}
