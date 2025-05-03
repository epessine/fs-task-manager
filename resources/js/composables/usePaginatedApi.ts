import { useFiltersStore } from '@/stores/filters';
import axios from 'axios';
import { ref } from 'vue';

export function usePaginatedApi(url: string) {
    const filters = useFiltersStore();
    const data = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const total = ref(0);

    const fetchData = async () => {
        loading.value = true;
        error.value = null;

        try {
            const response = await axios.get(url, {
                params: {
                    page: currentPage.value,
                    status: filters.status,
                    category_id: filters.categoryId,
                    user_id: filters.userId,
                    sort_by: filters.sortBy,
                    sort_asc: filters.sortAsc,
                    per_page: 12,
                },
            });

            data.value = response.data.data;
            currentPage.value = response.data.meta.current_page;
            lastPage.value = response.data.meta.last_page;
            total.value = response.data.meta.total;
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message;
        } finally {
            loading.value = false;
        }
    };

    const changePage = (page: number) => {
        if (page >= 1 && page <= lastPage.value) {
            currentPage.value = page;
            fetchData();
        }
    };

    filters.$subscribe(() => {
        currentPage.value = 1;
        fetchData();
    });

    return {
        data,
        loading,
        error,
        currentPage,
        lastPage,
        total,
        fetchData,
        changePage,
    };
}
