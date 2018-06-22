<?php

namespace App\Services\Files\Services;

use App\Services\Files\Interfaces\FileInterface;

/**
 * Class Files
 * @package App\Services\Files\Services
 */
class Files implements FileInterface
{
    /**
     * @var FileRepository
     */
    protected $fileRepository;

    /**
     * Files constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param array $files
     * @param $fileReceiver
     * @param string $collection
     * @return array
     */
    public function store(array $files, $fileReceiver, string $collection)
    {
        $array = [];
        foreach ($files as $file) {
            array_push($array, $this->uploadMedia($fileReceiver, $file, $collection));
        }

        return $array;
    }

    /**
     * @param $filesOwner
     * @param string $collection
     * @return mixed
     */
    public function get($filesOwner, string $collection)
    {
        return $this->fileRepository->all($filesOwner, $collection)->toArray();
    }

    /**
     * @param $fileOwner
     * @param string $fileId
     */
    public function delete($fileOwner, string $fileId)
    {
        $fileOwner->media()->findOrFail($fileId)->delete();
    }

    /**
     * @param $fileReceiver
     * @param $file
     * @param $collection
     * @return mixed
     */
    protected function uploadMedia($fileReceiver, $file, $collection)
    {
        $media = $fileReceiver->addMedia($file)->toMediaCollection($collection);
        $media->url = $fileReceiver->prepareMediaConversion($media);

        return $media;
    }
}