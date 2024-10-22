-- Date: 2021-10-10
-- Author: Johan Cañizares 
-- Description: Script de creación de la base de datos
-- Version: 1.0

-- Creación de la base de datos
-- Implementar UUID
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

--USUARIOS
CREATE TABLE users (
    user_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),     -- Identificador único del usuario
    name VARCHAR(255) NOT NULL,          -- Nombre del usuario
    email VARCHAR(255) NOT NULL UNIQUE,  -- Correo electrónico del usuario, único
    password VARCHAR(255) NOT NULL,      -- Contraseña del usuario
    created_at TIMESTAMP DEFAULT NOW(),  -- Fecha y hora de creación
    updated_at TIMESTAMP DEFAULT NOW(),  -- Fecha y hora de actualización
    deleted_at TIMESTAMP,                -- Fecha y hora de eliminación (opcional)
    deleted_by_user UUID,                 -- ID del usuario que eliminó el registro
    created_by_user UUID,                 -- ID del usuario que creó el registro
    updated_by_user UUID,                 -- ID del usuario que actualizó el registro
    disabled BOOLEAN DEFAULT FALSE,      -- Estado de deshabilitación del usuario
    deleted BOOLEAN DEFAULT FALSE        -- Marca si el usuario está eliminado
);

--TEMA DE TESIS
CREATE TABLE thesis_titles (
    thesis_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),          -- Identificador único de la tesis
    name VARCHAR(255) NOT NULL              -- Nombre del tema de tesis
);

--PERIODO ACADEMICO
CREATE TABLE period_academic (
    period_academic_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(), -- Identificador único del periodo académico
    name VARCHAR(255) NOT NULL,             -- Nombre del periodo académico
    created_at TIMESTAMP DEFAULT NOW(),     -- Fecha de creación
    created_by_user UUID REFERENCES users(user_id), -- Usuario que creó el registro
    updated_at TIMESTAMP DEFAULT NOW(),     -- Fecha de actualización
    updated_by_user UUID REFERENCES users(user_id), -- Usuario que actualizó el registro
    deleted_at TIMESTAMP,                   -- Fecha de eliminación (si aplica)
    deleted_by_user UUID REFERENCES users(user_id), -- Usuario que eliminó el registro
    date_start DATE NOT NULL,               -- Fecha de inicio del periodo académico
    date_end DATE NOT NULL                  -- Fecha de fin del periodo académico
);


--ESTUDIANTES
CREATE TABLE students (
    student_id UUID UNIQUE REFERENCES users(user_id) ON DELETE CASCADE,        -- Identificador único del estudiante
    thesis_id UUID REFERENCES thesis_titles(thesis_id),  -- Relación con la tabla de tesis
    dni BIGINT NOT NULL,                    -- Documento Nacional de Identidad del estudiante
    degree VARCHAR(255),                    -- Título académico del estudiante
    approval BOOLEAN DEFAULT FALSE,         -- Indica si ha sido aprobado
    remarks TEXT,                           -- Comentarios adicionales
    enrollment_date TIMESTAMP DEFAULT NOW()  -- Fecha de inscripción
);



--DOCENTES
CREATE TABLE teachers (
    teacher_id UUID  UNIQUE REFERENCES users(user_id) ON DELETE CASCADE,   -- Identificador único del profesor
    area_of_expertise VARCHAR(255) NOT NULL, -- Área de especialización del profesor
    no_comments TEXT                         -- Comentarios adicionales
);

--PROCESO DE TESIS
CREATE TABLE thesis_process (
    thesis_process_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),        -- Identificador único del proceso de tesis
    teacher_id UUID REFERENCES teachers(teacher_id), -- Relación con el tutor (profesor)
    student_id UUID REFERENCES students(student_id), -- Relación con el estudiante
    thesis_id UUID REFERENCES thesis_titles(thesis_id), -- Relación con el tema de tesis
    period_academic_id UUID REFERENCES period_academic(period_academic_id), -- Relación con el periodo académico
    state_now VARCHAR(255),                      -- Estado actual del proceso de tesis
    date_start DATE NOT NULL,                    -- Fecha de inicio del proceso
    date_end DATE,                               -- Fecha de finalización del proceso
    created_at TIMESTAMP DEFAULT NOW(),          -- Fecha de creación
    updated_at TIMESTAMP DEFAULT NOW(),          -- Fecha de actualización
    created_by_user UUID REFERENCES users(user_id), -- Usuario que creó el registro
    updated_by_user UUID REFERENCES users(user_id) -- Usuario que actualizó el registro
);


