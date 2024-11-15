import { defineStore } from 'pinia'
import { studentService } from '~/services/studentModel/studentService'
import { useIdb } from '~/composables/idb'

export const student = defineStore('student', {
  state: () => ({
    dashboard_status: [],
    faseActual: [],
    requeriments: [],
  }),

  actions: {
    // Sincronizar los datos del estado desde IndexedDB al cargar el store
    async syncFromDB() {
        const { consults } = useIdb();
      
        // Consultar y asignar cada dato individualmente
        const dashboardStatus = await consults('student_data', 'dashboard_status');

      
        const faseActual = await consults('student_data', 'faseActual');

      
        const requeriments = await consults('student_data', 'requeriments');

      
        // Asignar al estado del store
        this.dashboard_status = dashboardStatus || [];
        this.faseActual = faseActual || {};
        this.requeriments = requeriments || [];
      },
      

    // Obtener datos desde el servicio y guardar tanto en el estado como en IndexedDB
    async getDataStatus(token) {
        const { setData } = useIdb()
      
        // Llamadas al servicio para obtener los datos del estudiante
        const response = await studentService.getDataStatus(token)
        
        await setData('student_data', 'dashboard_status', response.response.data);

        await setData('student_data', 'faseActual', response.faseActual);
      
        const response2 = await studentService.getRequeriments(token, response.faseActual.thesis_process_phases_id)

        await setData('student_data', 'requeriments', response2.data);
      
        // Sincronizar el estado con los datos recién guardados en IndexedDB
        await this.syncFromDB()
      },
      async clearStore() {
        const { clearData } = useIdb();
  
        // Limpiar los datos en IndexedDB
        await clearData('student_data');
  
        // Restablecer el estado del store
        this.dashboard_status = [];
        this.faseActual = {};
        this.requeriments = [];
        
        console.log('Datos del store y de IndexedDB limpiados.');
      },

  },
})
