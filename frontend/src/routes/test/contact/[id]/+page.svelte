<script lang="ts">
    import type { ContactRecordResponse } from '$lib/api/client/apiTypes';
    export let data: { contactData: ContactRecordResponse };
</script>

{#if data.contactData.record}
    <h1>{data.contactData.record.title}</h1>
    <p><b>From:</b> {data.contactData.record.name} ({data.contactData.record.email})</p>
    <p><b>Reason:</b> {data.contactData.record.reason}</p>
    <pre>{data.contactData.record.message}</pre>
{:else}
    <p>Not found</p>
{/if}
