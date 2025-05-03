<script setup lang="ts">
import type { SharedData, Task } from '@/types';
import Card from '../ui/card/Card.vue';
import CardHeader from '../ui/card/CardHeader.vue';
import CardTitle from '../ui/card/CardTitle.vue';
import CardDescription from '../ui/card/CardDescription.vue';
import CardContent from '../ui/card/CardContent.vue';
import { Button } from '@/components/ui/button';
import Icon from '../Icon.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
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
            <div class="justify-between flex gap-2">
                <div class="rounded-lg border flex items-center justify-center px-2" :class="props.task.completed_at ? 'border-green-100 bg-green-50 dark:border-green-200/10 dark:bg-green-700/10' : 'border-yellow-100 bg-yellow-50 dark:border-yellow-200/10 dark:bg-yellow-700/10'">
                    <div class="relative space-y-0.5" :class="props.task.completed_at ? 'text-green-600 dark:text-green-100' : 'text-yellow-600 dark:text-yellow-100'">
                        <p class="font-medium text-sm">{{ props.task.completed_at ? 'Completed' : 'Pending' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button :disabled="page.props.auth.user.id !== task.user.id" variant="secondary">
                        <Icon name="edit" />
                    </Button>
                    <Button :disabled="page.props.auth.user.id !== task.user.id" variant="destructive">
                        <Icon name="trash" />
                    </Button>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
