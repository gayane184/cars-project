<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Mark;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $cars = Car::query()->with(['model', 'model.mark', 'thumbnail'])->get();
        $marks = Mark::query()->whereHas('models')->with('models')->get();

        return response()->json([
            'cars' => $cars,
            'marks' => $marks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param FileService $fileService
     * @return JsonResponse
     */
    public function store(Request $request, FileService $fileService)
    {
        $values = $request->only('model_id', 'thumbnail', 'description');

        $validate = Validator::make($values, [
            'model_id' => 'required|exists:models,id',
            'thumbnail' => 'required|mimes:jpg,jpeg,png,svg|max:10000',
            'description' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $car = Car::query()->create([
            'model_id' => $values['model_id'],
            'description' => $values['description']
        ]);

        $fileService
            ->setModelId($car['id'])
            ->setModelType(Car::class)
            ->setType('thumbnail')
            ->setDirectory('thumbnails')
            ->store($values['thumbnail']);

        return response()->json([
            'success' => true,
            'message' => 'Car created successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param FileService $fileService
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, FileService $fileService, int $id)
    {
        $car = Car::query()->findOrFail($id);

        $values = $request->only('model_id', 'thumbnail', 'description');

        $rules = [
            'model_id' => 'required|exists:models,id',
            'description' => 'required'
        ];

        if ($request->hasFile('thumbnail')) {
            $rules['thumbnail'] = 'mimes:jpg,jpeg,png,svg|max:10000';
        }

        $validate = Validator::make($values, $rules);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $car->update([
            'model_id' => $values['model_id'],
            'description' => $values['description']
        ]);

        if ($request->hasFile('thumbnail')) {
            $fileService
                ->setDirectory('thumbnails')
                ->setType('thumbnail')
                ->update($request->file('thumbnail'), $car);
        }

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully.'
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
        $car = Car::query()->findOrFail($id);

        $fileService->delete($car->thumbnail()->first());

        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Car deleted successfully.'
        ]);
    }

    /**
     * Update car status.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(int $id)
    {
        $car = Car::query()->findOrFail($id);

        $car->update(['published' => !$car['published']]);

        return response()->json([
            'success' => true,
            'message' => 'Car ' . ($car->published ? 'published.' : 'hided.')
        ]);
    }
}
