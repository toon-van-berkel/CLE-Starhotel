<script lang="ts">
  import Navbar from "$lib/components/Navbar.svelte";
  import Footer from "$lib/components/Footer.svelte";

  import "../scss/style.css";

	export let data: { user: any };
</script>

<Navbar user={data.user} />
<main>
<slot />
</main>
<Footer></Footer>