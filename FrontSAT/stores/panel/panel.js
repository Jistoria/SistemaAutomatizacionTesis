import { menusService } from "~/services/panelModel/menusService"

export const panel = defineStore('panel',{
    state: () =>({
        name:'',
        url:'',
        icon:'',
        menus_data:[],
        
    }),
    actions:{
            //el with credentials
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
        }

    }

})