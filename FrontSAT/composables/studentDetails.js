export const Fase = [
    {
        phase_name:'Diseño',
        Phases_select:'Fase Diseño',
    },
    {
        phase_name:'Resultados',
        Phases_select:'Fase Resultado',
    }
]
export const Requerimientos = [
    {
        name:'Pendientes',
        icon:'bi bi-check-circle-fill',
        state:'Pendiente',
        bg:'bg-neutral',
    },
    {
        name:'Enviados',
        icon:'bi bi-envelope-fill',
        state:'Enviado',
        bg:'bg-accent',
    },
    {
        name:'Aceptados',
        icon:'bi bi-check-circle-fill',
        icon_use:'bi bi-check-circle-fill',
        btn_color:'btn_suceess',
        state:'Aprobado',   
        bg:'bg-success'
    },
    {
        icon_use:'bi bi-x-circle-fill',
        bg_use:'bg-error',
        btn_color:'btn_error',
        state:'Rechazado',
    },
];
export const Requerimientos_use = [
    {
        icon_use:'bi bi-x-circle-fill',
        btn_color:'btn_error',
        state:'Rechazado',
    },
    {
        icon_use:'bi bi-check-circle-fill',
        btn_color:'btn_suceess',
        state:'Aprobado', 
    },
]