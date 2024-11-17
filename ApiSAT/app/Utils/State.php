<?php
namespace App\Utils;

class State
{
    public const APPROVED = 'Aprobado';
    public const SENT = 'Enviado';
    public const IN_PROCESS = 'En proceso';
    public const PENDING = 'Pendiente';
    public const REJECTED = 'Rechazado';
    public const NOT_ENABLED = 'No habilitado';

    /**
     * Método opcional para obtener todos los estados como un arreglo.
     */
    public static function getAllStates(): array
    {
        return [
            self::APPROVED,
            self::SENT,
            self::IN_PROCESS,
            self::REJECTED,
            self::NOT_ENABLED,
        ];
    }
}
