import {docService} from '~/services/docModel/docService'
export const documents = defineStore('documents',{
    state: () =>({
        
    }),
    actions:{
        async sendPdf(token,id, file){
            try {
                await docService.sendPdf(token,id, file)
            } catch (error) {
                console.error('Error en sendPdf en el store:', error);
                throw error;
            }
        },
    },
})