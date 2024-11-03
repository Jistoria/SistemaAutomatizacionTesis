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
    async listenChannel(id: string) {
        const {$echo} = useNuxtApp();
        const pusher = $echo.connector.pusher;

        // Asegúrate de estar escuchando el canal
        const channel = $echo.channel('adminTesis.' + id);
        console.log('Escuchando canal', 'adminTesis.' + id);
        try {
            pusher.connection.bind('connected', () => {
                console.log('Conectado a Pusher');
                channel.listen('.NotificationDataProcess', (data: any) => {
                    console.log('Evento recibido:', data);
                });
            });
            
            pusher.connection.bind('disconnected', () => {
                console.log('Desconectado de Pusher');
            });
            
            pusher.connection.bind('error', (error: any) => {
                console.error('Error en la conexión:', error);
            });
            
        }catch (error) {
            console.error('Error al escuchar canal', error);
            
        }
        
        // Escuchar cambios en el estado de conexión
        
        
    }
    

}

export const docService = new DocService();