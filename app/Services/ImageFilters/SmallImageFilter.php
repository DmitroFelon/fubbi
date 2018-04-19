<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/9/18
 * Time: 2:46 PM
 */

namespace App\Services\ImageFilters;

use Intervention\Image\Filters\FilterInterface;

class SmallImageFilter implements FilterInterface
{
    /**
     * Applies filter to given image
     *
     * @param  \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        return $image->fit(120, 120);
    }
}