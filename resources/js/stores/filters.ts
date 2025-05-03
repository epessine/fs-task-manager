import { defineStore } from 'pinia';
import { ref } from 'vue';

export enum Status {
    All = 'All',
    Completed = 'Completed',
    Pending = 'Pending',
}

export enum SortBy {
    CreatedAt = 'created_at',
    UpdatedAt = 'updated_at',
    CompletedAt = 'completed_at',
    Title = 'title',
}

export const useFiltersStore = defineStore('filters', () => {
    const status = ref<Status>(Status.All);
    const categoryId = ref<string | null>(null);
    const userId = ref<number | null>(null);
    const sortAsc = ref<boolean>(false);
    const sortBy = ref<SortBy>(SortBy.UpdatedAt);

    const resetFilters = () => {
        status.value = Status.All;
        categoryId.value = null;
        userId.value = null;
        sortAsc.value = false;
        sortBy.value = SortBy.UpdatedAt;
    };
    const setStatus = (newStatus: Status) => {
        status.value = newStatus;
    };
    const setCategoryId = (newCategoryId: string | null) => {
        categoryId.value = newCategoryId;
    };
    const setUserId = (newUserId: number | null) => {
        userId.value = newUserId;
    };
    const setSortAsc = (newSortAsc: boolean) => {
        sortAsc.value = newSortAsc;
    };
    const setSortBy = (newSortBy: SortBy) => {
        sortBy.value = newSortBy;
    };

    return {
        status,
        categoryId,
        userId,
        sortAsc,
        sortBy,
        resetFilters,
        setStatus,
        setCategoryId,
        setUserId,
        setSortAsc,
        setSortBy,
    };
});
