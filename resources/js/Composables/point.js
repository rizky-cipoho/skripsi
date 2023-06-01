import { ref } from "vue";

export function axiosComposable(val) {
	const data = ref(30);
	return { data };
}
