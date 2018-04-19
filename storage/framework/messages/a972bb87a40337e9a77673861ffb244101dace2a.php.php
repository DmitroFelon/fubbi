<?php

namespace App\Http\Controllers;

use App\Models\Annotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnotationController extends Controller
{

    public function index(Request $request){
        $annotations = Annotation::with([
            'user' => function($query)
        {
            $query->addSelect(['id']);
            $query->addSelect(DB::raw('CONCAT_WS(" ", `first_name`, `last_name`) as name'));
        }
        ])->get();

        return response()->json(['total' => count($annotations), 'rows' => $annotations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Annotation $annotation)
    {
        $data = $request->input();

        $annotation->fill($request->input());
        $annotation->user_id = Auth::user()->id;
        $annotation->article_id = $request->input('article_id');
        $annotation->save();
        return $annotation->id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Models\Annotation $annotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Annotation $annotation)
    {
        return strval($annotation->fill($request->input())->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Annotation $annotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Annotation $annotation)
    {
        return strval($annotation->delete());
    }
}
