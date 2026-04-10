<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    title: { type: String, default: 'Task Manager' },
});

const page = usePage();
const currentUrl = computed(() => page.url);

const navItems = [
    { label: 'Dashboard', url: '/dashboard', active: currentUrl.value.startsWith('/dashboard') },
    { label: 'Tasks', url: '/tasks', active: currentUrl.value.startsWith('/tasks') },
];

const navCls = (active) => `h-9 px-4 rounded-lg text-sm font-medium transition-default flex items-center gap-2 ${active ? 'bg-accent-50 text-accent-700 shadow-sm' : 'text-surface-500 hover:bg-surface-50 hover:text-surface-800'
    }`;
</script>

<template>
    <header
        class="glass sticky top-0 z-40 px-6 py-3.5 flex items-center justify-between border-b border-surface-200/50 animate-fade-in">


        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-accent-600 flex items-center justify-center text-white shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </div>
            <h1 class="text-lg font-bold text-surface-900 tracking-tight hidden sm:block">{{ title }}</h1>
        </div>

        <div class="flex items-center gap-6">
            <nav class="flex items-center gap-1.5 border-r border-surface-200 pr-6 mr-1">
                <Link v-for="item in navItems" :key="item.url" :href="item.url" :class="navCls(item.active)">
                    {{ item.label }}
                </Link>
            </nav>
            <div class="flex items-center gap-3">
                <slot name="actions" />
            </div>
        </div>
    </header>
</template>
