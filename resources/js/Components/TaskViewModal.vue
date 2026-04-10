<script setup>
import { ref } from 'vue';
import BaseModal from './BaseModal.vue';
import BaseButton from './BaseButton.vue';
import TaskBadge from './TaskBadge.vue';

const props = defineProps({
    task: { type: Object, required: true },
    visible: { type: Boolean, default: false },
});

defineEmits(['close', 'edit', 'delete', 'markComplete']);

const copied = ref(false);
function copyLink() {
    const url = `${window.location.origin}/tasks/${props.task.id}`;
    navigator.clipboard.writeText(url).then(() => {
        copied.value = true;
        setTimeout(() => copied.value = false, 2000);
    });
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<template>
    <BaseModal :visible="visible" @close="$emit('close')">
        <template #header>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-[10px] font-bold font-mono text-surface-400 uppercase tracking-widest">{{
                        task.task_code }}</p>
                    <button @click="copyLink" title="Copy Link"
                        class="text-surface-300 hover:text-accent-500 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                    </button>
                    <span v-if="copied"
                        class="text-[9px] font-bold text-success-600 animate-fade-in uppercase tracking-tighter">Copied!</span>
                </div>
                <h2 class="text-lg font-bold text-surface-900 leading-tight">{{ task.title }}</h2>
            </div>
        </template>

        <div class="px-6 py-5 space-y-5">
            <div v-if="task.description" class="p-4 rounded-xl bg-surface-50 border border-surface-100 italic">
                <p class="text-sm text-surface-600 leading-relaxed">{{ task.description }}</p>
            </div>

            <div class="grid grid-cols-2 gap-x-8 gap-y-5">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider">Assigned To</span>
                    <p class="text-sm text-surface-800 font-semibold">{{ task.assigned_user?.name || '—' }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider">Severity</span>
                    <div>
                        <TaskBadge type="severity" :value="task.severity" />
                    </div>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider">Status</span>
                    <div>
                        <TaskBadge type="status" :value="task.status" />
                    </div>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider">Due Date</span>
                    <p class="text-sm text-surface-800 font-semibold">{{ formatDate(task.due_date) }}</p>
                </div>
            </div>

            <div
                class="pt-4 border-t border-surface-100 flex items-center justify-between text-[11px] text-surface-400 font-medium">
                <span>Created by {{ task.creator?.name }}</span>
                <span>Added on {{ formatDate(task.created_at) }}</span>
            </div>
        </div>

        <template #footer>
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <BaseButton v-if="task.status === 'pending'" variant="danger" size="sm"
                        @click="$emit('delete', task)">
                        Delete
                    </BaseButton>
                    <BaseButton v-if="task.status !== 'completed'" variant="success" size="sm"
                        @click="$emit('markComplete', task)">
                        Complete
                    </BaseButton>
                </div>
                <div class="flex items-center gap-2">
                    <BaseButton variant="secondary" size="sm" @click="$emit('close')">Close</BaseButton>
                    <BaseButton v-if="task.status !== 'completed'" variant="primary" size="sm"
                        @click="$emit('edit', task)">
                        Edit Task
                    </BaseButton>
                </div>
            </div>
        </template>
    </BaseModal>
</template>
