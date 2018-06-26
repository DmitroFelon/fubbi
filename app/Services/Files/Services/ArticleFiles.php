<?php

namespace App\Services\Files\Services;

use App\Models\Article;
use App\Models\Traits\ResponseMessage;
use App\Services\Article\ArticleRepository;
use App\Services\Files\Interfaces\ArticleFilesInterface;
use App\Services\Google\Drive;
use Chumper\Zipper\Zipper;

// TODO: Сделать скачивание медиа у статей, если есть.
class ArticleFiles extends Files implements ArticleFilesInterface
{
    use ResponseMessage;

    /**
     * @var Drive
     */
    protected $drive;

    /**
     * @var Zipper
     */
    protected $zipper;

    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * ArticleFiles constructor.
     * @param Drive $drive
     * @param Zipper $zipper
     * @param FileRepository $fileRepository
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        Drive $drive,
        Zipper $zipper,
        FileRepository $fileRepository,
        ArticleRepository $articleRepository
    )
    {
        $this->drive = $drive;
        $this->zipper = $zipper;
        $this->articleRepository = $articleRepository;
        parent::__construct($fileRepository);
    }

    /**
     * @param Article $article
     * @param array $params
     * @return mixed
     */
    public function prepareExport(Article $article, array $params)
    {
        try {
            $data['success']  = 1;
            $type             = $this->type($params);
            $media            = $article->export($type);
            $data['name']     = $article->title . '.' . $this->drive->getExtension($type);
            if (stripos($data['name'], '/') || stripos($data['name'], '\\')) {
                $data['name'] = rand() . '.' . $this->drive->getExtension($type);
            }
            $data['fullPath'] = $media->getPath();

            return $data;
        } catch (\Exception $e) {
            $data['success'] = 0;
            $data['response'] = $this->make('Some error happened while exporting, try later please.', 'error');

            return $data;
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function prepareBatchExport(array $params)
    {
        $data['success'] = 1;
        $type            = $this->type($params);
        $ids             = array_key_exists('ids', $params) ? $params['ids'] : [];
        $path            = storage_path('app/public/exports/');
        $zipName         = rand() . '.zip';
        $fullPath        = $path . $zipName;
        try {
            $this->zipper->make($fullPath);
            $this->fillArchive($ids, $type);
            $this->zipper->close();
            $data['fullPath'] = $fullPath;

            return $data;
        } catch (\Exception $e) {
            $this->zipper->close();
            $data['success']  = 0;
            $data['response'] = $this->make('Some error happened while exporting, try later please.', 'error');

            return $data;
        }
    }

    /**
     * @param array $request
     * @return mixed|string
     */
    protected function type(array $request)
    {
        return array_key_exists('as', $request)
            ? $request['as']
            : Drive::MS_WORD;
    }

    /**
     * @param array $ids
     * @param string $type
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    protected function fillArchive(array $ids, string $type)
    {
        foreach ($ids as $id) {
            $article = $this->articleRepository->findById($id);
            if (! $article) {
                continue;
            }
            $media = $article->export($type, $this->drive);
            if (! $media) {
                continue;
            }
            $this->zipper->add(
                $media->getPath(),
                $article->title . '.' . $this->drive->getExtension($type)
            );
        }
    }
}