<?php


namespace App\Exceptions\Project;

class ImpossibleProjectState extends \Exception
{
    public function render()
    {
        abort(403);
    }
}