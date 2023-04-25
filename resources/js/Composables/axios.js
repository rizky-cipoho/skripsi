import {ref} from 'vue'

export default function axios(route, params, routeParams){
	const data = ref([]);

	axios.post(route(route), {
		params:{
			params: params
		}
	})
	.then((output)=>{

	})
	.catch(()=>{

	});

	return { data }
}