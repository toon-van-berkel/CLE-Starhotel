import { api } from "$lib/api/client/apiBase";

export type ContactPayload = {
  //data die wordt verstuurd naar de backend
  name: string;
};

export type ContactResponse = {
  ok: true;
  id?: number;
};

export function sendContact(payload: ContactPayload) {
  return api<ContactResponse>(fetch, "/contact", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });
}
