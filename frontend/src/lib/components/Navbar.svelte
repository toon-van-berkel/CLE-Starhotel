<script lang="ts">
	import type { AuthUser } from '$lib/api/types/user';
	export let user: AuthUser | null = null;
</script>

<nav>
	<ul>
		<div class="flex">
			<li><a class="bg-primary" href="/">SH</a></li>
			<li><a class="navinfo" href="/">Starhotel</a></li>
		</div>

		<div class="flex">
			<li><a class="navinfo" href="/about">About</a></li>
			<li><a class="navinfo" href="/rooms">Rooms</a></li>

			{#if user}
				<li><a class="navinfo" href="/profile">Profile</a></li>
			{:else}
				<li><a class="navinfo" href="/login">Login</a></li>
			{/if}
		</div>
	</ul>
</nav>

<hr />
