import { sweetAlert } from "#imports";
import { useNuxtApp } from '#app';
import { admin } from "~/stores/dashboards/admin";
import { usePaginationStore } from "~/stores/pagination/pagination";

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
    async sendPreRequeriment(token: string, formData: FormData) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/import-data-file/document-pre-requirement-student', {
                method: 'POST',
                headers: {
                    Auhorization: 'Bearer '+ token
                },
                credentials: 'include',
                body: formData,
            });
            console.log(response);
            if (response.success == true) {
                swal.showAlert('success', 'right', {
                    title: response.message,
                    text: '',
                    confirmType: 'timer'
                });
                return response;
            }
            
        } catch (error) {
            swal.showAlert('error', 'right', {
                title: 'El requerimiento no se subio correctamente',
                text: '',
                confirmType: 'timer'
            });
            return error;
        }
    }
    async sendRequeriment(token: string, formData: FormData) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/import-data-file/document-requirement-student', {
                method: 'POST',
                headers: {
                    Auhorization: 'Bearer '+ token
                },
                credentials: 'include',
                body: formData,
            });
            console.log(response);
            if (response.success == true) {
                swal.showAlert('success', 'right', {
                    title: response.message,
                    text: '',
                    confirmType: 'timer'
                });
                return;
            }
            
        } catch (error) {
            swal.showAlert('error', 'right', {
                title: 'El requerimiento no se subio correctamente',
                text: '',
                confirmType: 'timer'
            });
            return error;
        }
    }
    async sendPdf(token: string,id: string ,file: File) {
        const fetchClient = this.getFetchClient();
        const formData = new FormData();
        formData.append('file', file);
        this.listenChannel(id);
        try {
            const response = await fetchClient('/import-data-file/pdf-thesis', {
                method: 'POST',
                headers: {
                    
                    Auhorization: 'Bearer '+ token
                },
                credentials: 'include',
                body: formData,
            });
            if (response.success == true) {
                swal.showLoadingToast('El documento esta siendo procesado...');
                return;
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
    async listenChannel(id: string) {
        const { $echo } = useNuxtApp();

        // Configuración del canal público
        const channelName = `adminTesis.${id}`;
        const channel = $echo.channel(channelName);
        const usePagination = usePaginationStore();
        // Asegurarse de escuchar el evento específico NotificationDataProcess
        try {
            channel.listen('.NotificationDataProcess', async(data: any) => {
                if (data.status == 'success') {
                    swal.closeLoadingToast();
                    await swal.showAlert('success', 'right', {
                        title: 'El pdf se proceso correctamente',
                        text: '',
                        confirmType: 'timer'
                    });
                    const adminStore = admin();
                    swal.showLoadingToast('Actualizando Datos...');
                        await adminStore.getProcesosTesis(1,'','');
                        await usePagination.applyFiltersAndSearch({search:'', filter:''});
                    swal.closeLoadingToast();
                    
                }else{
                    swal.closeLoadingToast();
                    swal.showAlert('error', 'right', {
                        title: 'El pdf no se proceso correctamente',
                        text: '',
                        confirmType: 'timer'
                    });
                }
                channel.stopListening('.NotificationDataProcess');
            });
        } catch (error) {
            console.error('Error al escuchar el evento NotificationDataProcess', error);
        }
    }
    
}

export const docService = new DocService();