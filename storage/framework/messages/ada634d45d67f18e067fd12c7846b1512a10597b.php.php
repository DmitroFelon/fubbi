<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\Api\KeywordTool;
use Illuminate\Support\Facades\Session;

class KeywordsController extends Controller
{
	public function index(Project $project, string $theme, KeywordTool $api)
	{
		try {
			$keywords_full = ($project->getMeta('keywords')) ? collect($project->getMeta('keywords')) : collect();

			if ($keywords_full->has($theme) and ! empty($keywords_full->get($theme))) {
				$keywords = $keywords_full->get($theme);
			} else {
				$response = $api->test($theme);

				$response = (isset($response['results'])) ? collect($response['results']) : collect();

				$keywords = collect($response->keyBy('string'))->transform(
					function ($item, $key) {
						return false;
					}
				);

				$keywords_full->put($theme, $keywords->toArray());

				$project->setMeta('keywords', $keywords_full);

				$project->save();
			}

			$data = compact(
				[
					'project',
					'keywords',
					'theme',
				]
			);

			return view('entity.project.partials.form.keywords-step', $data);
		} catch (\Exception $e) {
			return '<div class="alert alert-danger">'.$e->getMessage().'</div>';
		}
	}
}
