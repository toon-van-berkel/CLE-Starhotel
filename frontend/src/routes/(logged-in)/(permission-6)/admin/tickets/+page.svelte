<script lang="ts">
  import { apiGet, apiPost, apiPut, apiDelete } from '$lib/api/api';
  import { endpoints } from '$lib/api/endpoints';

  let listResult = '';
  let errorMessage = '';

  let createJson = '{\n  "subject": "Test",\n  "message": "Hello"\n}';

  let updateTicketId = '';
  let updateJson = '{\n  "subject": "Updated subject"\n}';

  let deleteTicketId = '';

  async function loadList() {
    errorMessage = '';
    try {
      const data = await apiGet<any>(fetch, endpoints.ticketsList);
      listResult = JSON.stringify(data, null, 2);
    } catch (error: any) {
      errorMessage = error?.message ?? 'Failed to load tickets';
    }
  }

  async function createTicket() {
    errorMessage = '';
    try {
      const data = JSON.parse(createJson);
      const result = await apiPost<any>(fetch, endpoints.ticketCreate, data);
      await loadList();
      alert('Created:\n' + JSON.stringify(result, null, 2));
    } catch (error: any) {
      errorMessage = error?.message ?? 'Create failed';
    }
  }

  async function updateTicket() {
    errorMessage = '';
    try {
      const ticketId = Number(updateTicketId);
      const data = JSON.parse(updateJson);
      const result = await apiPut<any>(fetch, endpoints.ticketUpdate(ticketId), data);
      await loadList();
      alert('Updated:\n' + JSON.stringify(result, null, 2));
    } catch (error: any) {
      errorMessage = error?.message ?? 'Update failed';
    }
  }

  async function deleteTicket() {
    errorMessage = '';
    try {
      const ticketId = Number(deleteTicketId);
      const result = await apiDelete<any>(fetch, endpoints.ticketDelete(ticketId));
      await loadList();
      alert('Deleted:\n' + JSON.stringify(result, null, 2));
    } catch (error: any) {
      errorMessage = error?.message ?? 'Delete failed';
    }
  }

  loadList();
</script>

<h1>Admin - Tickets</h1>

<button on:click={loadList}>Reload list</button>

{#if errorMessage}
  <p>{errorMessage}</p>
{/if}

<h2>List</h2>
<pre>{listResult}</pre>

<h2>Create</h2>
<textarea bind:value={createJson} rows="10" cols="60"></textarea>
<br />
<button on:click={createTicket}>Create</button>

<h2>Update</h2>
<label>
  Ticket id
  <input bind:value={updateTicketId} />
</label>
<br />
<textarea bind:value={updateJson} rows="10" cols="60"></textarea>
<br />
<button on:click={updateTicket}>Update</button>

<h2>Delete</h2>
<label>
  Ticket id
  <input bind:value={deleteTicketId} />
</label>
<br />
<button on:click={deleteTicket}>Delete</button>
