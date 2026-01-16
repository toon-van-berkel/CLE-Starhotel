<script lang="ts">
  import { goto } from '$app/navigation';

  // new split api imports
  import { register } from '$lib/api/user/register';
  import { login } from '$lib/api/user/login';

  let first_name = '';
  let last_name = '';
  let email = '';
  let phone = '';
  let password = '';
  let password2 = '';

  let loading = false;
  let error = '';
  let success = '';

  function validate() {
    if (first_name.trim().length < 2) return 'First name must be at least 2 characters';
    if (last_name.trim().length < 2) return 'Last name must be at least 2 characters';
    if (!email.includes('@')) return 'Enter a valid email address';
    if (password.length < 8) return 'Password must be at least 8 characters';
    if (password !== password2) return 'Passwords do not match';
    return '';
  }

  async function onSubmit() {
    error = '';
    success = '';

    const v = validate();
    if (v) {
      error = v;
      return;
    }

    loading = true;

    const normalizedEmail = email.trim().toLowerCase();
    const normalizedPhone = phone.trim();

    try {
      await register({
        first_name: first_name.trim(),
        last_name: last_name.trim(),
        email: normalizedEmail,
        phone: normalizedPhone || undefined,
        password
      });

      // Optional: auto-login right after registration
      await login(normalizedEmail, password);

      success = 'Account created! Redirecting...';
      await goto('/dashboard');
    } catch (e) {
      error = e instanceof Error ? e.message : String(e);
    } finally {
      loading = false;
    }
  }
</script>

<svelte:head>
  <title>Register</title>
</svelte:head>

<div class="wrap">
  <h1>Create account</h1>
  <p class="sub">Register to access the platform.</p>

  <form on:submit|preventDefault={onSubmit} class="card">
    <div class="row">
      <div class="field">
        <label for="first">First name</label>
        <input id="first" bind:value={first_name} autocomplete="given-name" required />
      </div>

      <div class="field">
        <label for="last">Last name</label>
        <input id="last" bind:value={last_name} autocomplete="family-name" required />
      </div>
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input id="email" type="email" bind:value={email} autocomplete="email" required />
    </div>

    <div class="field">
      <label for="phone">Phone (optional)</label>
      <input id="phone" bind:value={phone} autocomplete="tel" />
    </div>

    <div class="row">
      <div class="field">
        <label for="pw">Password</label>
        <input id="pw" type="password" bind:value={password} autocomplete="new-password" required />
        <small>At least 8 characters.</small>
      </div>

      <div class="field">
        <label for="pw2">Repeat password</label>
        <input id="pw2" type="password" bind:value={password2} autocomplete="new-password" required />
      </div>
    </div>

    {#if error}
      <div class="msg error">{error}</div>
    {/if}
    {#if success}
      <div class="msg ok">{success}</div>
    {/if}

    <button disabled={loading}>
      {loading ? 'Creating...' : 'Create account'}
    </button>

    <p class="foot">
      Already have an account?
      <a href="/login">Login</a>
    </p>
  </form>
</div>

<style>
  .wrap { max-width: 520px; margin: 48px auto; padding: 0 16px; }
  h1 { margin: 0 0 6px; font-size: 28px; }
  .sub { margin: 0 0 18px; opacity: .8; }

  .card { border: 1px solid rgba(255,255,255,.12); padding: 18px; border-radius: 14px; }
  .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .field { display: grid; gap: 6px; margin-bottom: 12px; }
  label { font-size: 13px; opacity: .9; }
  input {
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(0,0,0,.2);
    color: inherit;
    outline: none;
  }
  input:focus { border-color: rgba(255,255,255,.35); }
  small { font-size: 12px; opacity: .7; }

  .msg { padding: 10px 12px; border-radius: 10px; margin: 10px 0; font-size: 14px; }
  .error { background: rgba(255, 70, 70, .16); border: 1px solid rgba(255, 70, 70, .35); }
  .ok { background: rgba(70, 255, 140, .12); border: 1px solid rgba(70, 255, 140, .35); }

  button {
    width: 100%;
    padding: 11px 12px;
    border: 0;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 6px;
  }
  button:disabled { opacity: .6; cursor: not-allowed; }

  .foot { margin: 12px 0 0; font-size: 14px; opacity: .85; text-align: center; }
  a { text-decoration: underline; }
  @media (max-width: 520px) { .row { grid-template-columns: 1fr; } }
</style>
