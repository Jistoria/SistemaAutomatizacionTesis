
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
            return response;

        } catch (error) {
            return error;
        }
    }
}

// Exporta una instancia de la clase para su uso en el store u otras partes de la aplicación
export const studentService = new StudentService();
