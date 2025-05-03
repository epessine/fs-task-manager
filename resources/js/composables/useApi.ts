import { ref } from 'vue';

export function useApi(url: string) {
    const response = ref<any>();
    const loading = ref(false);
    const error = ref<Error>();

    const fetchData = async () => {
        loading.value = true;
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Network response was not ok');
            response.value = await res.json();
        } catch (err: any) {
            error.value = err.message || 'Failed to fetch data';
        } finally {
            loading.value = false;
        }
    };

    return { response, loading, error, fetchData };
}
