import { courtService } from "~/services/court/courtService"
import { observation } from "../observation/observation";
export const court = defineStore('court',{
    state: () =>({
        courtlist:[],
    }),
    actions:{
        async getCourtList(){
            const response = await courtService.getCourt()
            const data_court={
                observation_data:{
                    title: "Desarrollo de una aplicación móvil híbrida para monitoreo de calidad del aire utilizando MQTT y Wi-Fi Direct con Raspberry Pi en la Universidad Laica Eloy Alfaro de Manabí, Matriz Manta",
                    studentInfo: {
                        name: "Mieles Alvarez Diego Jose",
                        tribunalDate: "07/10/2005"
                    },
                    grades: [
                        { label: "Calificación 1", value: 10.0, color: "bg-green-200 text-green-800" },
                        { label: "Calificación 2", value: 5.0, color: "bg-pink-200 text-pink-800" },
                        { label: "Calificación 3", value: 0.0, color: "bg-red-200 text-red-800" }
                    ],
                    average: 14.0
                }
            }
            this.courtlist = data_court;
        }
        
    }
})