<?php

namespace App\Events\Roles;

use App\Models\Roles\Role;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * @var Role
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('Roles');
    }
    
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'RoleCreated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['role' => $this->role];
    }
}
