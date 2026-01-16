

<script lang="ts">


// informatie ophalen van de reservatie
// values voor de kamer, aantal personen, datum reservatie ophalen. (moeten worden doorgegeven vanaf de reservatie pagina)

// weergeven van de informatie




import {getReservationDetails} from '$lib/api/confirm/confirmation';

    // let getReservationDetails = {
    //     kamer: "Deluxe Suite",
    //     aantal_personen: 2,
    //     booked_from: "2024-07-15",
    //     booked_till: "2024-07-20"
    // }
</script>

<h1 class="textcenter header">De reservatie is voltooid</h1>
<p class="textcenter ordernumber">ordernummer: #SH-19357</p>
<p class="textcenter confirmtext">Dankuwel van uw reservatie van:</p>

    <ul class="textcenter margin">
        <li class="listtext">{getReservationDetails.room_id}</li>
        <li class="listtext">voor {getReservationDetails.totalPeople} personen</li>
        <li class="listtext">van {getReservationDetails.booked_from} tot {getReservationDetails.booked_till}</li>
    </ul>