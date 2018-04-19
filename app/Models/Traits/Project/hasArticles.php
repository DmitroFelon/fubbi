<?php

namespace App\Models\Traits\Project;

use App\Models\Article;

/**
 * Class Articles
 *
 * attach App\Models\Article to App\Models\Project
 *
 * @package App\Models\Traits\Project
 */
trait hasArticles
{
    /**
     * @return mixed
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @param $article_id
     * @return mixed
     */
    public function attachArticle($article_id)
    {
        return $this->articles()->attach($article_id, ['attempts' => 1]);
        $this->eventData['attachArticle'] = $article_id;
        $this->fireModelEvent('attachArticle', false);
    }

    /**
     * @param $article_id
     * @return mixed
     */
    public function detachArticle($article_id)
    {
        return $query = $this->articles()->detach($article_id);
    }

    /**
     * @param $args
     */
    public function syncArticle($args)
    {
        return $this->articles()->sync($args);
    }

    /**
     * @param $article_id
     */
    public function acceptArticle($article_id)
    {
        $article           = $this->articles()->find($article_id);
        $article->accepted = true;
        $article->save();
        $this->eventData['acceptArticle'] = $article_id;
        $this->fireModelEvent('acceptArticle', false);
    }

    /**
     * @param $article_id
     */
    public function declineArticle($article_id)
    {
        $article  = $this->articles()->find($article_id);
        $attempts = $article->attempts;

        $article->accepted = false;
        $article->attempts = $attempts + 1;

        $article->save();

        if ($attempts == 3) {
            $this->eventData['lastDeclineArticle'] = $article_id;
            $this->fireModelEvent('lastDeclineArticle', false);
        } else {
            $this->eventData['declineArticle'] = $article_id;
            $this->fireModelEvent('declineArticle', false);
        }
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function getArticleByType(string $type)
    {
        return $this->articles()->where('type', $type);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getRequirements()
    {
        return collect([
            [
                'string'           => _('Have you checked the compliance document?'),
                'meta_name'        => '',
                'media_collection' => 'compliance_guideline'
            ],
            [
                'string'           => _('Have you checked the client’s preferred writing style?'),
                'meta_name'        => 'writing_style',
                'media_collection' => ''
            ],
            [
                'string'           => _('Have you checked the Preferred Language?'),
                'meta_name'        => 'language',
                'media_collection' => ''
            ],
            [
                'string'           => _('Did you study the client’s model articles?'),
                'meta_name'        => 'example_article',
                'media_collection' => 'ready_content'
            ],
            [
                'string'           => _('Did you add the client’s preferred call to action?'),
                'meta_name'        => 'cta',
                'media_collection' => ''
            ],
            [
                'string'           => _('Did you check if the content needs to be relevant to a City, State or Country?'),
                'meta_name'        => 'relevance',
                'media_collection' => ''
            ],
            [
                'string'           => _('Did you check the Words To Avoid list?'),
                'meta_name'        => 'avoid_keywords',
                'media_collection' => ''
            ],
            /*[
                'string'           => _('Did you check the Writing Guidelines List?'),
                'meta_name'        => '',
                'media_collection' => 'compliance_guideline'
            ],*/
            /* [
                 'string'           => _('Did you check every point in the outline is covered in the article?'),
                 'meta_name'        => '',
                 'media_collection' => ''
             ],
             [
                 'string'           => _('Did you only only write one article this month per keyword?'),
                 'meta_name'        => '',
                 'media_collection' => ''
             ],*/
            /*[
                'string'           => _('Did you lay the article out in the correct format?'),
                'meta_name'        => '',
                'media_collection' => ''
            ],*/
        ]);
    }
}