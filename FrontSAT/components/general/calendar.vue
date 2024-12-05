<script setup>
import Calendar from '~/components/general/calendar.vue';
import FullCalendar from '@fullcalendar/vue3';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import { sweetAlert } from '~/composables/sweetAlert';
const swal = sweetAlert();
const selectedRanges = ref([]);
const approvedEvents = ref([]);
const selectedEvent = ref(null);

const showAddEventModal = ref(false);
const newEvent = ref({
  title: '',
  description: '',
  start: '',
  end: '',
});
//te tengo que dar el inicio de semana y fin de semana4
//primero se llene la fecha siempre lo demas se llena luegeo de que la fecha haya sido validadado
const emit = defineEmits(['data-saved']);

const calendarOptions = ref({
  plugins: [interactionPlugin, timeGridPlugin],
  initialView: 'timeGridWeek',
  nowIndicator: true,
  selectable: true,
  events: approvedEvents.value,
  //
  select: (data) => {
    // Guardamos el rango seleccionado en la lista
    selectedRanges.value.push({
      start: data.startStr,
      end: data.endStr,
    });
  },
  //eventos
  eventClick:(info)=>{
    selectedEvent.value = {
      title: info.event.title,
      start: info.event.start,
      end: info.event.end,
      id: info.event.extendedProps.id,
      description: info.event.extendedProps.description || 'Sin descripción',
    };
    showModal.value = true;
  }
});
const showModal = ref(false); // Controla si el modal está abierto



const closeModal = (index) => {
  selectedRanges.value.splice(index, 1); // Elimina el rango del array
};
const formatDate = (dateStr) => {
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  };
  return new Date(dateStr).toLocaleDateString('es-ES', options);
};
//simulacion de la backend

