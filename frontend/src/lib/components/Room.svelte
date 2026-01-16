<script lang="ts">
    import { onMount } from "svelte";
    import { records } from "$lib/api/room/records";
    import type { Room } from "$lib/api/types/room";  

    let rooms: Room[] = [];
    let error: string | null = null;

    onMount(async () => {
        const res = await records();
        rooms = res.records;
        error = res.error ?? null;
    });

  // export let room: Room;
</script>

{#each rooms as room}
<div>
    <!-- <p>id: {room.id}</p> -->
    <p>Max capacity: {room.max_capacity}</p>
    <p>Current capacity{room.current_capacity}</p>
    <p>Floot: {room.floor}</p>
    <p>Location: {room.location}</p>
    <p>Wing: {room.wing}</p>
    <p>Number: {room.number}</p>
</div>

{/each}
