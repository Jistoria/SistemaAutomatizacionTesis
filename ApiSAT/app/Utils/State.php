<?php

namespace App\Utils;
use App\Enums\StateEnum;

class State
{
    public const APPROVED = StateEnum::APPROVED->value;
    public const SENT = StateEnum::SENT->value;
    public const IN_PROCESS = StateEnum::IN_PROCESS->value;
    public const PENDING = StateEnum::PENDING->value;
    public const REJECTED = StateEnum::REJECTED->value;
    public const NOT_ENABLED = StateEnum::NOT_ENABLED->value;

    /**
     * Obtener todos los estados como un arreglo.
     */
    public static function getAllStates(): array
    {
        return array_map(fn (StateEnum $enum) => $enum->value, StateEnum::cases());
    }

    /**
     * Convertir un estado string a enum.
     */
    public static function toEnum(string $state): ?StateEnum
    {
        return StateEnum::tryFrom($state);
    }

    /**
     * Convertir un enum a string.
     */
    public static function fromEnum(StateEnum $enum): string
    {
        return $enum->value;
    }

    public static function getStateforTeacher(): array
    {
        return [
            self::APPROVED,
            self::REJECTED
        ];
    }


}
