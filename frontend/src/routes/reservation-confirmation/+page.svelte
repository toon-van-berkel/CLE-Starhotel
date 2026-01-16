

<script lang="ts">
import {getReservationDetails} from '$lib/api/confirm/confirmation';

let detailsPromise: Promise<any>;
    let debugInfo = '';

// Call the function with the parameters (these need to come from your reservation form/store)
async function loadDetails() {
    const name = "Room A"; // Replace with actual value from params/store
    const totalPeople = 2; // Replace with actual value
    const bookedFrom = "2024-07-15"; // Replace with actual value
    const bookedTill = "2024-07-20"; // Replace with actual value
    
    

    debugInfo = `Sending: name=${name}, totalPeople=${totalPeople}, from=${bookedFrom}, till=${bookedTill}`;

    const response = await getReservationDetails(name, totalPeople, bookedFrom, bookedTill);
    console.log('API Response:', response); // Debug

    try {
        const response = await getReservationDetails(name, totalPeople, bookedFrom, bookedTill);
        console.log('API Response:', response);
        console.log('Response type:', typeof response);
        
        // Return whatever we get
        return response;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }


    
    
        console.log('Details:', response.details); // Debug
        return response.details;
    
    throw new Error(`Failed to load details: ${JSON.stringify(response)}`);

    // return response;
}

detailsPromise = loadDetails();
</script>

<p>{debugInfo}</p>

<h1 class="textcenter header">De reservatie is voltooid</h1>
<p class="textcenter ordernumber">Ordernummer: #SH-19357</p>
<p class="textcenter confirmtext">Dankuwel van uw reservatie van:</p>



{#await detailsPromise then response}
    <p>Response: {JSON.stringify(response)}</p>
    {#if response.ok && response.details}
        <ul class="textcenter margin">
            <li class="listtext">{response.details?.name}</li>
            <li class="listtext">voor {response.details?.current_capacity} personen</li>
            <li class="listtext">van {response.details?.booked_from} tot {response.details?.booked_till}</li>
        </ul>
    {/if}
{:catch error}
    <p>Error: {error.message}</p>
{/await}