<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 16/12/17
 * Time: 14:51
 */

namespace App\Services\Api;

use App\Services\Api\Keywords\KeywordsFactoryInterface;
use Illuminate\Support\Collection;

class GoogleKeywords implements KeywordsFactoryInterface
{
	public function __construct()
	{
	}

	/**
	 * @param $keyword
	 * @param string $country
	 * @param string $language
	 * @param string $metrics
	 * @return Collection
	 * @throws \Exception
	 */
	public function suggestions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork'):Collection
	{
		$params = [
			'cx'     => '014431535617793814068:kmrzg_w7iqo',
			'key'    => 'AIzaSyAzD1oduAfxtYOf4y1Jc9KON_DkrgbT99U',
			'q'      => $keyword,
			'gl'     => 'au',
			'fields' => 'items(title)',
			'number' => 20,
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/customsearch/v1?'.http_build_query($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);

		if (curl_error($ch)) {
			throw new \Exception(curl_error($ch));
		}

		$results = json_decode($output, true);

		$keywords = collect($results['items']);

		$keywords = $keywords->keyBy('title');

		$keywords->transform(
			function ($item, $key) {
				return [$item['title'] => false];
			}
		);


		//TODO COMPLETE GOOGLE SGGESTIONS 
		return $keywords->collapse();
	}

	/**
	 * @param $keyword
	 * @param string $country
	 * @param string $language
	 * @param string $metrics
	 * @return Collection
	 */
	public function questions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork'):Collection
	{
		// TODO: Implement questions() method.
	}
}