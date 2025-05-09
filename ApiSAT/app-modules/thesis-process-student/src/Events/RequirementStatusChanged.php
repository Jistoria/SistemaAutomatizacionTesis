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


class RequirementStatusChanged implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public string $studentId;
    public string $requirementId;
    public StateEnum $newStatus;
    public string $nameRequirement;

    /**
     * Create a new event instance.
     *
     * @param string $studentId
     * @param string $requirementId
     * @param string $newStatus
     */
    public function __construct(string $studentId, string $requirementId, StateEnum $newStatus, string $nameRequirement)
    {
        $this->studentId = $studentId;
        $this->requirementId = $requirementId;
        $this->newStatus = $newStatus;
        $this->nameRequirement = $nameRequirement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Canal específico del estudiante
        return new Channel('notification.' . $this->studentId);
    }

    /**
     * Datos que serán enviados en la transmisión.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'requirement_id' => $this->requirementId,
            'new_status' => $this->newStatus,
            'role' => 'Estudiante-tesis',
            'update' => true,
            'sweet_alert' => [
                'icon' => 'success',
                'title' => 'Requerimiento '.$this->nameRequirement.' '. $this->newStatus->value,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'NotificationUser';
    }
}
