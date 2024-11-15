import { student } from "~/stores/dashboards/student";
import { auth } from "~/stores/auth/auth";
import { roles } from "#imports";

export const firstLoad = async () => {
    const studentStore = student();
    const authStore = auth();
    console.log("Sesion Activa 2");
    console.log(authStore.role[0]);
    if(authStore.role[0] == roles.rol5){
        await studentStore.getDataStatus(authStore.token);
    }
    if(authStore.role[0] == roles.rol4){
        // Acción para rol4
    }
    if(authStore.role[0] == roles.rol3){
        // Acción para rol3
    }
    if(authStore.role[0] == roles.rol2){
        // Acción para rol2
    }
    if(authStore.role[0] == roles.rol1){
        //rol admin
        // Acción para rol1
    }

    return true;

}