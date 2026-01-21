<script lang="ts">
    import type {getUserInfoReservation} from '$lib/api/reservation/reservation';
    import type {upload} from '$lib/api/reservation/reservation';

    let submitted = false;

    let input = {
        first_name: "",
        last_name: "",
        email: "",
        booked_from: "",
        booked_till: "",
        totalPeople: "",
        max_capacity: "4",
    };

    let errors = {
        first_name: "",
        last_name: "",
        email: "",
        booked_from: "",
        booked_till: "",
        totalPeople: ""
    };

    function validateForm(e: Event) {
        e.preventDefault();
        submitted = true;

        errors.first_name = !input.first_name || !/^[A-Za-z\s]+$/.test(input.first_name)
            ? "First name is required (letters only)" : "";
        errors.last_name = !input.last_name || !/^[A-Za-z\s]+$/.test(input.last_name)
            ? "Last name is required (letters only)" : "";
        errors.email = !input.email ? "Email is required" : "";
        errors.booked_from = !input.booked_from ? "Check-in date is required" : "";
        errors.booked_till = !input.booked_till
            ? "Check-out date is required"
            : (input.booked_till <= input.booked_from ? "Check-out must be after check-in" : "");
        errors.totalPeople =
            !input.totalPeople ? "Number of people is required"
            : (parseInt(input.totalPeople) < 1 ? "Must be at least 1"
            : (parseInt(input.totalPeople) > parseInt(input.max_capacity) ? "Exceeds max capacity" : ""));

        if (!Object.values(errors).some(Boolean)) {
            // submit logic here (e.g., fetch/POST)
        }
    }
</script>

<h2>Reservation Form</h2>
<form id="reservationForm" on:submit={validateForm}>
    <div class="flex">
        <div class="form-group text-field-input flex-column">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" bind:value={input.first_name} class:invalid={!!errors.first_name}>
            <div class="error">{errors.first_name}</div>
        </div>

        <div class="form-group text-field-input flex-column">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" bind:value={input.last_name} class:invalid={!!errors.last_name}>
            <div class="error">{errors.last_name}</div>
        </div>
    </div>

    <div class="form-group text-field-input flex-column">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" bind:value={input.email} class:invalid={!!errors.email}>
        <div class="error">{errors.email}</div>
    </div>

    <div class="form-group flex-column text-field-input">
        <label for="bookedFrom">Check-in Date:</label>
        <input type="date" id="bookedFrom" name="bookedFrom" bind:value={input.booked_from} class:invalid={!!errors.booked_from}>
        <div class="error">{errors.booked_from}</div>
    </div>

    <div class="form-group flex-column text-field-input">
        <label for="bookedTill">Check-out Date:</label>
        <input type="date" id="bookedTill" name="bookedTill" bind:value={input.booked_till} class:invalid={!!errors.booked_till}>
        <div class="error">{errors.booked_till}</div>
    </div>

    <div class="form-group flex-column text-field-input">
        <label for="numberOfPeople">Number of People:</label>
        <input type="number" id="numberOfPeople" name="numberOfPeople" min="1" max={input.max_capacity} bind:value={input.totalPeople} class:invalid={!!errors.totalPeople}>
        <div class="error">{errors.totalPeople}</div>
    </div>

    <button class="buttonCenter" type="submit">Submit Reservation</button>
</form>

    <style>
    .buttonCenter {
        display: block;
        margin: 1.5rem auto 0;
    }
    input.invalid {
        border-color: #B42318 !important;
    }
    .error {
        color: #B42318;
        font-size: 0.85rem;
        min-height: 1.1rem;
    }
</style>