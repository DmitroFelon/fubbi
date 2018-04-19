<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Role;
use App\Services\Google\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{

    public function __construct()
    {
        /* $this->middleware('can:index,' . Article::class)->only(['index']);
         $this->middleware('can:show,article')->only(['show']);
         $this->middleware('can:update,article')->only(['update', 'edit']);
         $this->middleware('can:create,' . Article::class)->only(['create', 'store']);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $all = true;

        $user = Auth::user();

        if ($user->role == Role::ADMIN) {
            $articles_query = Article::query();
        } elseif ($user->role == Role::CLIENT) {
            $articles_query = $user->relatedClientArticles();
        } else {
            $articles_query = $user->relatedClientArticles();
        }

        if ($request->has('type') and $request->input('type') != '') {
            $articles_query->where('type', $request->input('type'));
        }

        if ($request->has('status') and $request->input('status') != '') {
            $articles_query->where('accepted', intval($request->input('status')));
        }


        if ($request->has('active') and $request->input('active') != '') {
            $articles_query->where('active', true);
        }

        $articles = $articles_query->paginate(10);

        $filters['types'] = Article::getAllTypes();

        $filters['statuses'] = [
            ''    => _i('Select status'),
            true  => _i('Accepted'),
            false => _i('Declined')
        ];

        return view('entity.article.index', compact('articles', 'all', 'filters'));
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view('entity.article.show', compact('article'));
    }

    public function request_access(Article $article, Request $request, Drive $drive)
    {

        $email     = Auth::user()->email;
        $google_id = $article->google_id;

        $permissions = [$email => 'commenter'];
        $drive->addPermission($google_id, $permissions);
        return redirect()->back()->with('success', 'Permissions has been provided');
    }


    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Article $article, Request $request)
    {
        try {
            $as    = ($request->has('as')) ? $request->input('as') : Drive::PDF;
            $media = $article->export($as);

            if (!$request->has('show')) {
                return response()->download($media->getPath(), $article->title . '.' . Drive::getExtension($as));
            } else {
                return $media->getFullUrl();
            }

            //return $media->getFullUrl();

        } catch (\Exception $e) {
            if (!$request->has('show')) {
                return redirect()->back()->with('error', _i('Some error happened while exporting, try later please.'));
            } else {
                return response(['error' => 'Some error happened while exporting, try later please.'], 400);
            }

        }
    }

    public function batch_export(Request $request)
    {
        $as  = ($request->has('as')) ? $request->input('as') : Drive::MS_WORD;
        $ids = $request->has('ids') ? $request->input('ids') : [];

        $path      = storage_path('app/public/exports/');
        $zipper    = new \Chumper\Zipper\Zipper;
        $zip_name  = rand() . '.zip';
        $full_path = $path . $zip_name;
        $zipper->make($full_path);
        $drive = new Drive();

        try {
            foreach ($ids as $id) {
                $article = Article::find($id);
                if (!$article) {
                    continue;
                }
                $media = $article->export($as, $drive);
                if (!$media) {
                    continue;
                }
                $zipper->add(
                    $media->getPath(),
                    $article->title . '.' . Drive::getExtension($as)
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


}
