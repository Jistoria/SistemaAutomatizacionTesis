<?php

namespace Modules\ThesisProcessStudent\Events;

use App\Enums\StateEnum;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendRequirementsStudent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected string $teacherId,
        protected string $studentName,
        protected StateEnum $newStatus,
        protected string $nameRequirement
    )
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notification.' . $this->teacherId),
        ];
    }

    /**
     * Datos que serán enviados en la transmisión.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'student_name' => $this->studentName,
            'role' => 'Docente-tesis',
            'update' => true,
            'sweet_alert' => [
                'icon' => 'success',
                'title' => 'El estudiante '.$this->studentName.' ha enviado '.$this->nameRequirement,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'NotificationUser';
    }
}
