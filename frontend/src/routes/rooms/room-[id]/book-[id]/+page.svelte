<script lang="ts">
	import { goto } from '$app/navigation';
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import type { ApiError } from '$lib/api/client/apiBase';
	import type { ReservationApiRecord } from '$lib/api/types/reservation';

	// form fields
	let room_id = $props();
	let booked_from = $state('');
	let booked_till = $state('');
	let payment_method = $state('');

	// ui state
	let submitting = $state(false);
	let formError = $state('');
	let fieldErrors = $state<Record<string, string>>({});
	let created = $state<ReservationApiRecord | null>(null);

	function resetErrors() {
		formError = '';
		fieldErrors = {};
	}

	function clientValidate(): boolean {
		const fe: Record<string, string> = {};

		if (!booked_from) fe.booked_from = 'Choose a start date';
		if (!booked_till) fe.booked_till = 'Choose an end date';
		if (booked_from && booked_till && booked_till < booked_from) {
			fe.booked_till = 'End date must be after start date';
		}
		if (!payment_method.trim()) fe.payment_method = 'Payment method is required';

		fieldErrors = fe;
		return Object.keys(fe).length === 0;
	}

	async function submitReservation(e?: SubmitEvent) {
		e?.preventDefault();

		resetErrors();
		created = null;

		if (!clientValidate()) return;

		submitting = true;
		try {
			const res = await apiSubmit('reservation', fetch, {
				room_id,
				booked_from,
				booked_till,
				payment_method
			});

			// Expect: { ok:true, id, record }
			if ('ok' in res && res.ok === true) {
				created = (res as any).record ?? null;
				await goto('/rooms');
				return;
			}

			// if backend returns ok:false with 200
			formError = (res as any)?.error ?? 'Reservation failed';
			fieldErrors = (res as any)?.fields ?? {};
		} catch (err) {
			const e = err as ApiError;
			const data = (e.data ?? {}) as any;

			formError = data.error ?? e.message ?? 'Reservation failed';
			fieldErrors = data.fields ?? {};
		} finally {
			submitting = false;
		}
	}
</script>

<section class="page">
	<h1>Create reservation</h1>

	{#if formError}
		<div class="alert">{formError}</div>
	{/if}

	<form onsubmit={submitReservation} class="card">
		<div class="grid">
			<label>
				<span>From</span>
				<input type="date" bind:value={booked_from} disabled={submitting} />
				{#if fieldErrors.booked_from}<small class="err">{fieldErrors.booked_from}</small>{/if}
			</label>

			<label>
				<span>Till</span>
				<input
					type="date"
					bind:value={booked_till}
					min={booked_from || undefined}
					disabled={submitting}
				/>
				{#if fieldErrors.booked_till}<small class="err">{fieldErrors.booked_till}</small>{/if}
			</label>
		</div>

		<label>
			<span>Payment method</span>
			<input
				type="text"
				placeholder="card / cash / iDEAL / bank transfer…"
				bind:value={payment_method}
				disabled={submitting}
			/>
			{#if fieldErrors.payment_method}<small class="err">{fieldErrors.payment_method}</small>{/if}
		</label>

		<div class="actions">
			<button type="button" class="btn secondary" onclick={() => goto('/rooms')} disabled={submitting}>
				Cancel
			</button>
			<button type="submit" class="btn" disabled={submitting}>
				{submitting ? 'Creating…' : 'Create reservation'}
			</button>
		</div>
	</form>

	{#if created}
		<div class="card">
			<h2>Created</h2>
			<p>Reservation #{created.id} for room #{created.room_id}</p>
		</div>
	{/if}
</section>

<style>
	.page {
		max-width: 720px;
		margin: 24px auto;
		padding: 0 16px;
	}
	h1 {
		margin: 0 0 16px 0;
	}
	.card {
		background: rgba(255, 255, 255, 0.04);
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: 14px;
		padding: 16px;
		backdrop-filter: blur(10px);
	}
	label {
		display: block;
		margin-bottom: 14px;
	}
	label > span {
		display: block;
		font-size: 14px;
		opacity: 0.9;
		margin-bottom: 6px;
	}
	input {
		width: 100%;
		padding: 10px 12px;
		border-radius: 10px;
		border: 1px solid rgba(255, 255, 255, 0.12);
		background: rgba(0, 0, 0, 0.25);
		color: inherit;
		outline: none;
	}
	.grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
	}
	.actions {
		display: flex;
		gap: 10px;
		justify-content: flex-end;
		margin-top: 10px;
	}
	.btn {
		padding: 10px 14px;
		border-radius: 10px;
		border: 1px solid rgba(255, 255, 255, 0.12);
		background: rgba(255, 255, 255, 0.1);
		cursor: pointer;
	}
	.btn.secondary {
		background: transparent;
	}
	.btn:disabled {
		opacity: 0.6;
		cursor: not-allowed;
	}
	.alert {
		margin: 0 0 12px 0;
		padding: 10px 12px;
		border-radius: 10px;
		border: 1px solid rgba(255, 80, 80, 0.35);
		background: rgba(255, 80, 80, 0.12);
	}
	.err {
		display: block;
		margin-top: 6px;
		color: rgba(255, 120, 120, 0.95);
		font-size: 12px;
	}
	@media (max-width: 560px) {
		.grid {
			grid-template-columns: 1fr;
		}
	}
</style>
