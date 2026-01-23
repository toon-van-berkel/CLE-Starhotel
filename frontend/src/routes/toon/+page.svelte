<script lang="ts">
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import { invalidateAll, goto } from '$app/navigation';
	import { clearMe } from '$lib/api/auth/sessions';

	export let data: { user: any };

	let loggingOut = false;

	async function logout() {
		loggingOut = true;
		try {
			await apiSubmit('logout', fetch, {});
			clearMe();
			await invalidateAll();
			await goto('/login');
		} finally {
			loggingOut = false;
		}
	}
</script>

<nav>
	<a href="/">Home</a>
	<a href="/test/rooms">Rooms</a>
	<a href="/test/contact">Contact</a>

	{#if data.user}
		<a href="/profile">Profile</a>
		<button onclick={logout} disabled={loggingOut}>{loggingOut ? '...' : 'Logout'}</button>
	{:else}
		<a href="/login">Login</a>
		<a href="/register">Register</a>
	{/if}
</nav>