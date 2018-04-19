<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 16/12/17
 * Time: 18:35
 */

namespace App\Services\Api\Keywords;

use Faker\Factory as Faker;
use Illuminate\Support\Collection;

class LocalKeywords implements KeywordsFactoryInterface
{
    protected $faker;

    public function __construct()
    {
        $faker       = new Faker();
        $this->faker = $faker->create();
    }

    /**
     * @param $keyword
     * @param string $country
     * @param string $language
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function suggestions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork'):Collection
    {
        return $this->keywords();
    }

    /**
     * @param $keyword
     * @param string $country
     * @param string $language
     * @return \Illuminate\Support\Collection
     */
    public function questions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork'):Collection
    {
        return $this->keywords();
    }
    
    
    private function keywords(){
        $keywords = collect($this->faker->words(20));

        $keywords->transform(function ($item, $key) {
            return [$item => false];
        });

        $keywords = $keywords->collapse();

        return $keywords;
    }
}