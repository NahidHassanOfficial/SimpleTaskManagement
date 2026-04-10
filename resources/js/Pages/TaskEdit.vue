<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppHeader from '../Components/AppHeader.vue';

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);

const props = defineProps({
    task: { type: Object, required: true },
    users: { type: Array, required: true },
});

const form = useForm({
    title: props.task.title,
    description: props.task.description,
    status: props.task.status,
    severity: props.task.severity,
    assigned_to: props.task.assigned_to,
    due_date: props.task.due_date ? props.task.due_date.split('T')[0] : ''
});

function submit() {
    form.put(`/tasks/${props.task.id}`);
}

const inputCls = 'w-full h-11 rounded-xl border border-surface-200 bg-white px-4 text-surface-800 placeholder-surface-400 outline-none focus:border-accent-500 focus:ring-4 focus:ring-accent-50 transition-all';
const selectCls = inputCls + ' appearance-none';
const labelCls = 'text-sm font-semibold text-surface-700 ml-1';
</script>

<template>

    <Head title="Edit Task" />
    <div class="min-h-screen bg-surface-50">
        <AppHeader title="Edit Task" />

        <main class="max-w-2xl mx-auto px-6 py-12">
            <Link href="/tasks"
                class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-accent-600 mb-8 transition-default">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Task List
            </Link>

            <form @submit.prevent="submit"
                class="bg-white rounded-2xl border border-surface-200 shadow-sm p-8 space-y-6">
                <div v-if="form.errors.error"
                    class="p-4 rounded-xl bg-danger-50 border border-danger-100 text-sm text-danger-700 font-medium">
                    {{ form.errors.error }}
                </div>
                <div class="space-y-2">
                    <label :class="labelCls">Task Title</label>
                    <input v-model="form.title" type="text" :class="inputCls" placeholder="e.g. Implement search" />
                    <p v-if="form.errors.title" class="text-xs text-danger-600 font-medium">{{ form.errors.title }}</p>
                </div>

                <div class="space-y-2">
                    <label :class="labelCls">Description</label>
                    <textarea v-model="form.description" rows="4" :class="inputCls + ' h-auto py-3 resize-none'"
                        placeholder="What needs to be done?"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label :class="labelCls">Status</label>
                        <select v-model="form.status" :class="selectCls">
                            <option v-for="statusOption in statuses" :key="statusOption.value"
                                :value="statusOption.value">
                                {{ statusOption.label }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label :class="labelCls">Severity</label>
                        <select v-model="form.severity" :class="selectCls">
                            <option v-for="severityOption in severities" :key="severityOption.value"
                                :value="severityOption.value">
                                {{ severityOption.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label :class="labelCls">Assigned To</label>
                        <select v-model="form.assigned_to" :class="selectCls">
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 border-t border-surface-100 flex items-center justify-end gap-4">
                    <Link href="/tasks"
                        class="h-11 px-6 rounded-xl text-sm font-semibold text-surface-600 bg-surface-50 hover:bg-surface-100 transition-default">
                        Cancel</Link>
                    <button type="submit" :disabled="form.processing"
                        class="h-11 px-8 rounded-xl text-sm font-semibold text-white bg-accent-600 hover:bg-accent-700 shadow-md transition-default flex items-center gap-2 disabled:opacity-50">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </main>
    </div>
</template>
