export const load = async ({ fetch, params }) => {
    const id = Number(params.id);
    return { id };
};
