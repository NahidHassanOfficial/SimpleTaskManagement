<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppHeader from '../Components/AppHeader.vue';
import BaseButton from '../Components/BaseButton.vue';
import TaskBadge from '../Components/TaskBadge.vue';

const props = defineProps({
    task: Object
});

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<template>

    <Head :title="'Task: ' + task.title" />
    <div class="min-h-screen bg-surface-50">
        <AppHeader :title="task.task_code">
            <template #actions>
                <BaseButton variant="secondary" size="md" @click="router.visit('/tasks')">
                    Back to Task List
                </BaseButton>
            </template>
        </AppHeader>

        <main class="max-w-3xl mx-auto px-6 py-12">
            <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden animate-scale-in">
                <div class="px-8 py-8 border-b border-surface-100">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <TaskBadge type="status" :value="task.status" />
                            <TaskBadge type="severity" :value="task.severity" />
                        </div>
                        <BaseButton v-if="task.status !== 'completed'" variant="primary" size="sm"
                            @click="router.visit(`/tasks/${task.id}/edit`)">
                            <template #icon>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </template>
                            Edit Task
                        </BaseButton>
                    </div>
                    <h1 class="text-3xl font-bold text-surface-900 tracking-tight mb-4">{{ task.title }}</h1>
                    <p class="text-lg text-surface-600 leading-relaxed">
                        {{ task.description || 'No description provided.' }}</p>
                </div>

                <div class="px-8 py-6 bg-surface-50/50 grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-surface-400 mb-1">Assigned To</p>
                        <p class="text-surface-900 font-medium">{{ task.assigned_user?.name || 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-surface-400 mb-1">Due Date</p>
                        <p class="text-surface-900 font-medium">{{ formatDate(task.due_date) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-surface-400 mb-1">Created By</p>
                        <p class="text-surface-900 font-medium">{{ task.creator?.name || 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-surface-400 mb-1">Created At</p>
                        <p class="text-surface-900 font-medium">{{ formatDate(task.created_at) }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
