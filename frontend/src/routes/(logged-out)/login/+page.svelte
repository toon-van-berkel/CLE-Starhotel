<script lang="ts">
  import { goto } from '$app/navigation';
  import { login } from '$lib/stores/session';

  let email = '';
  let password = '';
  let errorMessage = '';

  async function handleSubmit() {
    errorMessage = '';
    try {
      await login(fetch, { email, password });
      await goto('/rooms');
    } catch (error: any) {
      errorMessage = error?.message ?? 'Login failed';
    }
  }
</script>

<h1>Login</h1>

<form on:submit|preventDefault={handleSubmit}>
  <label>
    Email
    <input bind:value={email} type="email" />
  </label>

  <br />

  <label>
    Password
    <input bind:value={password} type="password" />
  </label>

  <br />

  <button type="submit">Login</button>
</form>

{#if errorMessage}
  <p>{errorMessage}</p>
{/if}
