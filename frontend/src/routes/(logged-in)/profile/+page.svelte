<script lang="ts">
  export let data: { user: any };
</script>

<h1>Profile</h1>

<pre>{JSON.stringify(data.user, null, 2)}</pre>
