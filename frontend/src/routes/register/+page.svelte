<script lang="ts">
	import { goto } from '$app/navigation';
	import { apiSubmit } from '$lib/api/client/apiSubmit';

	let first_name = '';
	let last_name = '';
	let email = '';
	let phone = '';
	let password = '';

	let errorMsg = '';
	let fieldErrors: Record<string, string> = {};

	async function submit(e: SubmitEvent) {
		e.preventDefault();
		errorMsg = '';
		fieldErrors = {};

		const res = await apiSubmit('register', fetch, { first_name, last_name, email, phone, password });

		if (!res.ok) {
		errorMsg = res.error || 'Register failed';
		fieldErrors = res.fields ?? {};
		return;
		}

		await goto('/login');
	}
</script>

<h1>Register</h1>

<form onsubmit={submit}>
	<input placeholder="First name" bind:value={first_name} />
	{#if fieldErrors.first_name}<small>{fieldErrors.first_name}</small>{/if}

	<input placeholder="Last name" bind:value={last_name} />
	{#if fieldErrors.last_name}<small>{fieldErrors.last_name}</small>{/if}

	<input placeholder="Email" bind:value={email} />
	{#if fieldErrors.email}<small>{fieldErrors.email}</small>{/if}

	<input placeholder="Phone" bind:value={phone} />
	{#if fieldErrors.phone}<small>{fieldErrors.phone}</small>{/if}

	<input type="password" placeholder="Password" bind:value={password} />
	{#if fieldErrors.password}<small>{fieldErrors.password}</small>{/if}

	<button type="submit">Create account</button>
</form>

{#if errorMsg}
	<p>{errorMsg}</p>
{/if}
