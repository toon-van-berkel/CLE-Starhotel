<script lang="ts">
  import type { RoomsListResponse } from "$lib/api/client/apiTypes";
  export let data: { roomsData: RoomsListResponse };
  const { roomsData } = data;
</script>

<h1>Test Page</h1>
<p>This is the base test page.</p>

{#each roomsData.records as room}
  <div>
    Room {room.number} <a href="/test/rooms/{room.id}">Room {room.id}</a>
  </div>
{/each}
