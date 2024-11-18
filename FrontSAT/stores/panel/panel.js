import { menusService } from "~/services/panelModel/menusService"

export const panel = defineStore('panel',{
    state: () =>({
        name:'',
        url:'',
        icon:'',
        menus_data:[],
        stuendent_data:[],
        current_page: 1,
        last_page: 0,
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
        async getlistStudents(page=1,filter='',search=''){
          
            if(this.isLoaded){

            }else{
              try {
                  this.isLoaded = true;
                  const response = await menusService.getListStudents(page,filter,search);
                  console.log('respuesta obtenida', response.data);
                  
                  this.stuendent_data = response.data;
                  this.current_page = response.data.current_page;
                  this.last_page = response.data.last_page;
                  this.isLoaded=false
              } catch (error) {
                  
              }
            }
        },

        async fetchPage(page = 1, filter='', search =''){
          console.log(page)
          const response = await menusService.getListStudents(page,filter,search);
          return response;
  
        },

        async detailStudent(id){
          const response = await menusService.detailStudent(id);
          console.log('id', id);
          
        }
    }

})