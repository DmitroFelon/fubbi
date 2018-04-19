<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 16/12/17
 * Time: 14:35
 */

namespace App\Services\Api\Keywords;

use Illuminate\Support\Collection;

/**
 * Interface KeywordsFactoryInterface
 *
 * @package App\Services\Api
 */
interface KeywordsFactoryInterface
{
	/**
	 * @param $keyword
	 * @param string $country
	 * @param string $language
	 * @param string $metrics
	 * @return Collection
	 */
	public function suggestions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork'):Collection;

	/**
	 * @param $keyword
	 * @param string $country
	 * @param string $language
	 * @param string $metrics
	 * @return Collection
	 */
	public function questions($keyword, $country = 'au', $language = 'en', $metrics = 'googlesearchnetwork'):Collection;
}