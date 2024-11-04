-- Author: Johan Cañizares Mantuano
-- Date: 2020-11-19
-- Description: Script de pruebas para la funcionalidad del sistema



-- Prueba para confirmar si el pdf se subio correctamente
SELECT 
    thesis_process.state_now AS proceso, 
    user_students.name AS estudiante, 
    user_teachers.name AS tutor, 
    thesis_titles.title AS tesis,
    period_academic.name AS periodo_academico,
    period_academic.start_date AS inicio_periodo,
    period_academic.end_date AS fin_periodo
FROM 
    thesis_process
INNER JOIN 
    users AS user_students ON user_students.id = thesis_process.student_id
INNER JOIN 
    users AS user_teachers ON user_teachers.id = thesis_process.teacher_id
INNER JOIN 
    thesis_titles ON thesis_titles.thesis_id = thesis_process.thesis_id
INNER JOIN 
    period_academic ON period_academic.period_academic_id = thesis_process.period_academic_id;
