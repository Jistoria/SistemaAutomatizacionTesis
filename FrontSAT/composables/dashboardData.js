import { ref } from "vue"
import { panel } from "~/stores/panel/panel";

export const dashboardData = ref({
    estudiantes: {
      title: "Estudiantes",
      items: [
        { icon: "bi-people-fill",color:'bg-secondary', label: "Total de estudiantes", value: 0 },
        { icon: "bi-person-check-fill",color:'bg-secondary', label: "Estudiantes Habilitados", value: 0 },
        { icon: "bi-pencil-square",color:'bg-secondary', label: "En materia Diseño", value: 0 },
        { icon: "bi-bar-chart",color:'bg-secondary' ,label: "En materia Resultados", value: 0 },
      ],
    },
    tribunales: {
      title: "Tribunales",
      items: [
        { icon: "bi-building", color:'bg-secondary', label: "Total de Tribunales", value: 0 },
        { icon: "bi-building-check", color:'bg-secondary', label: "Tribunales Activos", value: 0 },
        { icon: "bi-calendar3", color:'bg-success', label: "Sesiones Agendadas", value: 0 },
        { icon: "bi-people", color:'bg-secondary', label: "Miembros Totales", value: 0 },
      ],
    },
});

export async function getDashboardData() {
    const panelStore = panel();
    try {
        const response = await panelStore.dataDashboard()
        const data = response.data;
        Object.keys(data).forEach((section) => {
            if (dashboardData.value[section]) {
              data[section].forEach((item) => {
                const existingItem = dashboardData.value[section].items.find(i => i.label === item.title);
                if (existingItem) {
                  existingItem.value = item.value;
                }
              });
            }
        });
    } catch (error) {
        console.error("Error al obtener los datos del dashboard:", error);

    }

}