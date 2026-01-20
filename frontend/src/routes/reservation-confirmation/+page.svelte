

<script lang="ts">
    import type {reservationDetails} from '$lib/api/confirm/reservationdetails';

  let { data } = $props<{
    data: {
      reservation: reservationDetails | null;
      error: string | null;
    };
  }>();
</script>

{#if data.reservation.error}
  <p>{data.reservation.error}</p>
{:else if !data.reservation.room_id}
  <p>Room not found.</p>
{:else}
  <ul class="textcenter margin">
    <li class="listtext">{data.reservation.room_id}</li>
    <li class="listtext">{data.reservation.reservation_id}</li>
    <li class="listtext">van {data.reservation.booked_from} <br> tot {data.reservation.booked_till}</li>
  </ul>
{/if}