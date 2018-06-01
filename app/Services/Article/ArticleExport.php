<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.05.18
 * Time: 12:31
 */

namespace App\Services\Article;

use App\Services\Google\Drive;
use App\Models\Article;

/**
 * Class ArticleExport
 * @package App\Services\Article
 */
class ArticleExport
{
    /**
     * @param Article $article
     * @param array $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|string|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function singleExport(Article $article, array $request)
    {
        $type = $this->type($request);
        try {
            $media = $article->export($type);
            if (!array_key_exists('show', $request)) {
                return response()->download($media->getPath(), $article->title . '.' . Drive::getExtension($type));
            }
            else {
                return $media->getFullUrl();
            }
        } catch (\Exception $e) {
            if (!array_key_exists('showss', $request)) {
                return redirect()->back()->with('error', _i('Some error happened while exporting, try later please.'));
            } else {
                return response(['error' => 'Some error happened while exporting, try later please.'], 400);
            }
        }
    }

    /**
     * @param array $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function batchExport(array $request)
    {
        $type = $this->type($request);
        $ids = array_key_exists('ids', $request) ? $request['ids'] : [];
        $path      = storage_path('app/public/exports/');
        $zipper    = new \Chumper\Zipper\Zipper;
        $zip_name  = rand() . '.zip';
        $full_path = $path . $zip_name;
        try {
            $zipper->make($full_path);
        } catch (\Exception $e) {
            return response(['error' => 'Some error happened while exporting, try later please.'], 400);
        }
        $drive = new Drive();
        try {
            foreach ($ids as $id) {
                $article = Article::find($id);
                if (!$article) {
                    continue;
                }
                $media = $article->export($type, $drive);
                if (!$media) {
                    continue;
                }
                $zipper->add(
                    $media->getPath(),
                    $article->title . '.' . Drive::getExtension($type)
                );
            }
            $zipper->close();
            return response()->download($full_path);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', _i($e->getMessage()));
        } finally {
            $zipper->close();
        }
    }

    /**
     * @param array $request
     * @return mixed|string
     */
    public function type(array $request)
    {
        return array_key_exists('as', $request) ? $request['as'] : Drive::MS_WORD;
    }
}