<?php

namespace Musonza\Chat\Conversations;

use Eloquent;

class ConversationUser extends Eloquent
{
    protected $table = 'mc_conversation_user';

    /**
     * Conversation.
     *
     * @return Relationship
     */
    public function conversation()
    {
        return $this->belongsTo('Musonza\Chat\Conversations\Conversation', 'conversation_id');
    }
}
