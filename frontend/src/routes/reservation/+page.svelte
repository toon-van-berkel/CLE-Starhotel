<script lang="ts">
    import type {getUserInfoReservation} from '$lib/api/reservation/reservation';

    let input = {
        first_name: "",
        last_name: "",
        email: "",
        booked_from: "",
        booked_till: ""
    }
</script>


<h2>Reservation Form</h2>
    <form id="reservationForm">
        <div class="flex">
            <div class="form-group text-field-input flex-column">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" bind:value={input.first_name}>
                <div class="error" id="firstNameError"></div>
            </div>

            <div class="form-group text-field-input flex-column">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" bind:value={input.last_name}>
                <div class="error" id="lastNameError"></div>
            </div>
        </div>


        <div class="form-group text-field-input flex-column">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" bind:value={input.email}>
            <div class="error" id="emailError"></div>
        </div>

        <div class="form-group flex-column text-field-input">
            <label for="bookedFrom">Check-in Date:</label>
            <input type="date" id="bookedFrom" name="bookedFrom" bind:value={input.booked_from}>
            <div class="error" id="bookedFromError"></div>
        </div>

        <div class="form-group flex-column text-field-input">
            <label for="bookedTill">Check-out Date:</label>
            <input type="date" id="bookedTill" name="bookedTill" bind:value={input.booked_till}>
            <div class="error" id="bookedTillError"></div>
        </div>

        <!-- <div class="form-group">
            <label for="numberOfPeople">Number of People:</label>
            <input type="number" id="numberOfPeople" name="numberOfPeople" min="1" value={current_capacity} required>
            <div class="error" id="peopleError"></div>
        </div> -->

        <button type="submit">Submit Reservation</button>
    </form>