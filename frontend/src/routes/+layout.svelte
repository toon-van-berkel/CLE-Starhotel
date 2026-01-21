<!-- <script lang="ts">
  import favicon from "$lib/assets/favicon.svg";
  import Navbar from "$lib/components/Navbar.svelte";
  // import { onMount } from "svelte";
  // import { authStore } from "$lib/api/stores/auth";
  import "../scss/style.css";

  let { children } = $props();

  // onMount(() => {
  //   authStore.init();
  // });
</script>

<svelte:head>
  <link rel="icon" href={favicon} />
</svelte:head>

<Navbar></Navbar>
{@render children()} -->

<script lang="ts">
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import { invalidateAll, goto } from '$app/navigation';

	export let data: { user: any };

	let loggingOut = false;

	async function logout() {
		loggingOut = true;
		try {
			await apiSubmit('logout', fetch, {});
			await invalidateAll();
			await goto('/login');
		} finally {
			loggingOut = false;
		}
	}
</script>

<nav>
	<a href="/">Home</a>

	{#if data.user}
		<a href="/profile">Profile</a>
		<button onclick={logout} disabled={loggingOut}>{loggingOut ? '...' : 'Logout'}</button>
	{:else}
		<a href="/login">Login</a>
		<a href="/register">Register</a>
    <a href="/test/rooms">Rooms</a>
    <a href="/test/contact">Contact</a>
	{/if}
</nav>

<slot />
