<script lang="ts">
  export let data: { roomId: number; room: any };
</script>

<h1>Room detail: {data.roomId}</h1>

<p>
  <a href={`/rooms/room-${data.roomId}/book-0`}>Make reservation for this room</a>
</p>

<pre>{JSON.stringify(data.room, null, 2)}</pre>
