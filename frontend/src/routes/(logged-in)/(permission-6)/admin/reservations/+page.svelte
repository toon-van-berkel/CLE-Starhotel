<script lang="ts">
  import { apiGet, apiPost, apiPut, apiDelete } from '$lib/api/api';
  import { endpoints } from '$lib/api/endpoints';

  let listResult = '';
  let errorMessage = '';

  let createJson = '{\n  "room_id": 1,\n  "check_in": "2026-01-27",\n  "check_out": "2026-01-28"\n}';

  let updateReservationId = '';
  let updateJson = '{\n  "check_out": "2026-01-29"\n}';

  let deleteReservationId = '';

  async function loadList() {
    errorMessage = '';
    try {
      const data = await apiGet<any>(fetch, endpoints.reservationsList);
      listResult = JSON.stringify(data, null, 2);
    } catch (error: any) {
      errorMessage = error?.message ?? 'Failed to load reservations';
    }
  }

  async function createReservation() {
    errorMessage = '';
    try {
      const data = JSON.parse(createJson);
      const result = await apiPost<any>(fetch, endpoints.reservationCreate, data);
      await loadList();
      alert('Created:\n' + JSON.stringify(result, null, 2));
    } catch (error: any) {
      errorMessage = error?.message ?? 'Create failed';
    }
  }

  async function updateReservation() {
    errorMessage = '';
    try {
      const reservationId = Number(updateReservationId);
      const data = JSON.parse(updateJson);
      const result = await apiPut<any>(fetch, endpoints.reservationUpdate(reservationId), data);
      await loadList();
      alert('Updated:\n' + JSON.stringify(result, null, 2));
    } catch (error: any) {
      errorMessage = error?.message ?? 'Update failed';
    }
  }

  async function deleteReservation() {
    errorMessage = '';
    try {
      const reservationId = Number(deleteReservationId);
      const result = await apiDelete<any>(fetch, endpoints.reservationDelete(reservationId));
      await loadList();
      alert('Deleted:\n' + JSON.stringify(result, null, 2));
    } catch (error: any) {
      errorMessage = error?.message ?? 'Delete failed';
    }
  }

  loadList();
</script>

<h1>Admin - Reservations</h1>

<button on:click={loadList}>Reload list</button>

{#if errorMessage}
  <p>{errorMessage}</p>
{/if}

<h2>List</h2>
<pre>{listResult}</pre>

<h2>Create</h2>
<textarea bind:value={createJson} rows="10" cols="60"></textarea>
<br />
<button on:click={createReservation}>Create</button>

<h2>Update</h2>
<label>
  Reservation id
  <input bind:value={updateReservationId} />
</label>
<br />
<textarea bind:value={updateJson} rows="10" cols="60"></textarea>
<br />
<button on:click={updateReservation}>Update</button>

<h2>Delete</h2>
<label>
  Reservation id
  <input bind:value={deleteReservationId} />
</label>
<br />
<button on:click={deleteReservation}>Delete</button>
