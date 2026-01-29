<script lang="ts">
  import "../../../scss/style.scss";
</script>

<section class="admin-page">
  <div class="admin-header">
    <span class="badge">Management</span>
    <h1>Admin Control Panel</h1>
    <p>
      Beheer de dagelijkse operaties van Starhotel. Selecteer een module om te
      bewerken.
    </p>
  </div>

  <div class="admin-grid">
    <a href="/admin/tickets" class="admin-card">
      <div class="card-icon">🎫</div>
      <div class="card-content">
        <h3>Support Tickets</h3>
        <p>Bekijk en beantwoord vragen van gasten.</p>
      </div>
      <span class="arrow">→</span>
    </a>

    <a href="/admin/reservations" class="admin-card">
      <div class="card-icon">📅</div>
      <div class="card-content">
        <h3>Reserveringen</h3>
        <p>Beheer alle boekingen en check-ins.</p>
      </div>
      <span class="arrow">→</span>
    </a>

    <a href="/admin/rooms" class="admin-card">
      <div class="card-icon">🏨</div>
      <div class="card-content">
        <h3>Kamerbeheer</h3>
        <p>Pas kamerdetails, prijzen en status aan.</p>
      </div>
      <span class="arrow">→</span>
    </a>
  </div>
</section>
