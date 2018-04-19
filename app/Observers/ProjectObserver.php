<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Notifications\Article\Disaproved;
use App\Notifications\Project\Created;
use App\Notifications\Project\StatusChanged;
use App\Notifications\Project\ThirdArticleReject;
use App\Notifications\Worker\Attached;
use App\Notifications\Worker\Detached;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Musonza\Chat\Conversations\Conversation;
use Musonza\Chat\Facades\ChatFacade;

/**
 * Class ProjectObserver
 *
 * @package App\Observers
 */
class ProjectObserver
{
    /**
     * @var \App\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * ProjectObserver constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->user    = Auth::user();
        $this->request = $request;
    }

    /**
     * Called when client creates a new subscription
     *
     *
     * @param \App\Models\Project $project
     */
    public function created(Project $project)
    {
        //notify admins about new subscription
        $should_be_notified = [
            Role::ADMIN => Created::class,
        ];

        //invite managers to the new project while filling process
        $should_be_invited = [
            Role::ACCOUNT_MANAGER,
        ];
        
        if($project->client){
            //send confirmation to client
            $project->client->notify(new Created($project));
        }

        foreach ($should_be_notified as $role => $model) {
            $users = User::withRole($role)->get();
            $users->each(
                function (User $user, $key) use ($project, $model) {
                    $user->notify(new $model($project));
                }
            );
        }

        foreach ($should_be_invited as $role) {
            $users = User::withRole($role)->get();
            $users->each(
                function (User $user, $key) use ($project) {
                    $user->inviteTo($project);
                }
            );
        }

        /*
         * Create chat conversation
         * */
        $participants = User::withRole(Role::ADMIN)->get(['id'])->pluck('id');

        $participants->push($project->client->id);

        $participants = $participants->unique();

        $conversation = Conversation::find($project->conversation_id);

        $conversation = ($conversation)
            ? $conversation
            : ChatFacade::createConversation($participants->toArray());

        $conversation->update([
            'data' => [
                'project_id' => $project->id,
                'title'      => $project->name
            ]
        ]);

        $project->setMeta('conversation_id', $conversation->id);
        $project->save();

        if (Auth::check()) {
            activity('project_state')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties([])
                ->log('Project ' . $project->name . ' has beeb created');
        }
    }

    /**
     * Called when client fills the quiz and keywords
     *
     *
     * @param \App\Models\Project $project
     */
    public function filled(Project $project)
    {
        $should_be_invited = [
            \App\Models\Role::ACCOUNT_MANAGER,
            \App\Models\Role::WRITER,
            \App\Models\Role::EDITOR,
            \App\Models\Role::DESIGNER,
            \App\Models\Role::RESEARCHER,
        ];

        foreach ($should_be_invited as $role) {
            $users = User::withRole($role)->get();
            $users->each(
                function (User $user, $key) use ($project) {
                    $user->inviteTo($project);
                }
            );
        }

        activity('project_state')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties([])
            ->log('Project ' . $project->name . ' has beeb filled sucessfully');
    }

    /**
     * @param Project $project
     */
    public function attachWorker(Project $project)
    {
        if (!isset($project->eventData['attachWorker'])) {
            return;
        }

        $worker_id = $project->eventData['attachWorker'];

        $conversation = ChatFacade::conversation($project->conversation_id);

        $worker = User::find($worker_id);

        if ($worker and $worker->role == Role::ACCOUNT_MANAGER) {
            ChatFacade::addParticipants($conversation, $worker_id);
        }

        activity('project_worker')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['worker' => $worker])
            ->log('User ' . $worker->name . ' has been attached to project ' . $project->name);

        $worker->notify(new Attached($project));
    }

    /**
     * @param Project $project
     */
    public function detachWorker(Project $project)
    {
        if (!isset($project->eventData['detachWorker'])) {
            return;
        }

        $worker_id = $project->eventData['detachWorker'];

        $conversation = ChatFacade::conversation($project->conversation_id);

        if($conversation){
            ChatFacade::removeParticipants($conversation, $worker_id);
        }

        $worker = User::find($worker_id);

        activity('project_worker')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['worker' => $worker])
            ->log('User ' . $worker->name . ' has been detached from project ' . $project->name);

        $worker->notify(new Detached($project));
    }

    /**
     * @param Project $project
     */
    public function attachWorkers(Project $project)
    {
        $worker_ids = $project->eventData['attachWorkers'];

        $workers = User::findMany($worker_ids);

        $attached_users_names = implode(', ', $workers->pluck('name')->toArray());

        activity('project_worker')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['worker' => $workers])
            ->log('Users:  ' . $attached_users_names . ' have beed attached to project ' . $project->name);

        $workers->each(function (User $user) use ($project) {
            $user->notify(new Attached($project));
        });

    }

    /**
     * @param Project $project
     */
    public function attachTeam(Project $project)
    {
        $team = Team::find($project->eventData['attachTeam']);
        activity('project_worker')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['team' => $team])
            ->log('Team ' . $team->name . ' has been attached to project project ' . $project->name);
    }

    /**
     * @param Project $project
     */
    public function attachArticle(Project $project)
    {
        $article = Article::find($project->eventData['attachArticle']);

        activity('project_progress')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['worker' => $article])
            ->log('Article ' . $article->title . ' has been attached to project project ' . $project->name);

    }

    /**
     * @param Project $project
     */
    public function setState(Project $project)
    {
        $project->client->notify(new StatusChanged($project));

        activity('project_progress')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['state' => $project->state])
            ->log('Project ' . $project->name . ' status changed to: "' . ucfirst(str_replace('_', ' ', $project->state)) . '".');
    }

    /**
     * @param Project $project
     */
    public function acceptArticle(Project $project)
    {
        $article = Article::find($project->eventData['acceptArticle']);

        activity('project_progress')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['article' => $article])
            ->log('Article ' . $article->title . ' has been apprived.');
    }

    /**
     * @param Project $project
     */
    public function declineArticle(Project $project)
    {
        $article = Article::find($project->eventData['declineArticle']);

        $manager = $project->workers()->withRole(Role::ACCOUNT_MANAGER)->first();

        $author = $article->author;

        if ($manager) {
            $manager->notify(new Disaproved($article));
        }

        if ($author) {
            $author->notify(new Disaproved($article));
        }

        activity('project_progress')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['article' => $article])
            ->log('Article "' . $article->title . '" has been declined.');
    }

    /**
     * @param Project $project
     */
    public function lastDeclineArticle(Project $project)
    {
        $article = Article::find($project->eventData['lastDeclineArticle']);


        $manager = $project->workers()->withRole(Role::ACCOUNT_MANAGER)->first();

        if ($manager) {
            $manager->notify(new ThirdArticleReject($article));
        }

        activity('project_progress')
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['article' => $article])
            ->log('Writer: ' . $article->author->name . ' has spent 3 attempts wtih article: "' . $article->title . '"');
    }

    public function reset(Project $project)
    {

    }

    public function suspend(Project $project)
    {

    }


}