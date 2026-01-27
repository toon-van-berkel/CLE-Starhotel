<script lang="ts">
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import { goto, invalidateAll } from '$app/navigation';
	import { refreshMe } from '$lib/api/auth/sessions';
	import type { AuthUser } from '$lib/api/types/user';

	export let data: { user: AuthUser };

	let errorMsg = '';
	let loading = false;

	async function logout() {
		errorMsg = '';
		loading = true;

		try {
			await apiSubmit('logout', fetch, {});
			await refreshMe(fetch, { force: true });

			await invalidateAll();

			await goto('/login', { replaceState: true, invalidateAll: true });
		} catch (err) {
			errorMsg = err instanceof Error ? err.message : 'Logout failed';
		} finally {
			loading = false;
		}
	}
</script>


<h1>Profile</h1>

<section style="display:flex; flex-direction:column; gap: .25rem;">
	<p><b>Name:</b> {data.user.first_name} {data.user.last_name}</p>
	<p><b>Email:</b> {data.user.email}</p>
	<p><b>Phone:</b> {data.user.phone}</p>
</section>

<button onclick={logout} disabled={loading}>
	{loading ? 'Logging out…' : 'Logout'}
</button>

{#if errorMsg}
	<p style="color:red;">{errorMsg}</p>
{/if}
