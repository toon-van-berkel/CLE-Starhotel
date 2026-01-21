export type Contact = {
  id: number;
  name: string;
  email: string;
  reason: string;
  title: string;
  message: string;
  status_id: number;
  admin_handled_id: number | null;
  created_at: string;
  handled_at: string | null;
  user_id: number | null;
};

export type ContactInput = {
  name: string;
  email: string;
  reason: string;
  title: string;
  message: string;
  user_id?: number | null;
};
