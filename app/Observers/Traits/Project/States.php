<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 09/11/17
 * Time: 12:24
 */

namespace App\Observers\Traits\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait States
{
    public function setState(Project $project)
    {
        //Log::info('state changed to: ' . $project->state . ". By: ". Auth::user()->name);
    }

}