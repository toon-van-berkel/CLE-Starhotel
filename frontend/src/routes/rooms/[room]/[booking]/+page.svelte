<script lang="ts">
  import { apiPost, apiPut } from '$lib/api/api';
  import { endpoints } from '$lib/api/endpoints';

  export let data: { roomId: number; bookingId: number; room: any; reservation: any };

  let errorMessage = '';
  let responseText = '';

  let reservationJson = JSON.stringify(
    data.bookingId > 0
      ? (data.reservation ?? {})
      : { room_id: data.roomId, check_in: '2026-01-27', check_out: '2026-01-28' },
    null,
    2
  );

  async function saveReservation() {
    errorMessage = '';
    responseText = '';

    try {
      const payload = JSON.parse(reservationJson);

      let result: any;
      if (data.bookingId > 0) {
        result = await apiPut<any>(fetch, endpoints.reservationUpdate(data.bookingId), payload);
      } else {
        result = await apiPost<any>(fetch, endpoints.reservationCreate, payload);
      }

      responseText = JSON.stringify(result, null, 2);
    } catch (error: any) {
      errorMessage = error?.message ?? 'Save failed';
    }
  }
</script>

<h1>Reservation</h1>

<p>Room: {data.roomId}</p>
<p>Booking id: {data.bookingId}</p>

<h2>Room raw</h2>
<pre>{JSON.stringify(data.room, null, 2)}</pre>

<h2>Reservation JSON</h2>
<textarea bind:value={reservationJson} rows="14" cols="70"></textarea>
<br />
<button on:click={saveReservation}>Save</button>

{#if errorMessage}
  <p>{errorMessage}</p>
{/if}

{#if responseText}
  <h2>Response</h2>
  <pre>{responseText}</pre>
{/if}
