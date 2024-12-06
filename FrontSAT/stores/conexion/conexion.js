
export const conexion = defineStore('conexion',{
    state: () =>({
        status: '',
    }),
    actions:{
        setConnected() {
            this.status = 'connected';
          },
          setReconnecting() {
            this.status = 'reconnecting';
          },
          setDisconnected() {
            this.status = 'disconnected';
          },
    },
})