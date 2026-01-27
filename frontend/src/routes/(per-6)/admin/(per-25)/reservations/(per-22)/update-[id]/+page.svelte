<script lang="ts">
	import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';
	import { goto } from '$app/navigation';

	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import type { ApiError } from '$lib/api/client/apiBase';

	export let data: {
		id: number;
		reservationData: any;
		roomsData: any;
	};

	const reservation = data.reservationData?.record ?? null;
	const rooms = data.roomsData?.records ?? [];

	// form fields (keep room as string because <select> gives strings)
	let room_id = reservation ? String(reservation.room_id) : '';
	let booked_from = reservation?.booked_from ?? '';
	let booked_till = reservation?.booked_till ?? '';
	let payment_method = reservation?.payment_method ?? '';

	let submitting = false;
	let formError = '';
	let fieldErrors: Record<string, string> = {};

	function resetErrors() {
		formError = '';
		fieldErrors = {};
	}

	function clientValidate() {
		const fe: Record<string, string> = {};

		if (!room_id) fe.room_id = 'Choose a room';
		if (!booked_from) fe.booked_from = 'Choose a start date';
		if (!booked_till) fe.booked_till = 'Choose an end date';
		if (booked_from && booked_till && booked_till < booked_from) {
			fe.booked_till = 'End date must be after start date';
		}
		if (!payment_method.trim()) fe.payment_method = 'Payment method is required';

		fieldErrors = fe;
		return Object.keys(fe).length === 0;
	}

	async function submitUpdate(e: SubmitEvent) {
		e.preventDefault();
		resetErrors();
		if (!clientValidate()) return;

		submitting = true;
		try {
			const res = await apiSubmit('reservationUpdate', fetch, {
				id: data.id,
				room_id: Number(room_id),
				booked_from,
				booked_till,
				payment_method
			});

			if (res?.ok === true) {
				await goto('/admin/reservations');
				return;
			}

			formError = res?.error ?? 'Update failed';
			fieldErrors = res?.fields ?? {};
		} catch (err) {
			const e = err as ApiError;
			const d = (e.data ?? {}) as any;

			formError = d.error ?? e.message ?? 'Update failed';
			fieldErrors = d.fields ?? {};
		} finally {
			submitting = false;
		}
	}
</script>

<Breadcrumbs
	names={['Admin Panel', 'Reservations', `Update #${data.id}`]}
	links={['/admin', '/admin/reservations', `/admin/reservations/update-${data.id}`]}
/>

<div class="head">
	<div>
		<h1>Update reservation</h1>
		<p class="muted">Edit reservation #{data.id}</p>
	</div>

	<div class="headActions">
		<a class="btn" href="/admin/reservations">Back</a>
	</div>
</div>

{#if data.reservationData?.error}
	<div class="alert">{data.reservationData.error}</div>
{:else if !reservation}
	<div class="alert">Reservation not found.</div>
{:else}
	{#if formError}
		<div class="alert">{formError}</div>
	{/if}

	<form class="card" onsubmit={submitUpdate}>
		<label>
			<span>Room</span>
			<select bind:value={room_id} disabled={submitting}>
				<option value="" disabled selected>Select a room</option>
				{#each rooms as r}
					<option value={String(r.id)}>
						Room {r.number} — {r.location} — {r.wing} — floor {r.floor} — max {r.max_capacity}
					</option>
				{/each}
			</select>
			{#if fieldErrors.room_id}<small class="err">{fieldErrors.room_id}</small>{/if}
		</label>

		<div class="grid">
			<label>
				<span>Booked from</span>
				<input type="date" bind:value={booked_from} disabled={submitting} />
				{#if fieldErrors.booked_from}<small class="err">{fieldErrors.booked_from}</small>{/if}
			</label>

			<label>
				<span>Booked till</span>
				<input type="date" bind:value={booked_till} min={booked_from || undefined} disabled={submitting} />
				{#if fieldErrors.booked_till}<small class="err">{fieldErrors.booked_till}</small>{/if}
			</label>
		</div>

		<label>
			<span>Payment method</span>
			<input type="text" bind:value={payment_method} disabled={submitting} />
			{#if fieldErrors.payment_method}<small class="err">{fieldErrors.payment_method}</small>{/if}
		</label>

		<div class="actions">
			<button type="button" class="btn" onclick={() => goto('/admin/reservations')} disabled={submitting}>
				Cancel
			</button>
			<button type="submit" class="btn primary" disabled={submitting}>
				{submitting ? 'Saving…' : 'Save changes'}
			</button>
		</div>
	</form>
{/if}

<style>
	.head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin:12px 0 16px}
	h1{margin:0;font-size:28px}
	.muted{opacity:.7;margin:6px 0 0}
	.headActions{display:flex;gap:10px}

	.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:16px;backdrop-filter:blur(10px);max-width:720px}
	label{display:block;margin-bottom:14px}
	label>span{display:block;font-size:14px;opacity:.9;margin-bottom:6px}
	input,select{width:100%;padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:rgba(0,0,0,.25);color:inherit;outline:none}
	.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
	.actions{display:flex;gap:10px;justify-content:flex-end;margin-top:10px}

	.btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.08);color:inherit;text-decoration:none;cursor:pointer}
	.btn:hover{background:rgba(255,255,255,.12)}
	.btn.primary{border-color:rgba(120,170,255,.35);background:rgba(120,170,255,.16)}
	.btn:disabled{opacity:.6;cursor:not-allowed}

	.alert{margin-top:14px;padding:12px 14px;border-radius:12px;border:1px solid rgba(255,120,120,.35);background:rgba(255,120,120,.12)}
	.err{display:block;margin-top:6px;color:rgba(255,120,120,.95);font-size:12px}

	@media (max-width:560px){.grid{grid-template-columns:1fr}}
</style>
