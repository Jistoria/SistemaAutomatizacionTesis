import { adminService } from "~/services/adminModel/adminService"
import { useIdb } from '~/composables/idb'
export const admin = defineStore('admin',{
    state: () =>({
        estudiantes: [],
        docentes: [],
        procesosTesis : [],
    }),
    actions:{
        async syncFromIdb(){
            const {consults} = useIdb()
            this.estudiantes = await consults('admin_data', 'estudiantes')
            this.docentes = await consults('admin_data', 'docentes')
            this.procesosTesis = await consults('admin_data', 'procesosTesis')
        },
        async getEstudiantes( page=1, search= '', filter= ''){
            const { setData } = useIdb()
            const response = await adminService.getEstudiantes(page, filter, search)
            if(response.success == true){
                await setData('admin_data', 'estudiantes', response.data)
            }
            await this.syncFromIdb()
            return response
        },
        
        async getDocentes(page=1, search= '', filter= ''){
            const { setData } = useIdb()
            const response = await adminService.getDocentes(page, filter, search)
            console.log('response', response)
            if(response.success == true){
                await setData('admin_data', 'docentes', response.data)
            }
            await this.syncFromIdb()
            return response
        },
        async getProcesosTesis(page=1, search= '', filter= ''){
            const { setData } = useIdb()
            const response = await adminService.getProcesosTesis(page, filter, search)
            if(response.success == true){
                await setData('admin_data', 'procesosTesis', response.data)
            }
            await this.syncFromIdb()
            return response
        },
        async fetchPage(page=1, filter='', search='',caso){
            
            if(caso == 'estudiantes'){
                const response = await this.getEstudiantes(page, search, filter)
                console.log('response', response)
                return response
            }else if(caso == 'docentes'){
                const response = await this.getDocentes(page, search, filter)
                return response
            }else if(caso == 'procesosTesis'){
                const response = await this.getProcesosTesis(page, search, filter)
                return response
            }
        },
        async dataAdmin(){
            this.getEstudiantes()
            this.getDocentes()
            this.getProcesosTesis()
        },
        async clearData(){
            const { clearData } = useIdb()
            await clearData('admin_data')
            this.estudiantes = []
            this.docentes = []
            this.procesosTesis = []
        }
        
        
    }
})
