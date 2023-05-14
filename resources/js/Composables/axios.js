import { ref } from "vue";

export function axiosComposable(val) {
	const data = ref([]);
	axios
		.post(route(val.route, val.routeParams), {
			params: val.params,
		})
		.then((output) => {
			data.value.push(...output.data);
		});

	return { data };
}
