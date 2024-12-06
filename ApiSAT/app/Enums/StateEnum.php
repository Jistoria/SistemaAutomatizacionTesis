<?php
namespace App\Enums;

enum StateEnum: string
{
    case APPROVED = 'Aprobado';
    case SENT = 'Enviado';
    case IN_PROCESS = 'En proceso';
    case PENDING = 'Pendiente';
    case REJECTED = 'Rechazado';
    case NOT_ENABLED = 'No habilitado';
}
