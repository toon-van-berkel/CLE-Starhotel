<script lang="ts">
  import "../../../scss/style.scss";
  import { apiSubmit } from "$lib/api/client/apiSubmit";
  import { goto, invalidateAll } from "$app/navigation";
  import { refreshMe } from "$lib/api/auth/sessions";
  import type { AuthUser } from "$lib/api/types/user";

  export let data: { user: AuthUser };

  let errorMsg = "";
  let loading = false;

  async function logout() {
    errorMsg = "";
    loading = true;

    try {
      await apiSubmit("logout", fetch, {});
      await refreshMe(fetch, { force: true });
      await invalidateAll();
      await goto("/login", { replaceState: true, invalidateAll: true });
    } catch (err) {
      errorMsg = err instanceof Error ? err.message : "Afmelden mislukt";
    } finally {
      loading = false;
    }
  }
</script>

<div class="profile-page">
  <header class="profile-header">
    <span class="subtitle">Persoonlijke gegevens</span>
    <h1>Welkom, {data.user.first_name}</h1>
    <p>Hier vindt u uw accountgegevens en kunt u uw sessie beheren.</p>
  </header>

  <div class="profile-card">
    <div class="info-section">
      <div class="info-grid">
        <div class="info-item">
          <span class="label">Voornaam</span>
          <p class="value">{data.user.first_name}</p>
        </div>

        <div class="info-item">
          <span class="label">Achternaam</span>
          <p class="value">{data.user.last_name}</p>
        </div>

        <div class="info-item">
          <span class="label">E-mailadres</span>
          <p class="value">{data.user.email}</p>
        </div>

        <div class="info-item">
          <span class="label">Telefoonnummer</span>
          <p class="value">{data.user.phone || "Niet opgegeven"}</p>
        </div>
      </div>
    </div>

    <div class="profile-actions">
      <button class="logout-btn" onclick={logout} disabled={loading}>
        {loading ? "Bezig met afmelden..." : "Afmelden"}
      </button>
    </div>
  </div>

  {#if errorMsg}
    <div class="error-toast">{errorMsg}</div>
  {/if}
</div>
