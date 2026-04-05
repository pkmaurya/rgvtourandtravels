<template>
  <div class="rich-text-editor">
    <QuillEditor 
      v-model:content="content" 
      contentType="html" 
      theme="snow" 
      toolbar="minimal"
      :options="editorOptions"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'Write something...'
  }
});

const emit = defineEmits(['update:modelValue']);

const content = ref(props.modelValue);

const editorOptions = {
    placeholder: props.placeholder,
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],
            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction
            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],
            ['clean']                                         // remove formatting button
        ]
    }
}

watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue;
  }
});

watch(content, (newValue) => {
  emit('update:modelValue', newValue);
});
</script>

<style>
/* Custom styling to match admin theme */
.rich-text-editor .ql-container {
  min-height: 150px;
  font-size: 1rem; /* match tailwind text-base or sm */
  font-family: inherit;
  border-bottom-left-radius: 0.5rem;
  border-bottom-right-radius: 0.5rem;
}
.rich-text-editor .ql-toolbar {
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
  background-color: #f9fafb; /* bg-gray-50 */
}
</style>
