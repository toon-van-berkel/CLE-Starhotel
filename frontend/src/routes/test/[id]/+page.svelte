<script lang="ts">
    import type { RoomRecordResponse } from '$lib/api/types/room';
    export let data: { roomData: RoomRecordResponse };
</script>

{#if data.roomData.record}
    <div>Room {data.roomData.record.number}</div>
    <div>Testing: {data.roomData.record.location}</div>
{:else}
    <div>Room not found</div>
{/if}
