<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class VoteCast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $positionId;
    public $candidateVotes;

    /**
     * Create a new event instance.
     *
     * @param int $positionId The ID of the position being voted on
     * @param array $candidateVotes Array of candidate IDs mapped to their vote counts
     */
    public function __construct($positionId, $candidateVotes)
    {
        $this->positionId = $positionId;
        $this->candidateVotes = $candidateVotes;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('position.' . $this->positionId);
    }
}