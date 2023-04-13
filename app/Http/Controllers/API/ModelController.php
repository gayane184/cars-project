<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Mark;
use App\Models\Model;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $models = Model::query()->with('mark')->get();

        $marks = Mark::all();

        return response()->json(['models' => $models, 'marks' => $marks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $values = $request->only('name', 'mark_id');

        $validate = Validator::make($values, [
            'name' => 'required|max:255',
            'mark_id' => 'required|exists:marks,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        Model::query()->create($values);

        return response()->json([
            'success' => true,
            'message' => 'Model created successfully.'
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
        $model = Model::query()->findOrFail($id);

        $values = $request->only('name', 'mark_id');

        $validate = Validator::make($values, [
            'name' => 'required|max:255',
            'mark_id' => 'required|exists:marks,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $model->update($values);

        return response()->json([
            'success' => true,
            'message' => 'Model updated successfully.'
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
        $model = Model::query()->findOrFail($id);

        $cars = Car::query()->where('model_id', $id)->get();

        foreach ($cars as $car) {
            $fileService->delete($car->thumbnail()->first());
        }

        $model->delete();

        return response()->json([
            'success' => true,
            'message' => 'Model deleted successfully.'
        ]);
    }
}
