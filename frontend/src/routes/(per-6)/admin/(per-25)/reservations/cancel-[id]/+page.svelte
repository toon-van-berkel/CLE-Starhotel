<script lang="ts">
	import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';
	import { goto } from '$app/navigation';

	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import type { ApiError } from '$lib/api/client/apiBase';

	export let data: { id: number; reservationData: any };

	const r = data.reservationData?.record ?? null;

	let submitting = false;
	let error = '';

	async function confirmCancel() {
		error = '';
		submitting = true;

		try {
			const res = await apiSubmit('reservationCancel', fetch, { id: data.id });

			if (res?.ok === true) {
				await goto('/admin/reservations');
				return;
			}

			error = res?.error ?? 'Cancel failed';
		} catch (err) {
			const e = err as ApiError;
			const d = (e.data ?? {}) as any;
			error = d.error ?? e.message ?? 'Cancel failed';
		} finally {
			submitting = false;
		}
	}
</script>

<Breadcrumbs
	names={['Admin Panel', 'Reservations', `Cancel #${data.id}`]}
	links={['/admin', '/admin/reservations', `/admin/reservations/cancel-${data.id}`]}
/>

<h1>Cancel reservation</h1>

{#if data.reservationData?.error}
	<div class="alert">{data.reservationData.error}</div>
{:else if !r}
	<div class="alert">Reservation not found.</div>
{:else}
	{#if error}
		<div class="alert">{error}</div>
	{/if}

	<div class="card warnCard">
		<h2>Are you sure?</h2>
		<p class="muted">
			This will <b>cancel</b> reservation <span class="mono">#{r.id}</span> (status will be set to cancelled).
		</p>

		<div class="summary">
			<div><span class="k">User</span><span class="v">{r.user_id}</span></div>
			<div><span class="k">Room</span><span class="v">{r.room_id}</span></div>
			<div><span class="k">From</span><span class="v mono">{r.booked_from}</span></div>
			<div><span class="k">Till</span><span class="v mono">{r.booked_till}</span></div>
			<div><span class="k">Payment</span><span class="v">{r.payment_method}</span></div>
			<div><span class="k">Status</span><span class="v">{r.status_id}</span></div>
		</div>

		<div class="actions">
			<button class="btn" type="button" onclick={() => goto('/admin/reservations')} disabled={submitting}>
				Back
			</button>
			<button class="btn warn" type="button" onclick={confirmCancel} disabled={submitting}>
				{submitting ? 'Cancelling…' : 'Confirm cancel'}
			</button>
		</div>
	</div>
{/if}

<style>
	h1{margin:12px 0 14px;font-size:28px}
	h2{margin:0 0 6px}
	.muted{opacity:.75;margin:0}
	.mono{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}

	.alert{margin-top:14px;padding:12px 14px;border-radius:12px;border:1px solid rgba(255,120,120,.35);background:rgba(255,120,120,.12)}

	.card{max-width:720px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:16px;backdrop-filter:blur(10px)}
	.warnCard{border-color:rgba(255,200,80,.35);background:rgba(255,200,80,.06)}

	.summary{margin-top:14px;display:grid;grid-template-columns:1fr 1fr;gap:10px}
	.summary>div{display:flex;justify-content:space-between;gap:12px;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.2)}
	.k{opacity:.7}
	.v{font-weight:600}

	.actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px}

	.btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.08);color:inherit;cursor:pointer}
	.btn:hover{background:rgba(255,255,255,.12)}
	.btn.warn{border-color:rgba(255,200,80,.35);background:rgba(255,200,80,.14)}
	.btn:disabled{opacity:.6;cursor:not-allowed}

	@media (max-width:560px){.summary{grid-template-columns:1fr}}
</style>
