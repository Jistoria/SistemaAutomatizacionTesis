import { defineStore } from 'pinia'
import { studentService } from '~/services/studentModel/studentService'
import { docService } from '~/services/docModel/docService'
import { useIdb } from '~/composables/idb'
import { useRouter } from 'vue-router'
import auth from '~/plugins/auth'



export const student = defineStore('student',{
    state: () =>({
        dashboard_status:[],
        faseActual: [],
        requeriments: [],
        generalData: [],

        //Variables Extras
        nextFase:[],
        prerequisitos: [],
        prerequsito: false,

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
                console.log('response', response)

                if(response.faseActual){
                    console.log('hola')
                    await setData('student_data', 'faseActual', response.faseActual);
              
                    const response3 = await studentService.getRequeriments(token, response.faseActual.thesis_process_phases_id)
            
                    await setData('student_data', 'requeriments', response3.data);


                }else if(response.nextFase && response.requeriment == false){ 
                    const { deleteField } = useIdb()
                    await deleteField('student_data', 'faseActual')
                    console.log('nextFase', response.nextFase)
                    console.log('no requisitos')
                    await setData('student_data', 'nextFase', response.nextFase);
                    
                }else if(response.nextFase && response.requeriment == true){
                    const { deleteField } = useIdb()
                    await deleteField('student_data', 'faseActual')
                    console.log('requisitos')
                    await setData('student_data', 'nextFase', response.nextFase);
                    console.log('response', response)
                    await setData('student_data', 'prerequisitos', response.requeriments);
                    this.prerequsito = true
                }

                const response2 = await studentService.getGeneralData(token)
    
                await setData('student_data', 'generalData', response2.data);


                await this.syncFromDB()

                this.isLoaded = true;

                return
            }

        },
        async sendRequeriments(token, requeriments){
            const response = await docService.sendRequeriment(token, requeriments)
            await this.updateRequeriments(token)
            return response
        },
        async sendPreRequeriments(token, requeriments){
            const response = await docService.sendPreRequeriment(token, requeriments)
            if(response.success === true){
                console.log('Requisitos enviados')
                this.isLoaded = false
                await this.getDataStatus(token)
            }
            return 
        },
        async syncFromDB() {
            
            const { consults } = useIdb();
          
            // Consultar y asignar cada dato individualmente
            const dashboardStatus = await consults('student_data', 'dashboard_status');

            const faseActual = await consults('student_data', 'faseActual');
    
          
            const requeriments = await consults('student_data', 'requeriments');

            const generalData = await consults('student_data', 'generalData');

            const nextFase = await consults('student_data', 'nextFase');

            const prerequisitos = await consults('student_data', 'prerequisitos');
    
          
            // Asignar al estado del store
            this.dashboard_status = dashboardStatus || [];
            this.faseActual = faseActual || {};
            this.requeriments = requeriments || [];
            this.generalData = generalData || [];
            this.nextFase = nextFase || [];
            this.prerequisitos = prerequisitos || [];
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
            this.nextFase = [];
            this.prerequisitos = [];
            
            console.log('Datos del store y de IndexedDB limpiados.');

            
            
        },
        resetDefault(){
            this.isLoaded = false;
        },
        async mandarSolicitud(token){
            const thesis_process_id = this.generalData.thesis_process_id
            const phase_name = this.nextFase.data.phase_name
            const requested_phase_id = this.nextFase.data.thesis_phases_id
            const response = await studentService.mandarSolicitud(token, thesis_process_id, phase_name, requested_phase_id)
            if (response.success === true){
                console.log('Solicitud Enviada')
                this.isLoaded = false
                await this.getDataStatus(token)
            }
        },
        async updateStudent(token){
            this.isLoaded = false
            await this.getDataStatus(token);
        }
    },
})
