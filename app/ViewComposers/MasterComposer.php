<?php


namespace App\ViewComposers;

use App\Models\Helpers\Page;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


/**
 * Class MasterComposer
 * @package App\ViewComposers
 */
class MasterComposer
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * @var string
     */
    protected $page;

    /**
     * TopMenuComposer constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->page    = $request->path();
        $this->user    = Auth::user();
        $this->request = $request;
    }

    /**
     * @param View $view
     * @return $this|void
     */
    public function compose(View $view)
    {

        if ($this->request->ajax()) {
            return;
        }

        if (!Auth::check()) {
            $data = [
                'notifications'         => null,
                'message_notifications' => null,
                'help_video_src'        => null,
            ];
        } else {
            $data = [
                'notifications'         => $this->user->getNotifications(),
                'message_notifications' => $this->user->getMessageNotifications(),
                'help_video_src'        => $this->helpVideoSrc()
            ];
        }

        \App\Facades\GlobalNotification::make();

        return $view->with($data);
    }

    /**
     * @return bool|string
     */
    public function helpVideoSrc()
    {
        return Page::getRelatedVideos();
    }
}