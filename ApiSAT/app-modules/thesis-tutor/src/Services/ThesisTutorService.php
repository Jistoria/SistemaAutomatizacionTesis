<?php

namespace Modules\ThesisTutor\Services;

use App\Enums\StateEnum;
use App\Models\Academic\Thesis\Observations\ObservationRequirement;
use App\Utils\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\ThesisProcessStudent\Contracts\RequirementsStudentServiceInterface;
use Modules\ThesisTutor\Models\Tutor;


/**
 * Servicio para gestionar la relación entre tutores y estudiantes en el contexto de tesis.
 */
class ThesisTutorService
{
    /**
     * Constructor del servicio de tutor de tesis.
     *
     * @param Tutor $teacher Instancia del modelo Tutor asociada.
     */
    public function __construct(
        protected Tutor $teacher,
        protected ObservationRequirement $observationRequirement
    ) {}

    /**
     * Obtiene los estudiantes asignados al tutor.
     *
     * @param string $user Identificador del tutor (teacher_id).
     * @param int|null $paginate Número de resultados por página para paginación (opcional).
     * @return Collection|LengthAwarePaginator|null Retorna una colección de estudiantes,
     *                                             un paginador si se utiliza paginación,
     *                                             o null si no hay estudiantes.
     */
    public function getMyStudents(string $user, int $paginate = null, string $search = null, string $order = null): Collection|LengthAwarePaginator|null
    {
        // Obtiene el tutor por su ID y llama al método getStudents para obtener sus estudiantes.
        $students = $this->teacher->where('teacher_id', $user)->first()?->getStudents($paginate, $search, $order);

        return $students;
    }

    /**
     * Cambia el estado de un requisito de un estudiante.
     *
     * @param string $user Identificador del tutor que realiza la acción.
     * @param string $student_requirements_id Identificador del requisito del estudiante.
     * @param StateEnum $status Estado nuevo para el requisito, representado como un enum State.
     * @return void
     */
    public function changeStatusRequirementStudent(string $user, string $student_requirements_id, StateEnum $status): void
    {
        // Llama al servicio de requisitos del estudiante para actualizar el estado.
        app(RequirementsStudentServiceInterface::class)->updateStatus($student_requirements_id, $status, $user);
    }

    public function createObservationsRequirement (string $user, array $data): ObservationRequirement
    {
        return $this->observationRequirement->create([
            'created_by_user' => $user,
            'student_requirements_id' => $data['student_requirements_id'],
            'comment' => $data['comment']
        ]);
    }

    public function getObservationsRequirement (string $student_requirements_id): Collection
    {
        return $this->observationRequirement->where('student_requirements_id', $student_requirements_id)->get();
    }

    public function deleteObservationsRequirement(string $observation_requirement_id): void
    {

        $this->observationRequirement->where('observation_requirement_id', $observation_requirement_id)->delete();
    }
}
