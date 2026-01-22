// import type { PageLoad } from "./$types";
// import { redirect, error } from "@sveltejs/kit";
// import { loadAuth } from "$lib/auth/auth";

// export const load: PageLoad = async ({ parent }) => {
//   const auth = await loadAuth(parent);

//   if (auth.isUserLoggedOut()) throw redirect(302, "/login");
//   if (!auth.isUserStatus(1, 3)) throw error(403, "Forbidden");
//   // if (!auth.isUserAdmin()) throw error(403, 'Admins only');

//   return { user: auth.user };
// };
