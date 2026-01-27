<script lang="ts">
  import { apiPost } from '$lib/api/api';
  import { endpoints } from '$lib/api/endpoints';

  let subject = '';
  let message = '';
  let responseText = '';
  let errorMessage = '';

  async function handleSubmit() {
    responseText = '';
    errorMessage = '';

    try {
      const result = await apiPost<any>(fetch, endpoints.ticketCreate, {
        subject,
        message
      });
      responseText = JSON.stringify(result, null, 2);
      subject = '';
      message = '';
    } catch (error: any) {
      errorMessage = error?.message ?? 'Contact submit failed';
    }
  }
</script>

<h1>Contact</h1>

<form on:submit|preventDefault={handleSubmit}>
  <label>
    Subject
    <input bind:value={subject} />
  </label>

  <br />

  <label>
    Message
    <textarea bind:value={message} rows="6"></textarea>
  </label>

  <br />

  <button type="submit">Send</button>
</form>

{#if errorMessage}
  <p>{errorMessage}</p>
{/if}

{#if responseText}
  <h2>Response</h2>
  <pre>{responseText}</pre>
{/if}
