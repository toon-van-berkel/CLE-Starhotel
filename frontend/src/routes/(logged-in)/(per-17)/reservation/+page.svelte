<script lang="ts">
    import type {getUserInfoReservation} from '$lib/api/reservation/reservation';
    import {upload} from '$lib/api/reservation/reservation';
    import {uploadTest} from '$lib/api/reservation/reservation';



	import dayjs from 'dayjs';
	import { Datepicker } from 'svelte-calendar';

    // let format = 'dddd, MMMM D, YYYY';
    let format = 'MMMM D, YYYY';

    

	let checkIn = $state(new Date());

	const endNextYear = dayjs().add(2, 'year').toDate();

      let checkOut = $state(new Date());


    onMount(async () => {
      checkIn.setDate(checkIn.getDate() + 1);

      checkOut.setDate(checkIn.getDate() + 2);
});
      
$effect(() => {
        console.log('checkIn:', checkIn);
        console.log('checkOut:', checkOut);
    });


    let isValid = $derived(checkOut > checkIn);

    let loadMessage = $state(false);
    let message = $state("");;

    






    import { goto } from '$app/navigation';

	import { apiCall } from '$lib/api/client/apiCall';
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import type { ApiError } from '$lib/api/client/apiBase';
	import type { Room } from '$lib/api/types/room';
	import type { ReservationApiRecord } from '$lib/api/types/reservation';
    import { onMount } from 'svelte';
    import { Logger } from 'sass-embedded';

	let rooms: Room[] = $state([]);
	let loadingRooms = $state(true);

	// form fields
	let room_id = $state(0);
	// let booked_from = $state('');
	// let booked_till = $state('');
      let booked_from = $derived(dayjs(checkIn).format('YYYY-MM-DD'));
    let booked_till = $derived(dayjs(checkOut).format('YYYY-MM-DD'));
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

		// if (!room_id) fe.room_id = 'Choose a room';
     if (!Number(room_id)) fe.room_id = 'Choose a room';
		if (!booked_from) fe.booked_from = 'Choose a start date';
		if (!booked_till) fe.booked_till = 'Choose an end date';

		if (checkOut <= checkIn) {
            fe.booked_till = 'End date must be after start date';
        }
		if (!payment_method.trim()) fe.payment_method = 'Payment method is required';

		fieldErrors = fe;
		return Object.keys(fe).length === 0;
	}

	async function loadRooms() {
		loadingRooms = true;
		try {
			const res = await apiCall('rooms', fetch);
			rooms = res.records ?? [];
		} catch (err) {
			const e = err as ApiError;
			formError = (e.data as any)?.error ?? e.message ?? 'Failed to load rooms';
		} finally {
			loadingRooms = false;
		}
	}

	async function submitReservation(e?: SubmitEvent) {
		e?.preventDefault();

		resetErrors();
		created = null;

		if (!clientValidate()) return;

		submitting = true;
		try {
// Debug payload
            console.log('Submitting payload:', {
                room_id: Number(room_id),
                booked_from,
                booked_till,
                payment_method
            });

            const res = await apiSubmit('reservation', fetch, {
                room_id: Number(room_id),
                booked_from,
                booked_till,
                payment_method
            });
            console.log('API response:', res);

            if ('ok' in res && res.ok === true) {
                created = (res as any).record ?? null;
                await goto('/admin/reservations');
                return;
            }

            formError = (res as any)?.error ?? 'Reservation failed';
            fieldErrors = (res as any)?.fields ?? {};
        } catch (err) {
            const e = err as ApiError;
        console.error('Submit error:', e);
        console.log('Submit error status:', (e as any)?.status);
        console.log('Submit error data:', (e as any)?.data);

        const data = (e.data ?? {}) as any;
        formError = data.error ?? e.message ?? 'Reservation failed';
        fieldErrors = data.fields ?? {};
        } finally {
            submitting = false;
        }
    }
    
	// Load rooms once (Svelte 5 way)
	let didInit = $state(false);
	$effect(() => {
		if (didInit) return;
		didInit = true;
		loadRooms();
	});
</script>



<h2>Reservation Form</h2>

{#if formError}
    <div class="error-banner">{formError}</div>
{/if}

{#if loadMessage}
    <div class="notification">{message}</div>
{/if}

<form onsubmit={submitReservation}>
    <div class="form-group flex-column text-field-input">
        <label for="room">Room:</label>
        {#if loadingRooms}
            <p>Loading rooms...</p>
        {:else}
            <select id="room" bind:value={room_id} class:invalid={!!fieldErrors.room_id}>
                <option value={0}>Select a room</option>
                {#each rooms as room (room.id)}
                    <option value={room.id}>room {room.id}</option>
                {/each}
            </select>
        {/if}
        {#if fieldErrors.room_id}
            <div class="error">{fieldErrors.room_id}</div>
        {/if}
    </div>





    <div class="datums">
        <div class="check">
            <p>Check-in</p>
            <Datepicker bind:selected={checkIn} start={checkIn} end={endNextYear} {format} />
        </div>


        <div class="check">
            <p>Check-out</p>
            <div class:fout={!isValid}>
              
                <Datepicker bind:selected={checkOut} start={checkIn} end={endNextYear} {format} />
            </div>
            {#if fieldErrors.booked_till}
                <div class="error">{fieldErrors.booked_till}</div>
            {/if}
        </div>
    </div>

    <div class="form-group flex-column text-field-input">
        <label for="payment">Payment Method:</label>
        <input type="text" id="payment" bind:value={payment_method} class:invalid={!!fieldErrors.payment_method} />
        {#if fieldErrors.payment_method}
            <div class="error">{fieldErrors.payment_method}</div>
        {/if}
    </div>

    <button disabled={!isValid || submitting} class="buttonCenter" type="submit">
        {submitting ? 'Submitting...' : 'Submit Reservation'}
    </button>
</form>


    <!-- <Datepicker {format} start={checkIn} end={endNextYear} />
    <Datepicker {format} start={startToday} end={endNextYear} /> -->


    <!-- <div class="form-group flex-column text-field-input">
        <label for="bookedFrom">Check-in Date:</label>
        <input type="date" id="bookedFrom" name="bookedFrom" bind:value={input.booked_from} class:invalid={!!errors.booked_from}>
        <div class="error">{errors.booked_from}</div>
    </div>

    <div class="form-group flex-column text-field-input">
        <label for="bookedTill">Check-out Date:</label>
        <input type="date" id="bookedTill" name="bookedTill" bind:value={input.booked_till} class:invalid={!!errors.booked_till}>
        <div class="error">{errors.booked_till}</div>
    </div> -->

    <!-- <div class="form-group flex-column text-field-input">
        <label for="numberOfPeople">Number of People:</label>
        <input type="number" id="numberOfPeople" name="numberOfPeople"
       min="1" max={max_capacity} bind:value={input.totalPeople} class:invalid={!!errors.totalPeople}>
        <div class="error">{errors.totalPeople}</div>
    </div> -->

    <!-- <button disabled={!isValid} on:click={handleBooking} class="buttonCenter" type="submit">Submit Reservation</button> -->
<!-- </form>  -->



<style>

    button {
    width: 100%;
    padding: 12px;
    background: #0f4a5a;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
  }

  button:hover {
    background: #125a6e
  }
  button:disabled {
    background: #ccc;
    cursor: not-allowed;
  }
</style>