--TRIBUNAL DE TESIS
CREATE TABLE thesis_committee (
    thesis_committee_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(), -- Llave primaria con UUID
    thesis_process_id UUID REFERENCES thesis_process(thesis_process_id),  -- Relación con el proceso de tesis (UUID)
    teacher_id UUID REFERENCES teachers(teacher_id),                      -- Relación con el profesor (UUID)
    student_id UUID REFERENCES students(student_id)                      -- Relación con el estudiante (UUID)
);

-- SOLICITUD DE CAMBIOS
CREATE TABLE request_changes (
    request_changes_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(), -- Llave primaria con UUID
    document_name VARCHAR(255) NOT NULL,          -- Nombre del documento en el que se solicitan cambios
    file_path VARCHAR(255),                       -- Ruta del archivo con los cambios solicitados
    submit_at TIMESTAMP DEFAULT NOW(),            -- Fecha de envío
    submit_by_user UUID REFERENCES users(user_id),-- Usuario que solicitó los cambios (UUID)
    thesis_process_id UUID REFERENCES thesis_process(thesis_process_id), -- Relación con el proceso de tesis (UUID)
    approved BOOLEAN DEFAULT FALSE,               -- Si los cambios fueron aprobados
    approved_by_user UUID REFERENCES users(user_id), -- Usuario que aprobó los cambios (UUID)
    failed BOOLEAN DEFAULT FALSE,                 -- Si los cambios fallaron
    failed_by_user UUID REFERENCES users(user_id), -- Usuario que marcó como fallidos los cambios (UUID)
    failed_at TIMESTAMP                           -- Fecha en la que fallaron los cambios
);


-- ANEXOS EXTRAS
CREATE TABLE anexos_extras (
    anexos_extras_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),   -- Llave primaria con UUID
    thesis_process_id UUID REFERENCES thesis_process(thesis_process_id), -- Relación con el proceso de tesis (UUID)
    period_academic_id UUID REFERENCES period_academic(period_academic_id), -- Relación con el periodo académico (UUID)
    document_name VARCHAR(255) NOT NULL,                            -- Nombre del documento adicional
    file_path VARCHAR(255),                                         -- Ruta del archivo adicional
    reason_document VARCHAR(255),                                   -- Razón del documento adicional
    submit_at TIMESTAMP DEFAULT NOW(),                              -- Fecha de envío
    approved BOOLEAN DEFAULT FALSE,                                 -- Si fue aprobado
    approved_at DATE,                                               -- Fecha de aprobación
    comments TEXT,                                                  -- Comentarios adicionales
    approved_by_user UUID REFERENCES users(user_id),                -- Usuario que aprobó el anexo (UUID)
    submit_by_user UUID REFERENCES users(user_id)                   -- Usuario que envió el anexo (UUID)
);

-- FASES DE TESIS
CREATE TABLE thesis_phases (
    thesis_phases_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),   -- Llave primaria con UUID
    name VARCHAR(255) NOT NULL,                   -- Nombre de la fase de tesis
    pre_requirements TEXT,                        -- Prerrequisitos de la fase
    created_at TIMESTAMP DEFAULT NOW(),           -- Fecha de creación
    updated_at TIMESTAMP DEFAULT NOW(),           -- Fecha de actualización
    created_by_user UUID REFERENCES users(user_id), -- Usuario que creó la fase (UUID)
    updated_by_user UUID REFERENCES users(user_id)  -- Usuario que actualizó la fase (UUID)
);


-- PREREQUERIMIENTO DE FASE DE TESIS
CREATE TABLE pre_requirements (
    pre_requirements_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(), -- Llave primaria con UUID
    thesis_phases_id UUID REFERENCES thesis_phases(thesis_phases_id), -- Relación con fases de tesis (UUID)
    name VARCHAR(255) NOT NULL,                  -- Nombre del prerrequisito
    description JSON                             -- Descripción del prerrequisito en formato JSON
);

