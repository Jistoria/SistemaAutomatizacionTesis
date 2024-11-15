import { menusService } from "~/services/panelModel/menusService"

export const panel = defineStore('panel',{
    state: () =>({
        name:'',
        url:'',
        icon:'',
        menus_data:[],
        stuendent_data:[],
        
    }),
    actions:{
        async menus(){
            const response = await menusService.menus()
            console.log(response.data)
            this.menus_data = response.data;

        },
        async dataDashboard(){
            try {
                const response = await menusService.getDataDashborad();
                return response
            } catch (error) {
                
            }
        },
        async getlistStudents(){
            const data = [
                {
                  user: {
                    name: "Juan Pérez",
                    phase: "Fase 1",
                    status: "Habilitado",
                  },
                  description:
                    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis est necessitatibus, quod aut expedita quo aperiam nobis nostrum corrupti dolores asperiores obcaecati repellat.",
                  evaluations: [
                    {
                      category: "Diseño",
                      scores: [
                        { partial: "Parcial 1", score: 8 },
                        { partial: "Parcial 2", score: 7 },
                      ],
                    },
                    {
                      category: "Resultado",
                      scores: [
                        { partial: "Parcial 1", score: 9 },
                        { partial: "Parcial 2", score: 6 },
                      ],
                    },
                  ],
                },
                {
                  user: {
                    name: "María Gómez",
                    phase: "Fase 2",
                    status: "No Habilitado",
                  },
                  description:
                    "Atque, dolorum! Voluptatum nesciunt pariatur amet obcaecati reiciendis veniam distinctio libero ad dolorum, ducimus alias aliquid inventore.",
                  evaluations: [
                    {
                      category: "Diseño",
                      scores: [
                        { partial: "Parcial 1", score: 7 },
                        { partial: "Parcial 2", score: 8 },
                      ],
                    },
                    {
                      category: "Resultado",
                      scores: [
                        { partial: "Parcial 1", score: 6 },
                        { partial: "Parcial 2", score: 7 },
                      ],
                    },
                  ],
                },
            ];
            this.stuendent_data = data;  
            return

            try {
                const response = await menusService.getListStudents();
                return response
            } catch (error) {
                
            }
        }

    }

})