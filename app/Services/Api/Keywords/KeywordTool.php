<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 14/12/17
 * Time: 07:54
 */

namespace App\Services\Api;

use App\Services\Api\Keywords\KeywordsFactoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * Class KeywordTool
 *
 * @package App\Services\Api
 */
class KeywordTool implements KeywordsFactoryInterface
{

    /**
     *
     */
    const SOURCE_GOOGLE = 'google';
    /**
     *
     */
    const SOURCE_YOUTUBE = 'youtube';
    /**
     *
     */
    const SOURCE_BING = 'bing';
    /**
     *
     */
    const SOURCE_AMAZON = 'amazon';
    /**
     *
     */
    const SOURCE_EBAY = 'ebay';
    /**
     *
     */
    const SOURCE_APP_STORE = 'app-store';

    /**
     * @param $keyword
     * @param string $country
     * @param string $language
     * @param string $metrics
     * @param string $source
     * @return Collection
     * @throws \Exception
     */
    public function suggestions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork', $source = self::SOURCE_GOOGLE):Collection
    {
        $apikey = config('keywordtool.apikey');

        $params = [
            'apikey'          => $apikey,
            'keyword'         => urldecode($keyword),
            'country'         => $country,
            'language'        => $language,
            'metrics_network' => 'googlesearchnetwork',
            'output'          => 'json',
        ];

        try {
            $results = $this->call($params, $source);
        } catch (\Exception $e) {
            throw new \Exception(json_encode($e->getMessage()));
        }

        $results  = $results->collapse();
        $keywords = collect($results->keyBy('string'));
        return $keywords->keys();
    }

    /**
     * @param $keyword
     * @param string $country
     * @param string $language
     * @param string $metrics
     * @param string $source
     * @return Collection
     * @throws \Exception
     */
    public function questions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork', $source = self::SOURCE_GOOGLE):Collection
    {
        $apikey = config('keywordtool.apikey');

        $params = [
            'apikey'          => $apikey,
            'keyword'         => urldecode($keyword),
            'country'         => $country,
            'language'        => $language,
            'metrics_network' => 'googlesearchnetwork',
            'output'          => 'json',
            'type'            => 'questions'
        ];

        try {
            $results = $this->call($params, $source);
        } catch (\Exception $e) {
            throw new \Exception(json_encode($e->getMessage()));
        }

        $categories_count = $results->count();
        $results          = $results->collapse();
        //remove sub categories from result
        $results  = $results->slice($categories_count);
        $keywords = collect($results->keyBy('string'));
        return $keywords->keys();
    }

    /**
     * @param $params
     * @param string $source
     * @return Collection
     * @throws \Exception
     */
    private function call(array $params, string $source = self::SOURCE_GOOGLE)
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            sprintf("https://api.keywordtool.io/v2/search/suggestions/%s?%s", $source, http_build_query($params))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);

        if (curl_error($ch)) {
            if (App::environment('production')) {
                throw new \Exception('Keyword Tool returned an empty result');
            }else{
                throw new \Exception(curl_error($ch));
            }
            
        }

        $results = json_decode($output, true);

        if (!isset($results['results']) or empty($results['results'])) {
            throw new \Exception('Keyword Tool returned an empty result');
        }

        return collect($results['results']);
    }

    /**
     * @param string $source
     * @return string
     */
    public static function getSourceName(string $source) :string
    {
        $names = [
            self::SOURCE_GOOGLE    => 'Google',
            self::SOURCE_YOUTUBE   => 'Youtube',
            self::SOURCE_BING      => 'Bing',
            self::SOURCE_AMAZON    => 'Amazon',
            self::SOURCE_EBAY      => 'Ebay',
            self::SOURCE_APP_STORE => 'App Store',
        ];

        return $names[$source] ?? '';
    }
}
