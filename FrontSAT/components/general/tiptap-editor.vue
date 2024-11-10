  
<script setup>
import { Editor, EditorContent, useEditor } from '@tiptap/vue-3'
import { watch } from 'vue';
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link';
import Paragraph from '@tiptap/extension-paragraph';
import Code from '@tiptap/extension-code';
import { request } from '~/stores/request/request';
const requestStore = request();
const props = defineProps({
    modelValue: {
        type: String,
    },
    isModalOpen: {
        type: Boolean,
        default: true,
    },
})
const emit = defineEmits(['update:modelValue'])

const editor = ref(null);
const fileInput = ref(null); 
const count = ref(1);

onMounted(() => {
    editor.value = new Editor({
        editorProps:{
            attributes: {
                class: 'border border-gray-400 p-4 min-h-[12rem] max-h-[12rem] overflow-y-auto outline-none prose max-w-none',
            },
        },
        content: props.modelValue,
        onUpdate({ editor }) {
            emit('update:modelValue', editor.getHTML())
        },
        extensions: [
            StarterKit,
            Underline,
            Link.configure({
                openOnClick: true,
                defaultProtocol: 'https',
            }),
        ],
    })
})
watch(
    () => props.isModalOpen,
  (newVal) => {
    if (!newVal) {
      destroyEditor();
    }
  }
)
function destroyEditor() {
    if (editor.value) {
        editor.value.options.content = null;
        editor.value.content = null;
    }
  
}
onBeforeUnmount(() => {
  if (editor.value) {
    editor.value.destroy()
  }
})
function triggerFileUpload() {
    fileInput.value.click();
}

async function handleFileUpload(event) {
  const files = event.target.files;
  count.value = count.value + files.length;
  if (count.value === 3) return;

  for (let file of files) {
    try {
       //En espera de la peticion para que me traiga la url
       // Simula un retraso como si estuviera subiendo el archivo al servidor
                await new Promise((resolve) => setTimeout(resolve, 1000));
                // Genera una URL de prueba para el archivo
                const fakeUrl = `https://example.com/uploads/${file.name.replace(/\s+/g, '_')}`;
                // Inserta el nombre del archivo como un enlace en el contenido del editor
                editor.value
                    .chain()
                    .focus()
                    .insertContent(`<a href="${fakeUrl}" target="_blank">${file.name}</a> `) // Añade un espacio después del enlace para separar los archivos
                    .run();
                console.log(`Archivo simulado subido: ${fakeUrl}`);
        //salida
    } catch (error) {
      console.error('Error al simular la subida del archivo:', error);
    }
  }
  fileInput.value.value = '';
}

</script>
<template>
    <div class="container mx-auto max-w-4x; my-8 ">
        <section v-if="editor" class="buttons flexs items-center flex-wrap gap-x-4 border border-gray-400">
            <div class="grid grid-cols-2 gap-4">
                <div >
                    <button @click="editor.chain().focus().toggleBold().run()" :disabled="!editor.can().chain().focus().toggleBold().run()" :class="{ 'bg-gray-200 rounded': editor.isActive('bold') }" >
                        <i class="bi bi-type-bold icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleItalic().run()" :disabled="!editor.can().chain().focus().toggleItalic().run()" :class="{ 'bg-gray-200 rounded': editor.isActive('italic') }" >
                        <i class="bi bi-type-italic icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'is-active': editor.isActive('underline') }">
                        <i class="bi bi-type-underline icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }" class="p-1">
                        <i class="bi bi-type-h1 icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }" class="p-1">
                        <i class="bi bi-type-h2 icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }" class="p-1">
                        <i class="bi bi-list-ul icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor.isActive('orderedList') }" class="p-1">
                        <i class="bi bi-list-ol icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'is-active': editor.isActive('blockquote') }" class="p-1">
                        <i class="bi bi-quote icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().undo().run()" :disabled="!editor.can().chain().focus().undo().run()" class="p-1">
                        <i class="bi bi-arrow-counterclockwise icon_size"></i>
                    </button>
                    <button @click="editor.chain().focus().redo().run()" :disabled="!editor.can().chain().focus().redo().run()" class="p-1">
                        <i class="bi bi-arrow-clockwise icon_size"></i>
                    </button>
                    <button @click="triggerFileUpload" >
                        <input type="file" @change="handleFileUpload" style="display: none" ref="fileInput" />
                        <i class="bi bi-file-earmark-arrow-up-fill icon_size"></i>
                    </button>
                </div>
                <div  class="flex justify-end">
                    <button type="submit"class=" pe-2">
                        Enviar Solicitud
                    </button>
                </div>
            </div>
           

        </section>
        <editor-content :editor="editor" />
    </div>
</template>
<style>

</style>