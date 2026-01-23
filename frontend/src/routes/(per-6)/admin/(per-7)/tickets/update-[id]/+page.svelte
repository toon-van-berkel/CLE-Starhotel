<script lang="ts">
	import { goto } from '$app/navigation';
	import { apiSubmit } from '$lib/api/client/__index__';
	import type { ContactRecordResponse, ContactUpdateResponse } from '$lib/api/client/apiTypes';
	import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';

	export let data: { id: number; contactData: ContactRecordResponse };
    const ticket = data.contactData.record;

	// Prefill
	let title = ticket?.title ?? '';
	let name = ticket?.name ?? '';
	let email = ticket?.email ?? '';
	let reason = ticket?.reason ?? '';
	let message = ticket?.message ?? '';
	let status_id = ticket?.status_id ?? 50 as number;

	let saving = false;

	// errors
	let error = '';
	let fieldErrors: Record<string, string> = {};

	async function save() {
		if (!ticket) return;

		saving = true;
		error = '';
		fieldErrors = {};

		try {
			const res: ContactUpdateResponse = await apiSubmit('contactUpdate', fetch, {
				id: data.id,
				title,
				name,
				email,
				reason,
				message,
				status_id
			});

			if (!res.ok) {
				// backend validation
				fieldErrors = res.fields ?? {};
				throw new Error(res.error ?? 'Update failed');
			}

			// go back to ticket page (your route style)
			await goto(`/admin/tickets/ticket-${data.id}`);
		} catch (e: any) {
			// only show generic error if not validation
			if (!Object.keys(fieldErrors).length) {
				error = e?.message ?? 'Update failed';
			}
		} finally {
			saving = false;
		}
	}
</script>

<Breadcrumbs
	names={['Admin Panel', 'tickets', `update-${ticket?.id}`]}
	links={['/admin', '/admin/tickets', `/admin/tickets/update-${ticket?.id}`]}
/>

{#if ticket}
	<h1>Update ticket: {ticket.title}</h1>

	{#if error}
		<p style="color:red">{error}</p>
	{/if}

	<label>Title</label>
	<input bind:value={title} />
	{#if fieldErrors.title}<p style="color:red">{fieldErrors.title}</p>{/if}

	<label>Name</label>
	<input bind:value={name} />
	{#if fieldErrors.name}<p style="color:red">{fieldErrors.name}</p>{/if}

	<label>Email</label>
	<input bind:value={email} />
	{#if fieldErrors.email}<p style="color:red">{fieldErrors.email}</p>{/if}

	<label>Reason</label>
	<input bind:value={reason} />
	{#if fieldErrors.reason}<p style="color:red">{fieldErrors.reason}</p>{/if}

	<label>Status</label>
	<select bind:value={status_id}>
		<option value={1}>New</option>
		<option value={2}>In progress</option>
		<option value={3}>Done</option>
	</select>

	<label>Message</label>
	<textarea rows="8" bind:value={message}></textarea>
	{#if fieldErrors.message}<p style="color:red">{fieldErrors.message}</p>{/if}

	<div style="margin-top: 12px;">
		<button onclick={save} disabled={saving}>
			{saving ? 'Saving…' : 'Save'}
		</button>

		<button onclick={() => goto(`/ticket-${data.id}`)} disabled={saving}>
			Cancel
		</button>
	</div>
{:else}
	<p>Not found</p>
{/if}
