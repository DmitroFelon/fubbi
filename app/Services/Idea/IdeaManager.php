<?php

namespace App\Services\Idea;

class IdeaManager
{
   public function update($request, $idea)
   {
       $idea->article_format_type = $request->article_format_type;
       $idea->link_to_model_article = $request->link_to_model_article;
       $idea->references = $request->references;
       $idea->points_covered = $request->points_covered;
       $idea->points_avoid = $request->points_avoid;
       $idea->additional_notes = $request->additional_notes;
       $idea->completed = 1;
       $idea->save();
   }
}
