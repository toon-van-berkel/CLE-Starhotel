<script lang="ts">
	import type { ReservationsListResponse } from '$lib/api/types/reservation';
	import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';

	export let data: { reservationsData: ReservationsListResponse };
	let reservations = data.reservationsData;

	function formatDateTime(v: string | null | undefined) {
		if (!v) return '';
		// keep it simple; you can change formatting later
		return v.replace('T', ' ').replace('.000Z', '');
	}
</script>

<Breadcrumbs
	names={['Admin Panel', 'Reservations']}
	links={['/admin', '/admin/reservations']}
/>

<div class="pageHead">
	<div>
		<h1>Reservations</h1>
		<p class="muted">Overview of all reservations.</p>
	</div>

	<a class="btn primary" href="/admin/reservations/create">POST</a>
</div>

{#if reservations.error}
	<div class="alert">{reservations.error}</div>
{:else}
	<div class="tableWrap">
		<table class="adminTable">
			<thead>
				<tr>
					<th>ID</th>
					<th>User</th>
					<th>Room</th>
					<th>Booked at</th>
					<th>Booked from</th>
					<th>Booked till</th>
					<th>Checked in</th>
					<th>Checked in at</th>
					<th>Checked out at</th>
					<th>Payment</th>
					<th>Status</th>
					<th class="actionsCol">Actions</th>
				</tr>
			</thead>

			<tbody>
				{#each reservations.records as r}
					<tr>
						<td class="mono">{r.id}</td>

						<td>
							<a class="link" href={`/admin/users/user-${r.user?.id ?? r.user_id}`}>
								User {r.user?.id ?? r.user_id}
							</a>
						</td>

						<td>
							<a class="link" href={`/admin/rooms/room-${r.room_id}`}>
								Room {r.room_id}
							</a>
						</td>

						<td class="mono">{formatDateTime(r.booked_at)}</td>
						<td class="mono">{r.booked_from}</td>
						<td class="mono">{r.booked_till}</td>

						<td>
							{#if r.checked_in === 1}
								<span class="badge ok">Checked in</span>
							{:else}
								<span class="badge">Not checked in</span>
							{/if}
						</td>

						<td class="mono">
							{#if r.checked_in_at}
								{formatDateTime(r.checked_in_at)}
							{:else}
								<span class="muted">—</span>
							{/if}
						</td>

						<td class="mono">
							{#if r.checked_out_at}
								{formatDateTime(r.checked_out_at)}
							{:else}
								<span class="muted">—</span>
							{/if}
						</td>

						<td><span class="badge">{r.payment_method}</span></td>

						<td><span class="badge neutral">#{r.status_id}</span></td>

						<td class="actions">
							<a class="btn small" href={`/admin/reservations/reservation-${r.id}`}>View</a>
							<a class="btn small" href={`/admin/reservations/update-${r.id}`}>Edit</a>
							<a class="btn small danger" href={`/admin/reservations/delete-${r.id}`}>Delete</a>
						</td>
					</tr>
				{/each}
			</tbody>
		</table>
	</div>
{/if}