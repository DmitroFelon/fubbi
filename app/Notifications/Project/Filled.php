<?php

namespace App\Notifications\Project;

use App\Models\Project;
use App\Notifications\NotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Filled extends Notification implements ShouldQueue
{
	use Queueable;

	protected $project;

	/**
	 * Create a new notification instance.
	 *
	 * @param \App\Models\Project $project
	 */
	public function __construct(Project $project)
	{
		$this->project = $project;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail', 'database'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		return (new MailMessage)->line(
				_i(
					'New project %s has beed filled.',
					[$this->project->name]
				)
			)->action('Review project', url()->action('ProjectController@show', $this->project))->line(
				'Thank you for using our application!'
			);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		$notification = NotificationPayload::make(
			_i(
				'New project %s has beed filled.',
				[$this->project->name]
			),
			url()->action('ProjectController@show', $this->project),
			get_class($this->project),
			$this->project->id
		);

		return $notification->toArray();
	}
}
