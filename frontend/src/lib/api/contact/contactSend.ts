import { api } from "$lib/api/client/apiBase";

export type ContactPayload = {
  //data die wordt verstuurd naar de backend
  name: string;
  email: string;
  reason: string;
  title: string;
  message: string;
};

export type ContactResponse = {
  ok: true;
  id?: number;
};

export function sendContact(payload: ContactPayload) {
  return api<ContactResponse>(fetch, "/api/contact", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });
}
