<script lang="ts">
    import type {getUserInfoReservation} from '$lib/api/reservation/reservation';

    let input = {
        first_name: "",
        last_name: "",
        email: "",
        booked_from: "",
        booked_till: "",
        totalPeople: "",
        max_capacity: "4",
    }

    let errors = {
        first_name: false,
        last_name: false,
        email: false,
        booked_from: false,
        booked_till: false,
        totalPeople: false
    };

    function validateForm(e: Event) {
        e.preventDefault();
        
        errors.first_name = !input.first_name || !/^[A-Za-z\s]+$/.test(input.first_name);
        errors.last_name = !input.last_name || !/^[A-Za-z\s]+$/.test(input.last_name);
        errors.email = !input.email;
        errors.booked_from = !input.booked_from;
        errors.booked_till = !input.booked_till || input.booked_till <= input.booked_from;
        errors.totalPeople = !input.totalPeople || parseInt(input.totalPeople) < 1 || parseInt(input.totalPeople) > parseInt(input.max_capacity);

        if (!Object.values(errors).includes(true)) {
            // Submit logic here
        }
    }
    
</script>


<h2>Reservation Form</h2>
    <form id="reservationForm" on:submit={validateForm}>
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

        <div class="form-group flex-column text-field-input">
            <label for="numberOfPeople">Number of People:</label>
            <input type="number" id="numberOfPeople" name="numberOfPeople" min="1" max={input.max_capacity} bind:value={input.totalPeople} required>
            <div class="error" id="peopleError"></div>
        </div>

        <button class="buttonCenter" type="submit">Submit Reservation</button>
    </form>