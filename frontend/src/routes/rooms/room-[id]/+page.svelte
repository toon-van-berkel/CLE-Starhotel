<script lang="ts">
  import type { Room } from '$lib/api/types/room';

  let { data } = $props<{
    data: {
      room: Room | null;
      error: string | null;
    };
  }>();
</script>

{#if data.error}
  <p>{data.error}</p>
{:else if !data.room}
  <p>Room not found.</p>
{:else}
  <h1>Room {data.room.number}</h1>
  <ul>
    <li>Floor: {data.room.floor}</li>
    <li>Wing: {data.room.wing}</li>
    <li>Max: {data.room.max_capacity}</li>
    <li>Current: {data.room.current_capacity}</li>
    <li>Location: {data.room.location}</li>
  </ul>
{/if}
