<script lang="ts">
    import type { RoomsListResponse } from '$lib/api/types/room';

    export let data: { roomsData: RoomsListResponse };
    const { roomsData } = data;
</script>

{#each roomsData.records as room}
    <div>Room {room.number} <a href="test/{room.id}">Room {room.id}</a> </div>
{/each}
 