import axios from 'axios';
import { ref } from 'vue';

export enum Filter {
    All = 'All',
    Completed = 'Completed',
    Pending = 'Pending',
}

export function usePaginatedApi(url: string) {
    const data = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const total = ref(0);
    const filter = ref(Filter.All);
    const perPage = ref(12);

    const fetchData = async () => {
        loading.value = true;
        error.value = null;

        try {
            const response = await axios.get(url, {
                params: {
                    page: currentPage.value,
                    filter: filter.value,
                    per_page: perPage.value,
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

    const changePage = (page : number) => {
        if (page >= 1 && page <= lastPage.value) {
            currentPage.value = page;
            fetchData();
        }
    };

    const changeFilter = (newFilter: Filter) => {
        filter.value = newFilter;
        currentPage.value = 1;
        fetchData();
    };

    return {
        data,
        loading,
        error,
        currentPage,
        lastPage,
        total,
        filter,
        perPage,
        fetchData,
        changePage,
        changeFilter,
    };
}
