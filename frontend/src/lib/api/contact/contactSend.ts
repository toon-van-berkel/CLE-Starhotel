import { api } from "$lib/api/client/api";

export type ContactPayload = {
  //data die wordt verstuurd naar de backend
  name: string;
};

export type ContactResponse = {
  ok: true;
  id?: number;
};

export function sendContact(payload: ContactPayload) {
  return api<ContactResponse>("/contact", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });
}
