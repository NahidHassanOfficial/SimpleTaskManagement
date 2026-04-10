<script setup>
import { ref, watch, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import ConfirmDialog from './ConfirmDialog.vue';

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);

const props = defineProps({
    visible: { type: Boolean, default: false },
    task: { type: Object, default: null },
    users: { type: Array, default: () => [] },
    errors: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['close', 'save']);
const isEditing = ref(false);
const saving = ref(false);
const confirmShow = ref(false);
const form = ref({ title: '', description: '', severity: 'medium', status: 'pending', assigned_to: '', created_by: '', due_date: '' });

watch(() => props.visible, (val) => {
    if (val && props.task) {
        isEditing.value = true;
        form.value = {
            title: props.task.title || '',
            description: props.task.description || '',
            severity: props.task.severity || 'medium',
            status: props.task.status || 'pending',
            assigned_to: props.task.assigned_to || '',
            created_by: props.task.created_by || '',
            due_date: props.task.due_date ? props.task.due_date.split('T')[0] : ''
        };
    } else {
        isEditing.value = false;
        form.value = { title: '', description: '', severity: 'medium', status: 'pending', assigned_to: '', created_by: '', due_date: '' };
    }
});

function handleSubmit() {
    if (!form.value.title.trim()) return;

    if (isEditing.value) {
        confirmShow.value = true;
    } else {
        performSubmit();
    }
}

function performSubmit() {
    confirmShow.value = false;
    saving.value = true;
    const url = isEditing.value ? `/tasks/${props.task.id}` : '/tasks';
    const method = isEditing.value ? 'put' : 'post';
    router[method](url, form.value, {
        preserveScroll: true,
        onSuccess: () => { saving.value = false; emit('save'); },
        onError: () => { saving.value = false; },
    });
}

const inputCls = 'w-full h-10 rounded-lg border border-surface-300 bg-white px-3 text-sm text-surface-800 placeholder-surface-400 outline-none focus:border-accent-500 focus:ring-2 focus:ring-accent-100 transition-default';
const selectCls = inputCls + ' appearance-none';
const labelCls = 'text-xs font-medium text-surface-500 uppercase tracking-wider';
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="$emit('close')"></div>
                <div
                    class="relative w-full max-w-lg rounded-2xl border border-surface-200 bg-white shadow-xl animate-scale-in">
                    <div class="flex items-center justify-between border-b border-surface-100 px-6 py-5">
                        <h2 class="text-lg font-semibold text-surface-900">{{ isEditing ? 'Edit Task' : 'Create Task' }}
                        </h2>
                        <button @click="$emit('close')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-surface-400 hover:text-surface-700 hover:bg-surface-100 transition-default">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="handleSubmit" class="px-6 py-5 space-y-4">
                        <div v-if="errors.error"
                            class="p-3 rounded-lg bg-danger-50 border border-danger-100 text-sm text-danger-700 font-medium animate-shake">
                            {{ errors.error }}
                        </div>
                        <div class="space-y-1.5">
                            <label :class="labelCls">Task Title *</label>
                            <input v-model="form.title" type="text" required placeholder="Enter task title"
                                :class="inputCls" />
                            <p v-if="errors.title" class="text-xs text-danger-600">{{ errors.title }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <label :class="labelCls">Description</label>
                            <textarea v-model="form.description" rows="3" placeholder="Describe the task..."
                                class="w-full rounded-lg border border-surface-300 bg-white px-3 py-2 text-sm text-surface-800 placeholder-surface-400 outline-none resize-none focus:border-accent-500 focus:ring-2 focus:ring-accent-100 transition-default"></textarea>
                        </div>
                        <div class="grid gap-4" :class="isEditing ? 'grid-cols-2' : 'grid-cols-1'">
                            <div class="space-y-1.5">
                                <label :class="labelCls">Severity</label>
                                <select v-model="form.severity" :class="selectCls">
                                    <option v-for="severityOption in severities" :key="severityOption.value" :value="severityOption.value">{{ severityOption.label }}</option>
                                </select>
                                <p v-if="errors.severity" class="text-xs text-danger-600">{{ errors.severity }}</p>
                            </div>
                            <div v-if="isEditing" class="space-y-1.5">
                                <label :class="labelCls">Status</label>
                                <select v-model="form.status" :class="selectCls">
                                    <option v-for="statusOption in statuses" :key="statusOption.value" :value="statusOption.value">{{ statusOption.label }}</option>
                                </select>
                                <p v-if="errors.status" class="text-xs text-danger-600">{{ errors.status }}</p>
                            </div>
                        </div>
                        <div class="grid gap-4" :class="isEditing ? 'grid-cols-1' : 'grid-cols-2'">
                            <div class="space-y-1.5">
                                <label :class="labelCls">Assigned To *</label>
                                <select v-model="form.assigned_to" required :class="selectCls">
                                    <option value="">Select user...</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                </select>
                                <p v-if="errors.assigned_to" class="text-xs text-danger-600">{{ errors.assigned_to }}
                                </p>
                            </div>
                            <div v-if="!isEditing" class="space-y-1.5">
                                <label :class="labelCls">Created By *</label>
                                <select v-model="form.created_by" required :class="selectCls">
                                    <option value="">Select user...</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                </select>
                                <p v-if="errors.created_by" class="text-xs text-danger-600">{{ errors.created_by }}</p>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label :class="labelCls">Due Date</label>
                            <input v-model="form.due_date" type="date" :class="inputCls" />
                            <p v-if="errors.due_date" class="text-xs text-danger-600">{{ errors.due_date }}</p>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-surface-100">
                            <button type="button" @click="$emit('close')"
                                class="h-9 px-4 rounded-lg text-sm font-medium text-surface-600 bg-white hover:bg-surface-50 border border-surface-200 transition-default">Cancel</button>
                            <button type="submit" :disabled="!form.title.trim() || saving"
                                class="h-9 px-5 rounded-lg text-sm font-medium text-white bg-accent-600 hover:bg-accent-700 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-default flex items-center gap-2">
                                <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                {{ isEditing ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>

                    <ConfirmDialog :visible="confirmShow" title="Update Task?"
                        message="Are you sure you want to save these changes to the task?" confirmText="Update Now"
                        variant="warning" @confirm="performSubmit" @cancel="confirmShow = false" />

                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity .25s ease
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0
}
</style>
