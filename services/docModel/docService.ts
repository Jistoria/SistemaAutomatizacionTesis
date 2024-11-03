import { sweetAlert } from "#imports";
import { useNuxtApp } from '#app';
const swal = sweetAlert() as ReturnType<typeof sweetAlert>;
class DocService {
    private fetchClient: any | null = null;

    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }

    async sendPdf(token: string, file: File) {
        const fetchClient = this.getFetchClient();
        const formData = new FormData();
        formData.append('file', file);
        try {
            const response = await fetchClient('/import-data-file/pdf-thesis', {
                method: 'POST',
                headers: {
                    Auhorization: 'Bearer '+ token
                },
                body: formData,
            });
            console.log('Respuesta:', response);
            if (response.success == true) {
                swal.showAlert('warning', 'right', {
                    title: 'El pdf se esta procesando...',
                    text: '',
                    confirmType: 'timer'
                });
                return response;
            }
        }catch (error) {
            swal.showAlert('error', 'right', {
                title: 'El pdf no se subio correctamente',
                text: '',
                confirmType: 'timer'
            });
            return error;
        }

    }

}

export const docService = new DocService();