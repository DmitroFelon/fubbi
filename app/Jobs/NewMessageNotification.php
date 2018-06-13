<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Notifications\NewChatMessage;

class NewMessageNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var
     */
    protected $conversation_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $conversation_id)
    {
        $this->user = $user;
        $this->conversation_id = $conversation_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new NewChatMessage($this->user, $this->conversation_id));
    }
}
