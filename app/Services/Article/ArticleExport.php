<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.05.18
 * Time: 12:31
 */

namespace App\Services\Article;

use Illuminate\Http\Request;
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function singleExport(Article $article, Request $request)
    {
        try {
            $type = $this->type($request);
            $media = $article->export($type);
            return response()->download($media->getPath(), $article->title . '.' . Drive::getExtension($type));
        } catch (\Exception $e) {
            if (!$request->has('show')) {
                return redirect()->back()->with('error', _i('Some error happened while exporting, try later please.'));
            } else {
                return response(['error' => 'Some error happened while exporting, try later please.'], 400);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function batchExport(Request $request)
    {
        $type = $this->type($request);
        $ids = $request->has('ids') ? $request->input('ids') : [];
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
     * @param Request $request
     * @return array|string
     */
    public function type(Request $request)
    {
        $type = ($request->has('as')) ? $request->input('as') : Drive::MS_WORD;
        return $type;
    }

}