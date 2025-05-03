<script setup lang="ts">
import type { Task } from '@/types';
import Card from '../ui/card/Card.vue';
import CardHeader from '../ui/card/CardHeader.vue';
import CardTitle from '../ui/card/CardTitle.vue';
import CardDescription from '../ui/card/CardDescription.vue';
import CardContent from '../ui/card/CardContent.vue';
import Icon from '../Icon.vue';

const props = defineProps<{ task: Task }>();
</script>

<template>
    <Card class="rounded-xl justify-between">
        <CardHeader>
            <CardTitle class="text-xl">{{ props.task.title }}</CardTitle>
            <CardDescription>
                {{ props.task.description }}
            </CardDescription>
        </CardHeader>
        <CardContent>
            <div class="justify-start flex gap-2">
                <div class="rounded-lg border flex items-center justify-center px-2" :class="props.task.completed_at ? 'border-green-100 bg-green-50 dark:border-green-200/10 dark:bg-green-700/10' : 'border-yellow-100 bg-yellow-50 dark:border-yellow-200/10 dark:bg-yellow-700/10'">
                    <div class="relative space-y-0.5" :class="props.task.completed_at ? 'text-green-600 dark:text-green-100' : 'text-yellow-600 dark:text-yellow-100'">
                        <p class="font-medium text-sm">{{ props.task.completed_at ? 'Completed' : 'Pending' }}</p>
                    </div>
                </div>
                <div class="rounded-lg border flex items-center border-blue-100 bg-blue-50 dark:border-blue-200/10 dark:bg-blue-700/10 justify-center px-2 py-1">
                    <div class="relative space-y-0.5 text-blue-600 dark:text-blue-100">
                        <Icon name="user" class="h-4 w-4 inline mr-0.5" />
                        <span class="font-medium text-sm">{{ props.task.user.name }}</span>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
