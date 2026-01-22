<script lang="ts">
  import favicon from "$lib/assets/favicon.svg";
  import Navbar from "$lib/components/Navbar.svelte";
  // import { onMount } from "svelte";
  // import { authStore } from "$lib/api/stores/auth";
  import "../scss/style.css";

  let { children } = $props();

  // onMount(() => {
  //   authStore.init();
  // });
</script>

<svelte:head>
  <link rel="icon" href={favicon} />
</svelte:head>

<Navbar></Navbar>
{@render children()}