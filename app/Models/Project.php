<?php

namespace App\Models;

use App\Models\Traits\Project\Articles;
use App\Models\Traits\Project\FormProjectAccessors;
use App\Models\Traits\Project\Keywords;
use App\Models\Traits\Project\States;
use App\Models\Traits\Project\Teams;
use App\Models\Traits\Project\Topics;
use App\Models\Traits\Project\Workers;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Kodeine\Metable\Metable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Project
 *
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \App\User $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Keyword[] $keywords
 * @property-read \Illuminate\Database\Eloquent\Collection|Revision[] $revisionHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Topic[] $topics
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $workers
 * @method static Builder|\App\Models\Project meta()
 * @mixin \Eloquent
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $description
 * @property string $state
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|\App\Models\Project whereClientId($value)
 * @method static Builder|\App\Models\Project whereCreatedAt($value)
 * @method static Builder|\App\Models\Project whereId($value)
 * @method static Builder|\App\Models\Project whereName($value)
 * @method static Builder|\App\Models\Project whereState($value)
 * @method static Builder|\App\Models\Project whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 */
class Project extends Model implements HasMedia
{
	use Keywords;
	use States;
	use Workers;
	use Teams;
	use Articles;
	use Topics;
	use RevisionableTrait;
	use Metable;
	use FormProjectAccessors;
	use HasMediaTrait;

	/**
	 * @const string
	 */
	const CREATED = 'created';

	/**
	 * @const string
	 */
	const PLAN_SELECTION = 'plan_selection';

	/**
	 * @const string
	 */
	const QUIZ_FILLING = 'quiz_filling';

	/**
	 * @const string
	 */
	const KEYWORDS_FILLING = 'keywords_filling';

	/**
	 * @const string
	 */
	const MANAGER_REVIEW = 'on_manager_review';

	/**
	 * @const string
	 */
	const PROCESSING = 'processing';

	/**
	 * @const string
	 */
	const CLIENT_REVIEW = 'on_client_review';

	/**
	 * @const string
	 */
	const ACCEPTED_BY_CLIENT = 'accepted_by_client';

	/**
	 * @const string
	 */
	const REJECTED_BY_CLIENT = 'rejected_by_client';

	/**
	 * @const string
	 */
	const COMPLETED = 'completed';

	/**
	 * @var bool
	 */
	protected $revisionEnabled = true;

	/**
	 * @var bool
	 */
	protected $revisionCleanup = true;

	/**
	 * @var int
	 */
	protected $historyLimit = 200;

	/**
	 * @var bool
	 */
	protected $revisionCreationsEnabled = true;

	/**
	 * Additional observable events.
	 */
	protected $observables = [
		'attachKeywords',
		'detachKeywords',
		'syncKeywords',
		'attachWorkers',
		'detachWorkers',
		'syncWorkers',
		'setState',
	];

	/**
	 * Project constructor.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function client()
	{
		return $this->belongsTo(User::class, 'client_id');
	}

	public function addFiles(Request $request)
	{
		foreach ($request->file('article_images') as $file) {
			$this->addMedia($file)->toMediaCollection('article_images');
		}
		foreach ($request->file('compliance_guideline') as $file) {
			$this->addMedia($file)->toMediaCollection('compliance_guideline');
		}
		foreach ($request->file('logo') as $file) {
			$this->addMedia($file)->toMediaCollection('logo');
		}
		foreach ($request->file('ready_content') as $file) {
			$this->addMedia($file)->toMediaCollection('ready_content');
		}
	}
}
