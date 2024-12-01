import { sweetAlert } from "#imports";
import { useNuxtApp } from '#app';


const swal = sweetAlert() as ReturnType<typeof sweetAlert>;
class NotifyService {
    private fetchClient: any | null = null;

    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    
    async listenChannel(id: string) {
        const { $echo } = useNuxtApp();
        console.log('Escuchando canal de notificaciones:', id);

        // Configuración del canal público
        const channelName = `notification.${id}`;
        const channel = $echo.channel(channelName);
        // Asegurarse de escuchar el evento específico NotificationDataProcess
        try {
            channel.listen('.NotificationUser', (data: any) => {
                console.log('Evento NotificationUser:', data);
                channel.stopListening('.NotificationUser');
            });
        } catch (error) {
            console.error('Error al escuchar el evento NotificationUser', error);
        }
    }
    
}

export const notifyService = new NotifyService();