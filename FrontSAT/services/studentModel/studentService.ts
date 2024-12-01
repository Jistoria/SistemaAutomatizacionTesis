import { sweetAlert } from "~/composables/sweetAlert";
interface Phase {
    approval: boolean;
    date_end: string | null;
    date_start: string | null;
    phase_name: string;
    phase_order: number;
    progress: number;
    state_now: string;
    thesis_process_phases_id: string | null;
  }
  
  interface Module {
    module_name: string;
    module_order: number;
    phases: Phase[];
  }
  
  interface DashboardResponse {
    data: Module[];
  }
class StudentService {
    private fetchClient: any | null = null;

    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    

    async getDataStatus(token: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/thesis-process-student/dashboard', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token
                },
            });
            console.log('Respuesta:', response);

            const faseActual = await this.getPhaseInProcess(response.data);
            console.log('Fase actual:', faseActual);

            if (faseActual != null) {
                return {response, faseActual};
            }else{
                const nextFase = await this.getNextPhase(token);
                console.log('Respuesta:', nextFase);
                if (nextFase.data.pre_requirements) {
                    console.log('Requerimientos:', nextFase.data.pre_requirements);
                    return {response, nextFase, requeriments: nextFase.data.pre_requirements, requeriment: true};
                }else{
                    return {response, nextFase, requeriment: false};
                }
                
            }
            
            

        } catch (error) {
            return error;
        }
    }
    async mandarSolicitud(token: string, thesis_process_phases_id: string, phase_name: string, requested_phase_id: string) {
        const swal = sweetAlert();
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/request-phases/student', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token
                },
                body: JSON.stringify({
                    thesis_process_id: thesis_process_phases_id,
                    phase_name: phase_name,
                    requested_phase_id: requested_phase_id
                })
            });
            if(response.success == true){
                swal.showAlert('success','right',{title: 'Solicitud enviada', text: '',confirmType: 'timer'})
            }else{
                swal.showAlert('error','right',{title: 'Algo salio mal', text: '',confirmType: 'timer'})
            }
            return response;
        } catch (error) {
            return error;
        }
    }

    async getNextPhase(token: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/thesis-process-student/next-phase/', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token
                },
            });

            return response;
        } catch (error) {
            return error;
        }
    }



    async getGeneralData(token: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/thesis-process-student/thesis-process', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token
                },
            });

            return response;
        } catch (error) {
            return error;
        }
    }
    async getRequeriments(token: string, id: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/thesis-process-student/requirements-phase/'+id, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token
                },
            });

            return response;
        } catch (error) {
            return error;
        }
    }
    async getPhaseInProcess(modules: Module[]): Promise<Phase | null> {
        for (const module of modules) {
          const foundPhase = module.phases.find(phase => phase.state_now === 'En proceso');
          if (foundPhase) {
            return foundPhase;
          }
        }
        return null;
    }
}

// Exporta una instancia de la clase para su uso en el store u otras partes de la aplicación
export const studentService = new StudentService();
