<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/26/18
 * Time: 3:41 PM
 */

namespace App\Models\Helpers;


/**
 * Class NotificationTypes
 * @package App\Models\Helpers
 */
class NotificationTypes
{
    /**
     * @const array
     */
    const CLIENT = [
        \App\Notifications\Chat\PrivateMessage::class        => 'Private Messages',
        \App\Notifications\Client\OutstandingApproval::class => 'Outstanding Approvals',
        \App\Notifications\Client\QuizIncomplete::class      => 'Onboarding quiz incomplete',
    ];

    /**
     * @const array
     */
    const WRITER = [
        \App\Notifications\Chat\PrivateMessage::class      => 'Private Messages',
        \App\Notifications\Worker\ArticleOverdue::class    => 'Item Overdue',
        \App\Notifications\Worker\ArticleDisaproval::class => 'Item Disaproval',
    ];

    /**
     * @const array
     */
    const DESIGNER = [
        \App\Notifications\Chat\PrivateMessage::class      => 'Private Messages',
        \App\Notifications\Worker\ArticleOverdue::class    => 'Item Overdue',
        \App\Notifications\Worker\ArticleDisaproval::class => 'Item Disaproval',
    ];

    /**
     * @const array
     */
    const RESEARCHER = [
        \App\Notifications\Chat\PrivateMessage::class      => 'Private Messages',
        \App\Notifications\Worker\ArticleOverdue::class    => 'Item Overdue',
        \App\Notifications\Worker\ArticleDisaproval::class => 'Item Disaproval',
    ];

    /**
     * @const array
     */
    const EDITOR = [
        \App\Notifications\Chat\PrivateMessage::class      => 'Private Messages',
        \App\Notifications\Worker\ArticleOverdue::class    => 'Item Overdue',
        \App\Notifications\Worker\ArticleDisaproval::class => 'Item Disaproval',
    ];

    /**
     * @const array
     */
    const ACCOUNT_MANAGER = [
        \App\Notifications\Chat\PrivateMessage::class       => 'Private Messages',
        \App\Notifications\Manager\ArticleDisaproval::class => 'Item Disaprovals',
        \App\Notifications\Manager\ArticleOverdue::class    => 'Item Overdue',
        \App\Notifications\Manager\InviteOverdue::class     => 'Invites Overdue',
        \App\Notifications\Manager\ArticleLowRating::class  => '3 star rating or below',
    ];

    /**
     * @const array
     */
    const ADMIN = [
        \App\Notifications\Chat\PrivateMessage::class       => 'Private Messages',
        \App\Notifications\Manager\ArticleDisaproval::class => 'Item Disaprovals',
        \App\Notifications\Manager\ArticleOverdue::class    => 'Item Overdue',
        \App\Notifications\Manager\InviteOverdue::class     => 'Invites Overdue',
        \App\Notifications\Manager\ArticleLowRating::class  => '3 star rating or below',
        \App\Notifications\Client\Registered::class         => 'New Client',
    ];

    /**
     * @param string $role
     * @return \Illuminate\Support\Collection
     */
    public static function get($role = '')
    {
        return (defined('self::' . strtoupper($role)))
            ? collect(constant('self::' . strtoupper($role)))
            : collect();

    }
}
