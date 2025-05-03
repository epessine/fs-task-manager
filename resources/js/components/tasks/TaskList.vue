<script setup lang="ts">
import PlaceholderPattern from '../PlaceholderPattern.vue';
import { onMounted } from 'vue';
import TaskComponent from './Task.vue';
import { Button } from '@/components/ui/button';
import { usePaginatedApi } from '@/composables/usePaginatedApi';
import { Status, useFiltersStore } from '@/stores/filters';

const filters = useFiltersStore();
const {
    data,
    loading,
    error,
    currentPage,
    lastPage,
    total,
    changePage,
    fetchData
} = usePaginatedApi(route('api.v1.tasks.index'));

onMounted(fetchData);
filters.$subscribe(() => fetchData());
</script>


<template>
    <div class="flex items-center justify-between">
        <div class="inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800">
            <button
                v-for="value in [Status.All, Status.Completed, Status.Pending]"
                :key="value"
                @click="filters.setStatus(value)"
                :class="[
                    'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                    filters.status === value
                        ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                        : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
                ]"
            >
                <span class="ml-1.5 text-sm">{{ value }}</span>
            </button>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-muted-foreground">
                    Page {{ currentPage }} of {{ lastPage }} ({{ total }} tasks)
                </span>
                <Button
                    variant="secondary"
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage <= 1"
                >Previous</Button>
                <Button
                    variant="secondary"
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage >= lastPage"
                >Next</Button>
            </div>
        </div>
    </div>
    <div v-if="loading" class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <PlaceholderPattern />
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <PlaceholderPattern />
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <PlaceholderPattern />
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <PlaceholderPattern />
        </div>
    </div>
    <div v-else-if="error" class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        Error loading tasks: {{ error.message }}
    </div>
    <div v-if="data" class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <TaskComponent
            v-for="task in data"
            :key="task.id"
            :task="task"
        />
    </div>
</template>
