<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppHeader from '../Components/AppHeader.vue';
import TaskFilters from '../Components/TaskFilters.vue';
import SummaryBadges from '../Components/SummaryBadges.vue';
import TaskTable from '../Components/TaskTable.vue';
import Pagination from '../Components/Pagination.vue';
import BaseButton from '../Components/BaseButton.vue';
import TaskViewModal from '../Components/TaskViewModal.vue';
import TaskCreateModal from '../Components/TaskCreateModal.vue';
import ConfirmDialog from '../Components/ConfirmDialog.vue';
import ToastNotification from '../Components/ToastNotification.vue';

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);

const props = defineProps({
    tasks: { type: Object, default: () => ({ data: [], links: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 }) },
    total_tasks: { type: Number, default: 0 },
    status_summary: { type: Object, default: () => ({}) },
    severity_summary: { type: Object, default: () => ({}) },
    filters: { type: Object, default: () => ({}) },
    users: { type: Array, default: () => [] },
});


function toast(message, type = 'success') {
    window.dispatchEvent(new CustomEvent('app-toast', { detail: { message, type } }));
}

// ─── View Modal ───
const viewTask = ref(null);
const viewOpen = ref(false);
async function openView(task) {
    try {
        const res = await fetch(`/tasks/${task.id}`, { headers: { 'Accept': 'application/json' } });
        if (res.ok) {
            viewTask.value = await res.json();
            viewOpen.value = true;
        }
    } catch (e) {
        toast('Failed to load task details.', 'error');
    }
}
function closeView() { viewOpen.value = false; viewTask.value = null; }

// ─── Create/Edit Modal ───
const editTask = ref(null);
const createOpen = ref(false);
function openCreate() { editTask.value = null; createOpen.value = true; }

async function openEdit(task) {
    try {
        const res = await fetch(`/tasks/${task.id}/edit`, { headers: { 'Accept': 'application/json' } });
        if (res.ok) {
            editTask.value = await res.json();
            closeView();
            createOpen.value = true;
        }
    } catch (e) {
        toast('Failed to load task for editing.', 'error');
    }
}
function closeCreate() { createOpen.value = false; editTask.value = null; }
function onSaved() { closeCreate(); router.reload(); toast('Task saved successfully!'); }

// ─── Confirm Dialog ───
const confirmOpen = ref(false);
const confirmConfig = ref({ title: '', message: '', confirmText: '', variant: 'danger', action: null });

function showConfirm(config) {
    confirmConfig.value = config;
    confirmOpen.value = true;
}
function onConfirmed() {
    const action = confirmConfig.value.action;
    confirmOpen.value = false;
    if (action) action();
}
function onConfirmCancelled() { confirmOpen.value = false; }

// ─── Delete ───
function requestDelete(task) {
    closeView();
    showConfirm({
        title: `Delete "${task.title}"?`,
        message: 'This task will be permanently deleted and cannot be recovered.',
        confirmText: 'Delete Task',
        variant: 'danger',
        action: () => performDelete(task),
    });
}

function performDelete(task) {
    router.delete(`/tasks/${task.id}`, {
        preserveScroll: true,
        onSuccess: () => toast('Task deleted successfully!'),
        onError: (errors) => toast(errors.error || 'Failed to delete task.', 'error'),
    });
}

// ─── Mark Complete ───
function requestMarkComplete(task) {
    closeView();
    showConfirm({
        title: `Mark "${task.title}" as completed?`,
        message: 'This task will be marked as completed. You can\'t change the status later.',
        confirmText: 'Mark Complete',
        variant: 'info',
        action: () => performStatusChange(task, 'completed'),
    });
}

// ─── Change Status ───
function requestChangeStatus(task, newStatus) {
    showConfirm({
        title: `Change status to "${newStatus}"?`,
        message: `The task "${task.title}" status will be updated from "${task.status}" to "${newStatus}".`,
        confirmText: 'Update Status',
        variant: 'warning',
        action: () => performStatusChange(task, newStatus),
    });
}

function performStatusChange(task, newStatus) {
    router.put(`/tasks/${task.id}`, { status: newStatus }, {
        preserveScroll: true,
        onSuccess: () => toast(`Status updated to "${newStatus}"!`),
        onError: (errors) => toast(errors.error || 'Failed to update status.', 'error'),
    });
}

// ─── Change Severity ───
function requestChangeSeverity(task, newSeverity) {
    showConfirm({
        title: `Change severity to "${newSeverity}"?`,
        message: `The task "${task.title}" severity will be updated from "${task.severity}" to "${newSeverity}".`,
        confirmText: 'Update Severity',
        variant: 'warning',
        action: () => performSeverityChange(task, newSeverity),
    });
}

function performSeverityChange(task, newSeverity) {
    router.put(`/tasks/${task.id}`, { severity: newSeverity }, {
        preserveScroll: true,
        onSuccess: () => toast(`Severity updated to "${newSeverity}"!`),
        onError: (errors) => toast(errors.error || 'Failed to update severity.', 'error'),
    });
}

// ─── Filtering / Pagination ───
function onFilter(filters) {
    router.get('/tasks', filters, { preserveState: true, preserveScroll: true, replace: true });
}
function onPageNavigate(url) {
    if (url) router.visit(url, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <div class="min-h-screen bg-surface-50">
        <AppHeader title="Tasks">
            <template #actions>
                <BaseButton @click="openCreate" variant="primary" size="md">
                    <template #icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </template>
                    Create Task
                </BaseButton>
            </template>
        </AppHeader>

        <main class="max-w-7xl mx-auto px-6 py-6 space-y-5">
            <TaskFilters :filters="filters" :users="users" :statuses="statuses" :severities="severities"
                :errors="$page.props.errors" @filter="onFilter" />
            <SummaryBadges :statusSummary="status_summary" :severitySummary="severity_summary"
                :totalTasks="total_tasks" />
            <TaskTable :tasks="tasks.data" @view="openView" @edit="openEdit" @delete="requestDelete"
                @markComplete="requestMarkComplete" @changeStatus="requestChangeStatus"
                @changeSeverity="requestChangeSeverity" />
            <Pagination :links="tasks.links" :currentPage="tasks.current_page" :lastPage="tasks.last_page"
                :total="tasks.total" :from="tasks.from" :to="tasks.to" @navigate="onPageNavigate" />
        </main>

        <!-- Modals -->
        <TaskViewModal :visible="viewOpen" :task="viewTask || {}" @close="closeView" @edit="openEdit"
            @delete="requestDelete" @markComplete="requestMarkComplete" />
        <TaskCreateModal :visible="createOpen" :task="editTask" :users="users" :statuses="statuses"
            :severities="severities" :errors="$page.props.errors" @close="closeCreate" @save="onSaved" />
        <ConfirmDialog :visible="confirmOpen" v-bind="confirmConfig" @confirm="onConfirmed"
            @cancel="onConfirmCancelled" />
        <ToastNotification />
    </div>
</template>