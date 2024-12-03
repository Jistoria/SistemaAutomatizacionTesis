import { sweetAlert } from "#imports";
import { useNuxtApp } from '#app';
import { notify } from "~/stores/notify/notify";

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
        const notifyStore = notify();
        console.log('Escuchando canal de notificaciones:', id);

        // Configuración del canal público
        const channelName = `notification.${id}`;
        const channel = $echo.channel(channelName);
        // Asegurarse de escuchar el evento específico NotificationDataProcess
        try {
            channel.listen('.NotificationUser', async (data: any) => {
                console.log('Evento NotificationUser:', data);
                await swal.showAlert(data.sweet_alert.icon,'right',{title: data.sweet_alert.title, text: '',confirmType: 'timer'})
                if(data.update == true){
                    swal.showLoadingToast('Actualizando Datos...');
                        await notifyStore.actionNotify(data.role);
                    swal.closeLoadingToast();
                }
            });0
        } catch (error) {
            console.error('Error al escuchar el evento NotificationUser', error);
        }
    }
    
}

export const notifyService = new NotifyService();