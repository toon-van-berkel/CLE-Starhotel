export const endpoints = {
  // Auth
  me: '/api/me',
  login: '/api/login',
  logout: '/api/logout',
  register: '/api/register',

  // Rooms (jij had: /api/rooms en /api/rooms/room-<id>)
  roomsList: '/api/rooms',
  roomDetail: (roomId: number) => `/api/rooms/room-${roomId}`,

  // Tickets / contacts
  ticketsList: '/api/contacts',
  ticketCreate: '/api/contacts',
  ticketUpdate: (ticketId: number) => `/api/contacts/update-${ticketId}`,
  ticketDelete: (ticketId: number) => `/api/contacts/delete-${ticketId}`,

  // Reservations
  reservationsList: '/api/reservations',
  reservationCreate: '/api/reservations',
  reservationDetail: (reservationId: number) => `/api/reservations/reservation-${reservationId}`,
  reservationUpdate: (reservationId: number) => `/api/reservations/update-${reservationId}`,
  reservationDelete: (reservationId: number) => `/api/reservations/delete-${reservationId}`
};
