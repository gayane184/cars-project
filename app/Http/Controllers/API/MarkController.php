<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Mark;
use App\Services\FileService;
use Exception;
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
     * @param FileService $fileService
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(FileService $fileService, int $id)
    {
        $mark = Mark::query()->findOrFail($id);

        $cars = Car::query()->whereHas('model', function ($q) use ($id) {
            return $q->where('mark_id', $id);
        })->get();

        foreach ($cars as $car) {
            $fileService->delete($car->thumbnail()->first());
        }

        $mark->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mark deleted successfully.'
        ]);
    }
}
