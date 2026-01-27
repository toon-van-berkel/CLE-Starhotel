<script lang="ts">
  import { goto } from '$app/navigation';
  import { register } from '$lib/stores/session';

  let firstName = '';
  let lastName = '';
  let email = '';
  let password = '';
  let errorMessage = '';

  async function handleSubmit() {
    errorMessage = '';
    try {
      await register(fetch, { first_name: firstName, last_name: lastName, email, password });
      await goto('/rooms');
    } catch (error: any) {
      errorMessage = error?.message ?? 'Register failed';
    }
  }
</script>

<h1>Register</h1>

<form on:submit|preventDefault={handleSubmit}>
  <label>
    First name
    <input bind:value={firstName} />
  </label>

  <br />

  <label>
    Last name
    <input bind:value={lastName} />
  </label>

  <br />

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

  <button type="submit">Create account</button>
</form>

{#if errorMessage}
  <p>{errorMessage}</p>
{/if}
