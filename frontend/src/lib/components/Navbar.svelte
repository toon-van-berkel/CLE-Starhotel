<!-- <script lang="ts">
    import { authStore } from '$lib/api/stores/auth';
    import { userIsLoggedIn } from '$lib/api/auth/guards';

    $: user = $authStore.user;
</script>

<nav>
    <h1>Test</h1>
    <ul>
        <li><a href="/">Home</a></li>

        {#if !userIsLoggedIn(user)}
            <li><a href="/login">Login</a></li>
        {:else}
            <li><a href="/profile">Profile</a></li>
        {/if}
    </ul>
</nav> -->
