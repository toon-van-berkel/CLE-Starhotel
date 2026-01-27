<script lang="ts">
    import type { ContactListResponse } from '$lib/api/client/apiTypes';
    import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';
    export let data: { contactsData: ContactListResponse };
</script>

<Breadcrumbs
	names={['Admin Panel', 'tickets']}
	links={['/admin', '/admin/tickets']}
/>
<h1>Ticekts</h1>
{#if data.contactsData.error}
    <p>{data.contactsData.error}</p>
{:else}
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reason</th>
                <th>Title</th>
                <th>Message</th>
                <th>Email</th>
                <th>Name</th>
                <th>Created at</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {#each data.contactsData.records as c}
                <tr>
                    <td>{c.id}</td>
                    <td>{c.reason}</td>
                    <td>{c.title}</td>
                    <td>{c.message}</td>
                    <td>{c.email}</td>
                    <td>{c.name}</td>
                    <td>{c.created_at}</td>
                    <td>
                        <a href={`/admin/tickets/ticket-${c.id}`}>View</a>
                        <a href={`/admin/tickets/update-${c.id}`}>Edit</a>
                        <a href={`/admin/tickets/delete-${c.id}`}>Delete</a>
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>
{/if}

<a href="/admin/tickets/create">Create a ticket</a>