import { defineStore } from 'pinia'
import { studentService } from '~/services/studentModel/studentService'
import { docService } from '~/services/docModel/docService'
import { useIdb } from '~/composables/idb'

export const student = defineStore('student',{
    state: () =>({
        dashboard_status:[],
        faseActual: [],
        requeriments: [],
        generalData: [],
        //dato de carga 
        isLoaded: false,
    }),
    actions:{
        async getDataStatus(token){
            if(this.isLoaded === true){
                //Cuando se haga un Post y Un PUT dejar el isLoaded en false despues de que el Post Se complete
            }else{
                const { setData } = useIdb()
      
                // Llamadas al servicio para obtener los datos del estudiante
                const response = await studentService.getDataStatus(token)
                
                await setData('student_data', 'dashboard_status', response.response.data);
        
                await setData('student_data', 'faseActual', response.faseActual);
              
                const response2 = await studentService.getRequeriments(token, response.faseActual.thesis_process_phases_id)
        
                await setData('student_data', 'requeriments', response2.data);

                const response3 = await studentService.getGeneralData(token)

                await setData('student_data', 'generalData', response3.data);
              
                // Sincronizar el estado con los datos recién guardados en IndexedDB
                await this.syncFromDB()

                return
            }

        },
        async sendRequeriments(token, requeriments){
            const response = await docService.sendRequeriment(token, requeriments)
            await this.updateRequeriments(token)
            return response
        },
        async syncFromDB() {
            const { consults } = useIdb();
          
            // Consultar y asignar cada dato individualmente
            const dashboardStatus = await consults('student_data', 'dashboard_status');
    
          
            const faseActual = await consults('student_data', 'faseActual');
    
          
            const requeriments = await consults('student_data', 'requeriments');

            const generalData = await consults('student_data', 'generalData');
    
          
            // Asignar al estado del store
            this.dashboard_status = dashboardStatus || [];
            this.faseActual = faseActual || {};
            this.requeriments = requeriments || [];
            this.generalData = generalData || [];
          },
          async updateRequeriments(token){
            const { setData } = useIdb();
            const response2 = await studentService.getRequeriments(token, this.faseActual.thesis_process_phases_id)
            await setData('student_data', 'requeriments', response2.data);
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
            this.generalData = [];
            
            console.log('Datos del store y de IndexedDB limpiados.');
          },
    },
})
