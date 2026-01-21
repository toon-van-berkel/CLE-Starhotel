<script lang="ts">
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import { goto } from '$app/navigation';
	import { invalidateAll } from '$app/navigation';

	let email = '';
	let password = '';
	let errorMsg = '';

	async function submit(e: SubmitEvent) {
		e.preventDefault();
		errorMsg = '';

		try {
			const res = await apiSubmit('login', fetch, { email, password });
			// if your backend returns { ok: true, user: ... } this line runs
			await invalidateAll();
			await goto('/profile');
		} catch (err) {
			errorMsg = err instanceof Error ? err.message : 'Login failed';
		}
	}
</script>

<form onsubmit={submit}>
	<input placeholder="Email" bind:value={email} />
	<input type="password" placeholder="Password" bind:value={password} />
	<button type="submit">Login</button>
</form>

{#if errorMsg}
	<p>{errorMsg}</p>
{/if}
