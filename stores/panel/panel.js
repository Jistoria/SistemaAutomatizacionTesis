import { menusService } from "~/services/panelModel/menusService"

export const panel = defineStore('panel',{
    state: () =>({
        name:'',
        url:'',
        icon:'',
        menus:[],
        
    }),
    actions:{
            //el with credentials
        async menus(){
            const response = await menusService.menus()
            console.log(response.data)
            this.menus = response.data;

        }
    }
})