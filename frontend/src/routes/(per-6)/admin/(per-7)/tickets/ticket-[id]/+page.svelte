<script lang="ts">
    import type { ContactRecordResponse } from '$lib/api/client/apiTypes';
    import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';
    export let data: { contactData: ContactRecordResponse };

    const ticket = data.contactData.record;
</script>

<Breadcrumbs
	names={['Admin Panel', 'tickets', `ticket-${ticket?.id}`]}
	links={['/admin', '/admin/tickets', `/admin/tickets/ticket-${ticket?.id}`]}
/>

{#if ticket}
    <h1>Ticket {ticket.id}</h1>
    <p><b>ID: </b> {ticket.id}</p>
    <p>
        <b>Admin handled ID: </b>
        {#if ticket.admin_handled_id}
            {ticket.admin_handled_id}
        {:else}
            Not handled
        {/if}
    </p>
    <p>
        <b>Handled at: </b>
        {#if ticket.handled_at}
            {ticket.handled_at}
        {:else}
            Not handled
        {/if}
    </p>
    <p>
        <b>Status: </b>
        {#if ticket.status_id === 50}
            None
        {:else}
            Invalid status
        {/if}
    </p>
    <br>
    <p><b>Title: </b>{ticket.title}</p>
    <p><b>Reason: </b>{ticket.reason}</p>
    <p><b>Message: </b>{ticket.message}</p>
    <br>
    <p><b>Name: </b>{ticket.name}</p>
    <p><b>Email: </b>{ticket.email}</p>
    <p>
        <b>User: </b>
        {#if ticket.user_id}
            <a href="/">User {ticket.user_id}</a>
        {:else}
            anonymous
        {/if}
    </p>
{:else}
    <p>Not found</p>
{/if}