const validateAndSaveRange = async(range)=>{
    await new Promise((resolve) => setTimeout(resolve, 1000));
    if (new Date(range.start).getHours() === 10) {
        return { success: false, message: 'La fecha no está disponible' };
    }
    return { success: true };
}
const saveDate = async (index) => {
    const range = selectedRanges.value[index];

    const result = await validateAndSaveRange(range);
    if (result.success) {
        approvedEvents.value.push({
            title: 'Reservado', //supospuestoponiendo que es lo que devuelve la back ya que cuando se haga un Onmounted se necesita que me traiga esos datos para dejar indicado
            start: range.start,
            end: range.end,
            classNames: ['confirmed-event'],
        });
        selectedRanges.value.splice(index, 1);
        emit('data-saved', range);
        selectedRanges.value.splice(index, 1);
        swal.showAlert('success','right',{title: 'Asignado', text: 'Horario Asignado',confirmType: 'timer'})
    } else {
        swal.showAlert('error','normal',{title: 'Error', text: 'No fue posible asignar este horario',confirmType: 'normal'})
    }
};
//simular Un Onmounted
const fetchApprovedEvents = async () => {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve([
        // Evento para hoy a las 9:00 AM
        {
          id: 'event1',
          title: 'Sustentación de Juan Pérez',
          start: new Date(new Date().setHours(9, 0, 0)).toISOString(),
          end: new Date(new Date().setHours(10, 0, 0)).toISOString(),
          description: 'Primera sustentación del día',
        },
        // Evento 2: Otro tema, misma hora
        {
          id: 'event2',
          title: 'Sustentación de María López',
          start: new Date(new Date().setHours(9, 0, 0)).toISOString(),
          end: new Date(new Date().setHours(10, 0, 0)).toISOString(),
          description: 'Sustentación paralela del día',
        },
        // Evento para dentro de dos días a las 11:00 AM
        {
          id: 'event3',
          title: 'Sustentación de Carlos García',
          start: new Date(new Date().setDate(new Date().getDate() + 2)).setHours(11, 0, 0),
          end: new Date(new Date().setDate(new Date().getDate() + 2)).setHours(12, 0, 0),
          description: 'Sustentacion',
        },
      ]);
    }, 1000); // Simula un retardo de 1 segundo
  });
};
onMounted(async () => {
  const events = await fetchApprovedEvents();
  // Agregar los eventos al array reactivo
  approvedEvents.value = events.map((event) => ({
    title: event.title,
    start: event.start,
    end: event.end,

    classNames: ['confirmed-event'], 
    extendedProps: {
      id: event.id,
      description: event.description,
    },
  }));
  calendarOptions.value.events = approvedEvents.value
});
</script>
<template>
  <!-- Se tiene que abrir un modal en daisy Ui en el que cuando haga click en la fecha se abra ese modal de fecha en la que esten las fechas de ese momento y preguntar si quiere guardarlo ahi -->
  <div class="flex justify-end">
    <button class="btn btn-primary my-4" @click="showAddEventModal = true">
      Agregar Nuevo Evento
    </button>
  </div>

  <FullCalendar 
  :options="calendarOptions" />
  <div v-for="(range, index) in selectedRanges" :key="range.start">
    <dialog :id="`modal_${range.start}`" class="modal modal-open">
      <div class="modal-box">
        <h3 class="text-lg font-bold">Seleccionaste esta fecha</h3>
        <p class="py-4">
          <strong>Inicio:</strong>{{ formatDate(range.start) }}<br />
          <strong>Fin:</strong> {{ formatDate(range.end) }}
        </p>
        <p>¿Quieres guardar este rango?</p>
        <div class="modal-action">
          <!-- Botón para guardar (puedes conectarlo a una API o función adicional) -->
          <button class="btn btn-success" @click="saveDate(index)">Guardar</button>
          <!-- Botón para cerrar el modal -->
          <button class="btn btn-error" @click="closeModal(index)">Cerrar</button>
        </div>
      </div>
    </dialog>
  </div>

  <!-- modal de los detalles de esa hora -->
   <!-- hacerle un crud para cambiar el estado de la sustentacion -->



  <dialog v-if="showModal"  class="modal modal-open">
    <div class="modal-box">
      <div class="py-4">
        <p><strong>Título:</strong> {{ selectedEvent?.title }}</p>
        <p><strong>Inicio:</strong> {{ formatDate(selectedEvent?.start) }}</p>
        <p><strong>Fin:</strong> {{ formatDate(selectedEvent?.end) }}</p>
      </div>
      <div class="modal-action">
        <form method="dialog">
        <button class="btn btn-error" @click="showModal = false">Cerrar</button>
        </form>
      </div>
    </div>
  </dialog>


  <dialog v-if="showAddEventModal"  class="modal modal-open">
    <div class="modal-box">
      <h3 class="font-bold text-lg">Agregar Nuevo Evento</h3>
      <div class="py-4">
        <div class="form-control mb-4">
          <label class="label"><span class="label-text">Título</span></label>
          <input
            type="text"
            class="input input-bordered"
            v-model="newEvent.title"
            placeholder="Título del evento"
          />
        </div>
        <div class="form-control mb-4">
          <label class="label"><span class="label-text">Descripción</span></label>
          <input
            type="text"
            class="input input-bordered"
            v-model="newEvent.description"
            placeholder="Descripción del evento"
          />
        </div>
        <div class="form-control mb-4">
          <label class="label"><span class="label-text">Inicio</span></label>
          <input
            type="datetime-local"
            class="input input-bordered"
            v-model="newEvent.start"
          />
        </div>
        <div class="form-control mb-4">
          <label class="label"><span class="label-text">Fin</span></label>
          <input
            type="datetime-local"
            class="input input-bordered"
            v-model="newEvent.end"
          />
        </div>
      </div>
      <div class="modal-action">
        <button class="btn btn-success" >Guardar</button>
        <button class="btn btn-error" @click="showAddEventModal = false">Cancelar</button>
      </div>
    </div>
  </dialog>

</template>
<style >
.confirmed-event {
  background-color: #4caf50 !important;
  border: 1px solid #388e3c !important;
  color: white !important;
}
.event-error{
    background-color: #f44336 !important;
    border: 1px solid #d32f2f !important;
    color: white !important;
}
</style>