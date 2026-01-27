<script lang="ts">
  import "../../scss/style.scss";
  import type { AuthUser } from "$lib/api/types/user";
  export let user: AuthUser | null = null;
</script>

<nav>
  <ul>
    <div class="nav-group">
      <li><a class="logo-square" href="/">SH</a></li>
      <li><a class="hotel-name" href="/">Starhotel</a></li>
    </div>

    <div class="nav-group">
      <li><a class="nav-link" href="/about">About</a></li>
      <li><a class="nav-link" href="/rooms">Rooms</a></li>
      <li><a class="nav-link" href="/contact">Contact</a></li>

      {#if user}
        <li><a class="nav-link profile-btn" href="/profile">Profile</a></li>
      {:else}
        <li><a class="nav-link login-btn" href="/login">Login</a></li>
      {/if}
    </div>
  </ul>
</nav>
