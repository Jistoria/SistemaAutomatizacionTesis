import { defineStore } from 'pinia';
import { panel } from '../panel/panel';
import { admin } from '../dashboards/admin';
import { auth } from '../auth/auth';
import { sweetAlert } from '~/composables/sweetAlert';

export const usePaginationStore = defineStore('pagination', {
  state: () => ({
    current_page: 1,
    total_pages: 0,
    page_size: 1,
    listName: '',
    frontend_pages: 0,
    backend_pages: 0,
    backend_size: 0,
    visible_data: [],
    original_data: [],
    data_store: null,
    caso: '',
    isLoading: false, // Nueva bandera para el estado de carga
  }),
  
  actions: {
    // Configuración inicial
    async setupPagination({ dataStore, pageSize, listName, caso= '' }) {
      if (!dataStore || !dataStore.data) {
        console.error('El dataStore no está inicializado o no contiene datos.');
        return;
      }

      this.listName = listName;
      this.data_store = dataStore;
      this.original_data = [...dataStore.data];
      this.page_size = pageSize;
      this.backend_pages = dataStore.last_page;
      this.backend_size = dataStore.per_page;
      this.current_page = 1;
      this.caso = caso;

      this.calculateFrontendPages();
      this.updateVisibleData();
    },

    // Calcular el total de subpáginas en el frontend
    calculateFrontendPages() {
      // Total de subpáginas en todo el backend
      this.frontend_pages = Math.ceil(this.data_store.total / this.page_size);
    },
    async applyFiltersAndSearch({ search, filter }) {
      this.searchQuery = search;
      this.filter = filter;

      // Reinicia la paginación al aplicar filtros o búsqueda
      this.current_page = 1;
      let response;
      if (this.listName === 'estudiante') {
        const dataStore = panel();
        
      try {
        response = await dataStore.fetchPage(1,filter, search); // Pasar los parámetros al backend
        this.original_data = [...response.data.data];
        this.data_store = response.data;
        this.total_pages = response.data.last_page;
        this.updateVisibleData();
        this.calculateFrontendPages();
      } catch (error) {
        console.error('Error al aplicar búsqueda o filtro:', error);
      }
      }else if(this.listName === 'admin'){
          const adminStore = admin();
          try {
            response = await adminStore.fetchPage(1,filter, search, caso); // Pasar los parámetros al backend
            this.original_data = [...response.data.data];
            this.data_store = response.data;
            this.total_pages = response.data.last_page;
            this.updateVisibleData();
            this.calculateFrontendPages();
          } catch (error) {
            console.error('Error al aplicar búsqueda o filtro:', error);
          }
      }
      
    },

    // Actualizar los datos visibles
    updateVisibleData() {
      if (!this.original_data || this.original_data.length === 0) {
        console.warn('No hay datos disponibles en original_data para actualizar visible_data.');
        this.visible_data = [];
        return;
      }
    
      const subpagesPerBackendPage = Math.ceil(this.backend_size / this.page_size);
      const relativePageIndex = (this.current_page - 1) % subpagesPerBackendPage;
      const start = relativePageIndex * this.page_size;
      const end = Math.min(start + this.page_size, this.original_data.length); // Asegurarse de no exceder límites    
      this.visible_data = this.original_data.slice(start, end);
      console.log('Datos visibles actualizados:', this.visible_data);
    
      if (this.visible_data.length === 0) {
        console.warn('La página solicitada no tiene datos visibles. Verifique el estado de original_data o los parámetros.');
      }
    },
    

    // Solicitar datos del backend
    async fetchBackendPage(page) {
      const authStore = auth();
      if(authStore.online == true){
        if (this.isLoading) return; // Evitar múltiples solicitudes simultáneas
        this.isLoading = true;
        let response;
        try {
          console.log(`Solicitando datos del backend para la página ${page}`);
          if (this.listName === 'estudiante') {
            const dataStore = panel();
             response = await dataStore.fetchPage(page);
          }else if(this.listName === 'admin'){
            const adminStore = admin();
            response = await adminStore.fetchPage(page, this.filter, this.search, this.caso);
          }
          if (!response || !response.data || response.data.length === 0) {
            console.warn('No se recibieron datos válidos del backend.');
            this.original_data = []; // Vaciar original_data si no hay datos válidos
            return;
          }
          this.original_data = [...response.data.data];
          this.data_store.current_page = response.data.current_page;
          this.data_store.last_page = response.data.last_page;
      
          this.calculateFrontendPages();
          this.updateVisibleData();
        } catch (error) {
          console.error('Error al solicitar datos del backend:', error);
        } finally {
          this.isLoading = false;
        }
      }else{
        const swal = sweetAlert();
        swal.showAlert('warning', 'normal', {
          title: 'Atención',
          text: 'Para poder usar esta acción requiere tener conexión',
          confirmType: 'normal',
        });
      }
    },
    

    // Avanzar a la siguiente subpágina
    async nextPage() {
  
      if (this.isLoading) {
        console.warn('Cargando datos, por favor espera...');
        return;
      }
    
      if (this.current_page < this.frontend_pages) {
        this.current_page += 1;
    
        if (this.current_page % Math.ceil(this.backend_size / this.page_size) === 1 && this.data_store?.current_page < this.backend_pages) {
          await this.fetchBackendPage(this.data_store.current_page + 1);
        }
    
        this.updateVisibleData();
      }
    },
    
    async previousPage() {

      if (this.isLoading) {
        console.warn('Cargando datos, por favor espera...');
        return;
      }
    
      if (this.current_page > 1) {
        if ((this.current_page - 1) % Math.ceil(this.backend_size / this.page_size) === 0 && this.data_store?.current_page > 1) {
          await this.fetchBackendPage(this.data_store.current_page - 1);
        }
    
        this.current_page -= 1;
        this.updateVisibleData();
      } 
    },
    resetPagination() {
      this.current_page = 1;
      this.total_pages = 0;
      this.page_size = 1;
      this.listName = '';
      this.frontend_pages = 0;
      this.backend_pages = 0;
      this.backend_size = 0;
      this.visible_data = [];
      this.original_data = [];
      this.data_store = null;
      this.isLoading = false;
      this.caso = '';
    },
    
    
    
  },
});
