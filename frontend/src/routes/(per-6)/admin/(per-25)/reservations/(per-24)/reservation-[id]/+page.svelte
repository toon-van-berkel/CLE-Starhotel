<script lang="ts">
    import type { ReservationResponse } from '$lib/api/client/apiTypes';
    import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';
    export let data: { reservationsData: ReservationResponse };

    const reservation = data.reservationsData.record;
</script>

<Breadcrumbs
	names={['Admin Panel', 'Reservations', `Reservation-${reservation?.id}`]}
	links={['/admin', '/admin/reservations', `/admin/reservations/reservation-${reservation?.id}`]}
/>

{#if reservation}
    <h1>reservation {reservation.id}</h1>
    <p><b>ID: </b> {reservation.id}</p>
    <p><b>User: </b> {reservation.user_id}</p>
    <p><b>Room: </b> {reservation.room_id}</p>
    <br>
    <p><b>Booked at: </b> {reservation.booked_at}</p>
    <p><b>Booked from: </b> {reservation.booked_from}</p>
    <p><b>Booked till: </b> {reservation.booked_till}</p>
    <p><b>Checked in: </b> {reservation.checked_in}</p>
    <p><b>Checked in at: </b> {reservation.checked_in_at}</p>
    <p><b>Checked out at: </b> {reservation.checked_out_at}</p>
    <br>
    <p><b>Payment: </b> {reservation.payment_method}</p>
    <p><b>Status: </b> {reservation.status_id}</p>
{:else}
    <p>Not found</p>
{/if}
