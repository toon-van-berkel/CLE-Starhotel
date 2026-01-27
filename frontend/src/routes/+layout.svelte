<script lang="ts">
  export let data: { user: any };
</script>

<nav>
  <a href="/rooms">Rooms</a>
  |
  <a href="/contact">Contact</a>
  |
  <a href="/profile">Profile</a>
  |
  <a href="/admin">Admin</a>
  |
  <a href="/login">Login</a>
  |
  <a href="/register">Register</a>
</nav>

<hr />

<slot />
