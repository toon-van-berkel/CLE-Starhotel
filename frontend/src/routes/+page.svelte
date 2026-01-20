<!-- <script lang="ts">
  import { onMount } from 'svelte';
  // import { getRooms } from '$lib/api/rooms/records';
  import type { Room } from '$lib/api/types/__index__';

  let rooms: Room[] = [];
  let error: string | null = null;
  let loading = true;

  onMount(async () => {
    const res = await getRooms();
    rooms = res.records;
    error = res.error ?? null;
    loading = false;
  });
</script>

{#if loading}
  <p>Loading...</p>
{:else if error}
  <p class="error">{error}</p>
{:else}
  <ul>
    {#each rooms as room (room.id)}
      <li>{room.number} — {room.floor} {room.wing} — {room.current_capacity}/{room.max_capacity}</li>
    {/each}
  </ul>
{/if}

<style>
  .error { color: red; }
</style> -->

<a href="/test">test</a>