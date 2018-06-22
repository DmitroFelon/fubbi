<?php

namespace App\Services\Files\Interfaces;

/**
 * Interface FileInterface
 * @package App\Services\Files\Interfaces
 */
interface FileInterface
{
    /**
     * @param array $files
     * @param $fileReceiver
     * @param string $collection
     * @return mixed
     */
    public function store(array $files, $fileReceiver, string $collection);

    /**
     * @param $filesOwner
     * @param string $collection
     * @return mixed
     */
    public function get($filesOwner, string $collection);

    /**
     * @param $fileOwner
     * @param string $fileId
     * @return mixed
     */
    public function delete($fileOwner, string $fileId);
}