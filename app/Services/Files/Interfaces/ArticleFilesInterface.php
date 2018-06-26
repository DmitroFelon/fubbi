<?php

namespace App\Services\Files\Interfaces;
use App\Models\Article;

/**
 * Interface ArticleFilesInterface
 * @package App\Services\Files\Interfaces
 */
interface ArticleFilesInterface extends FileInterface
{
    /**
     * @param Article $article
     * @param array $params
     * @return mixed
     */
    public function prepareExport(Article $article, array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function prepareBatchExport(array $params);
}