import { observationService } from "~/services/observationModel/observationService"
export const observation = defineStore('observation',{
    state: () =>({
        observationdata: [],
    }),
    actions:{
        async sendObservation(id,id_requeriment,content){
            try {
                await observationService.sendObservation(id,id_requeriment,content)
            } catch (error) {
                
            }
        },
        async getObservation(id, id_requeriment){
            try {
                try {
                    const response = await observationService.getObservation(id, id_requeriment);
                    this.observationdata = response.data;
                    console.log(this.observationdata);
                } catch (error) {
                    
                }
            } catch (error) {
                
            }
        },
        async deleteObservation(id,id_requeriment){
            try {
                const response = await observationService.deleteObservation(id,id_requeriment);
                console.log(response);
            } catch (error) {
                
            }
        },
        async updateObservation(){
            try {
                
            } catch (error) {
                
            }
        }

    },

})