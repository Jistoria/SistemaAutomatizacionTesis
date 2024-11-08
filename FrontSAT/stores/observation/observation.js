import { observationService } from "~/services/observationModel/observationService"
export const observation = defineStore('observation',{
    state: () =>({
        observationdata: [],
    }),
    actions:{
        async createObservation(){
            try {
                
            } catch (error) {
                
            }
        },
        async getObservation(){
            try {
                const observatio_place =[
                    {
                        id:1,
                        numobservation:1,
                        username:'William Zamora Jesús',
                        date:'2024-11-02',
                        content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."
                    },
                    {
                        id:2,
                        numobservation:2,
                        username:'Ana María Pérez',
                        date:'2024-11-03',
                        content: "Proin malesuada nibh nec sem vestibulum, a sagittis elit volutpat. Maecenas ac enim libero. Vivamus aliquam risus sit amet nisi tincidunt, sed fermentum lacus cursus. Nullam pharetra justo in dapibus facilisis."
                    },
                    {
                        id:3,
                        numobservation:3,
                        username: "Carlos Rodríguez",
                        date: "2024-11-04",
                        content: "Curabitur et ultricies lectus. Nullam in leo eu ligula interdum ullamcorper. Nam venenatis, libero vel ultricies tempor, neque metus finibus nisl, ac volutpat nisi felis non libero. Vivamus convallis odio velit, eget tristique lacus aliquet a."
                    },
                ]
                this.observationdata = observatio_place

            } catch (error) {
                
            }
        },
        async deleteObservation(){
            try {
                
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