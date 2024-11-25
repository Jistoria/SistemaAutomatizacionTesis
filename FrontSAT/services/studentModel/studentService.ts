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

            if (faseActual != null) {
                return {response, faseActual};
            }else{
                const nextFase = await this.getNextPhase(token);
                console.log('Respuesta:', nextFase);
                return {response, faseActual: null};
            }
            
            

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