-- REQUSITOS DE FASE DE TESIS
CREATE TABLE requirements (
    requirements_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),    -- Llave primaria con UUID
    thesis_phases_id UUID REFERENCES thesis_phases(thesis_phases_id), -- Relación con fases de tesis (UUID)
    name VARCHAR(255) NOT NULL,                 -- Nombre del requisito
    description JSON                            -- Descripción del requisito en formato JSON
);


-- FASES DE TESIS DE ESTUDIANTES
CREATE TABLE thesis_process_phases (
    thesis_process_phases_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(), -- Llave primaria con UUID
    thesis_process_id UUID REFERENCES thesis_process(thesis_process_id),   -- Relación con el proceso de tesis (UUID)
    teacher_id UUID REFERENCES teachers(teacher_id),                       -- Relación con el profesor (UUID)
    student_id UUID REFERENCES students(student_id),                       -- Relación con el estudiante (UUID)
    thesis_id UUID REFERENCES thesis_titles(thesis_id),                    -- Relación con el tema de tesis (UUID)
    thesis_phases_id UUID REFERENCES thesis_phases(thesis_phases_id),      -- Relación con la fase de tesis (UUID)
    period_academic_id UUID REFERENCES period_academic(period_academic_id), -- Relación con el periodo académico (UUID)
    requirements_approval BOOLEAN DEFAULT FALSE,                          -- Aprobación de los requisitos
    state_now VARCHAR(255),                                                -- Estado actual de la fase del proceso de tesis
    date_start DATE NOT NULL,                                              -- Fecha de inicio de la fase
    date_end DATE                                                          -- Fecha de fin de la fase
);


-- SALA DE DEFENSA
CREATE TABLE presentation_hall (
    presentation_hall_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(), -- Llave primaria con UUID
    name VARCHAR(255) NOT NULL                                         -- Nombre del salón de presentaciones
);


-- PRESENTACION/DEFENSA DE TESIS
CREATE TABLE thesis_defense (
    thesis_defense_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),         -- Llave primaria con UUID
    presentation_hall_id UUID REFERENCES presentation_hall(presentation_hall_id), -- Relación con el salón de presentaciones (UUID)
    thesis_process_id UUID REFERENCES thesis_process(thesis_process_id),   -- Relación con el proceso de tesis (UUID)
    thesis_phases_id UUID REFERENCES thesis_phases(thesis_phases_id),      -- Relación con la fase de tesis (UUID)
    teacher_id UUID REFERENCES teachers(teacher_id),                       -- Relación con el profesor (UUID)
    student_id UUID REFERENCES students(student_id),                       -- Relación con el estudiante (UUID)
    period_academic_id UUID REFERENCES period_academic(period_academic_id), -- Relación con el periodo académico (UUID)
    date_time TIMESTAMP NOT NULL,                                          -- Fecha y hora de la defensa
    secretary_id UUID REFERENCES users(user_id)                            -- Relación con el secretario de la defensa (UUID)
);


-- FASES FALLIDAS
CREATE TABLE failed_phases (
    failed_phases_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),    -- Llave primaria con UUID
    thesis_process_id UUID REFERENCES thesis_process(thesis_process_id),  -- Relación con el proceso de tesis (UUID)
    thesis_phases_id UUID REFERENCES thesis_phases(thesis_phases_id),    -- Relación con las fases de tesis (UUID)
    teacher_id UUID REFERENCES teachers(teacher_id),                     -- Relación con el profesor (UUID)
    student_id UUID REFERENCES students(student_id),                     -- Relación con el estudiante (UUID)
    thesis_id UUID REFERENCES thesis_titles(thesis_id),                  -- Relación con el título de tesis (UUID)
    period_academic_id UUID REFERENCES period_academic(period_academic_id), -- Relación con el periodo académico (UUID)
    thesis_process_phases_id UUID REFERENCES thesis_process_phases(thesis_process_phases_id), -- Relación con la fase del proceso de tesis (UUID)
    per_period_academic_id UUID REFERENCES period_academic(period_academic_id), -- Relación con el periodo académico en particular (UUID)
    failed_at TIMESTAMP DEFAULT NOW(),                                   -- Fecha en que la fase fue marcada como fallida
    reason_failed TEXT                                                   -- Razón por la que la fase falló
);


--INDEXES
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_students_user_id ON students(user_id);
CREATE INDEX idx_teachers_user_id ON teachers(user_id);
CREATE INDEX idx_students_dni ON students(dni);