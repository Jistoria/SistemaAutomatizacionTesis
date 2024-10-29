export const auth = defineStore('auth',{
    state: () =>({
        placeholder:'prueba pinia nesteado',
        user: [],
        token: null,
        session: false,
        role: null,
    }),
    actions:{
        async login(user,token){
            this.user = user
            this.token = token
            this.session = true
            this.role = user.role
        },
        async logout(){
            this.user = []
            this.token = null
            this.session = false
            this.role = null
        }
    }
})