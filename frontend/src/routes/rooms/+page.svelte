<script lang="ts">
  export let data: { rooms: any };
  function getRoomArray(rooms: any) {
    if (Array.isArray(rooms)) return rooms;
    if (rooms?.rooms && Array.isArray(rooms.rooms)) return rooms.rooms;
    return [];
  }
</script>

<h1>Rooms</h1>

<ul>
  {#each getRoomArray(data.rooms) as room}
    <li>
      <a href={`/rooms/room-${room.id}`}>Room {room.id}</a>
    </li>
  {/each}
</ul>

<h2>Raw</h2>
<pre>{JSON.stringify(data.rooms, null, 2)}</pre>
