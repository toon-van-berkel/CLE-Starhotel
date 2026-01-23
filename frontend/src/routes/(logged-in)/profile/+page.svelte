<script lang="ts">
	import { apiSubmit } from '$lib/api/client/apiSubmit';
	import { goto } from '$app/navigation';
	import { refreshMe, type AuthUser } from '$lib/api/auth/sessions';
	import { isUserAdmin, isUserStatus, hasPermission } from '$lib/api/auth/sessions';

	export let data: { user: AuthUser };

	let errorMsg = '';
	let loading = false;

	async function logout() {
		errorMsg = '';
		loading = true;

		try {
			await apiSubmit('logout', fetch, {});
			await refreshMe(fetch, { force: true }); // sets store to null
			await goto('/login');
		} catch (err) {
			errorMsg = err instanceof Error ? err.message : 'Logout failed';
		} finally {
			loading = false;
		}
	}
</script>

<h1>Profile</h1>

<section style="display:flex; flex-direction:column; gap: .25rem;">
	<p><b>ID:</b> {data.user.id}</p>
	<p><b>Name:</b> {data.user.first_name} {data.user.last_name}</p>
	<p><b>Email:</b> {data.user.email}</p>
	<p><b>Phone:</b> {data.user.phone}</p>
	<p><b>Status:</b> {data.user.status_id}</p>
</section>

<hr />

<section style="display:flex; flex-direction:column; gap:.25rem;">
	<p><b>Admin (role 1)?</b> {isUserAdmin(data.user) ? 'yes' : 'no'}</p>
	<p><b>Status is 1?</b> {isUserStatus(data.user, 1) ? 'yes' : 'no'}</p>
	<p><b>Status is 3?</b> {isUserStatus(data.user, 3) ? 'yes' : 'no'}</p>

	<p><b>Roles:</b> {data.user.role_ids?.length ? data.user.role_ids.join(', ') : 'none'}</p>
	<p><b>Permissions:</b> {data.user.permission_ids?.length ? data.user.permission_ids.join(', ') : 'none'}</p>
</section>

<hr />

<section>
	<h3>Permission checks</h3>
	<ul>
		<li>Permission 1 (Make review): {hasPermission(data.user, 1) ? 'yes' : 'no'}</li>
		<li>Permission 2 (Update review): {hasPermission(data.user, 2) ? 'yes' : 'no'}</li>
		<li>Permission 3 (Delete all reviews): {hasPermission(data.user, 3) ? 'yes' : 'no'}</li>
		<li>Permission 4 (Update all reviews): {hasPermission(data.user, 4) ? 'yes' : 'no'}</li>
		<li>Permission 5 (Delete review): {hasPermission(data.user, 5) ? 'yes' : 'no'}</li>
	</ul>
</section>

<button onclick={logout} disabled={loading}>
	{loading ? 'Logging out…' : 'Logout'}
</button>

{#if errorMsg}
	<p style="color:red;">{errorMsg}</p>
{/if}
