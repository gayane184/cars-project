<?php

namespace App\Services;

use Exception;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    protected $validate = true;
    protected $modelId;
    protected $modelType;
    protected $type;
    protected $directory;
    protected $name;

    /**
     * @return FileService
     */
    public function noValidate()
    {
        $this->validate = false;

        return $this;
    }

    /**
     * @param $modelId
     * @return FileService
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * @param $modelType
     * @return FileService
     */
    public function setModelType($modelType)
    {
        $this->modelType = $modelType;

        return $this;
    }

    /**
     * @param $type
     * @return FileService
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $directory
     * @return FileService
     */
    public function setDirectory(string $directory)
    {
        $this->directory = 'public/' . $directory;

        return $this;
    }

    /**
     * @param $name
     * @return FileService
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return Builder|Model
     */
    public function store(UploadedFile $uploadedFile)
    {
        if ($this->validate) {
            if (!$this->modelId) {
                throw new \LogicException('Model ID not set.');
            }

            if (!$this->modelType) {
                throw new \LogicException('Model Type not set.');
            }
        }

        if (!$this->type) {
            throw new \LogicException('Type not set.');
        }

        if (!$this->directory) {
            throw new \LogicException('Directory not set.');
        }

        $name = $this->name ?: $uploadedFile->getClientOriginalName();

        $path = $uploadedFile->store($this->directory);

        $path = str_replace('public/', '', $path);

        return File::query()->create([
            'model_id' => $this->modelId,
            'model_type' => $this->modelType,
            'name' => $name,
            'path' => $path,
            'type' => $this->type
        ]);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $client
     * @return void
     */
    public function update(UploadedFile $uploadedFile, $client)
    {
        if (!$this->directory) {
            throw new \LogicException('Directory not set.');
        }

        $name = $this->name ?: $uploadedFile->getClientOriginalName();

        $relation = $this->type;

        $path = $uploadedFile->store($this->directory);

        $path = str_replace('public/', '', $path);

        $filePath = '/public/' . $client->$relation()->first()->path;

        $client->$relation()->update([
            'name' => $name,
            'path' => $path
        ]);

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    /**
     * @param File $file
     * @throws Exception
     */
    public function delete(File $file)
    {
        $unlinkPath = Storage::path($file['path']);

        if (file_exists($unlinkPath)) {
            unlink($unlinkPath);
        }

        $file->delete();
    }
}
