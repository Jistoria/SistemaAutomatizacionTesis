import { menusService } from "~/services/panelModel/menusService"

export const panel = defineStore('panel',{
    state: () =>({
        name:'',
        url:'',
        icon:'',

        menus_data:[],
        stuendent_data:[],
        isLoaded:false
        
    }),
    actions:{
        async menus(){
            const response = await menusService.menus()
            console.log(response.data)
            this.menus_data = response.data;

        },
        async dataDashboard(){
          if(this.isLoaded){

          }else{
            try {
              this.isLoaded = true;
              const response = await menusService.getDataDashborad();
              return response
            } catch (error) {
                
            }
          }
        },
        async getlistStudents(){
            if(this.isLoaded){

            }else{
              try {
                  this.isLoaded = true;
                  const response = await menusService.getListStudents();
                  console.log('respuesta obtenida', response.data);
                  this.stuendent_data = response.data;
                  return response.data
              } catch (error) {
                  
              }
            }
        },
        async detailStudent(id){
          console.log('id', id);
          
        }
    }

})