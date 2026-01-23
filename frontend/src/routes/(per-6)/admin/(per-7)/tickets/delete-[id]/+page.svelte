<script lang="ts">
	import { goto } from '$app/navigation';
	import { apiSubmit } from '$lib/api/client/__index__';
	import type { ContactRecordResponse, ContactDeleteResponse } from '$lib/api/client/apiTypes';
	import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';

	export let data: { id: number; contactData: ContactRecordResponse };
    const ticket = data.contactData.record;

	let deleting = false;
	let error = '';

	async function doDelete() {
		deleting = true;
		error = '';

		try {
			const res: ContactDeleteResponse = await apiSubmit('contactDelete', fetch, { id: data.id });

			if (!res.ok) {
				throw new Error(res.error ?? 'Delete failed');
			}

			// go to your list page (change if your list route is different)
			await goto('/admin/tickets');
		} catch (e: any) {
			error = e?.message ?? 'Delete failed';
		} finally {
			deleting = false;
		}
	}
</script>

<Breadcrumbs
	names={['Admin Panel', 'tickets', `delete-${ticket?.id}`]}
	links={['/admin', '/admin/tickets', `/admin/tickets/delete-${ticket?.id}`]}
/>

{#if ticket}
	<h1>Delete: {ticket.title}</h1>
	<p><b>From:</b> {ticket.name} ({ticket.email})</p>
	<p><b>Reason:</b> {ticket.reason}</p>

	{#if error}
		<p style="color:red">{error}</p>
	{/if}

	<button onclick={doDelete} disabled={deleting}>
		{deleting ? 'Deleting…' : 'Confirm delete'}
	</button>

	<button onclick={() => goto(`/ticket-${data.id}`)} disabled={deleting}>
		Cancel
	</button>
{:else}
	<p>Not found</p>
{/if}
