<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppHeader from '../Components/AppHeader.vue';
import StatCard from '../Components/StatCard.vue';
import BaseButton from '../Components/BaseButton.vue';

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);

const props = defineProps({
    totalTasks: { type: Number, default: 0 },
    statusSummary: { type: Object, default: () => ({}) },
    severitySummary: { type: Object, default: () => ({}) },
});
</script>

<template>
    <div class="min-h-screen bg-surface-50">
        <AppHeader title="Dashboard">
            <template #actions>
                <BaseButton @click="router.visit('/tasks')" variant="primary" size="md">
                    <template #icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9l2 2 4-4" />
                        </svg>
                    </template>
                    Task List
                </BaseButton>
            </template>
        </AppHeader>

        <main class="max-w-7xl mx-auto px-6 py-10">
            <header class="mb-12 animate-fade-in">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="space-y-2">
                        <h2 class="text-4xl font-black text-surface-900 tracking-tight">Task Summarry</h2>
                        <div class="flex flex-wrap items-center gap-3">
                            <div
                                class="px-3 py-1 bg-warning-50 border border-warning-100 rounded-full flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-warning-500 animate-pulse"></div>
                                <span class="text-[10px] font-bold text-warning-700 uppercase tracking-widest">{{
                                    severitySummary.high || 0 }} Critical</span>
                            </div>
                            <div
                                class="px-3 py-1 bg-accent-50 border border-accent-100 rounded-full flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-accent-500"></div>
                                <span class="text-[10px] font-bold text-accent-700 uppercase tracking-widest">{{
                                    totalTasks }}
                                    Projects</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <BaseButton @click="router.visit('/tasks')" variant="secondary" size="sm">
                            View All Tasks
                        </BaseButton>
                        <BaseButton @click="router.visit('/tasks?status=pending')" variant="primary" size="sm">
                            New Tasks
                        </BaseButton>
                    </div>
                </div>
            </header>

            <div class="space-y-16">
                <!-- Main Pipeline -->
                <section>
                    <div class="flex items-center gap-4 mb-6">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-surface-400">Workflow Velocity
                        </p>
                        <div class="h-px flex-1 bg-surface-100"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <StatCard label="Total Tasks" :value="totalTasks" color="accent" icon="clipboard" :delay="0"
                            href="/tasks" />
                        <StatCard v-for="(status, index) in statuses" :key="status.value" :label="status.label"
                            :value="statusSummary[status.value] || 0" :color="status.color" :icon="status.icon"
                            :delay="100 + (index * 75)" :href="`/tasks?status=${status.value}`" />
                    </div>
                </section>

                <!-- Severity Distribution Section -->
                <section class="animate-fade-in" style="animation-delay: 200ms">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-px flex-1 bg-surface-100"></div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-surface-400">Priority Landscape
                        </p>
                        <div class="h-px flex-1 bg-surface-100"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <StatCard v-for="(severity, index) in severities" :key="severity.value" :label="severity.label"
                            :value="severitySummary[severity.value] || 0" :color="severity.color" icon="clipboard"
                            :delay="350 + (index * 75)" :href="`/tasks?severity=${severity.value}`" />
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>