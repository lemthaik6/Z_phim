<?php

return [
    // ==================== BOOKING SYSTEM WORKFLOW ====================

    /*
     * COMPLETE BOOKING & PAYMENT FLOW:
     * 
     * STEP 1: USER REGISTERS & LOGINS
     * POST /api/register
     * Body: {
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "password": "password123",
     *   "password_confirmation": "password123"
     * }
     * Response: { "user": {...}, "token": "..." }
     * 
     * STEP 2: GET ALL MOVIES
     * GET /api/movies?page=1
     * Response: { "data": [...], "links": {...}, "meta": {...} }
     * 
     * STEP 3: GET MOVIE DETAILS WITH SHOWTIMES
     * GET /api/movies/{movie_id}
     * Response: { "data": { "id": 1, "title": "...", "showtimes": [...] } }
     * 
     * STEP 4: GET SEATS FOR A SHOWTIME
     * GET /api/showtimes/{showtime_id}/seats
     * Response: [
     *   { "id": 1, "row": "A", "number": 1, "type": "regular", "status": "available" },
     *   { "id": 2, "row": "A", "number": 2, "type": "vip", "status": "booked" }
     * ]
     * 
     * STEP 5: CREATE BOOKING (select multiple seats)
     * POST /api/bookings
     * Headers: Authorization: Bearer {token}
     * Body: {
     *   "showtime_id": 1,
     *   "seat_ids": [1, 3, 5]
     * }
     * Response: { "message": "Booking created successfully, please proceed to payment" }
     * 
     * STEP 6: PROCESS PAYMENT
     * POST /api/payments
     * Headers: Authorization: Bearer {token}
     * Body: {
     *   "booking_id": 1,
     *   "amount": 45.00,
     *   "payment_method": "card"
     * }
     * Response: { "message": "Payment processed successfully" }
     * 
     * STEP 7: GET BOOKING DETAILS
     * GET /api/bookings/{booking_id}
     * Headers: Authorization: Bearer {token}
     * Response: {
     *   "data": {
     *     "id": 1,
     *     "user": {...},
     *     "showtime": {...},
     *     "seats": [
     *       { "id": 1, "row": "A", "number": 1, "type": "regular", "status": "available" }
     *     ],
     *     "total_amount": "45.00",
     *     "status": "paid",
     *     "payments": [...]
     *   }
     * }
     * 
     * STEP 8: CANCEL BOOKING (if needed)
     * POST /api/bookings/{booking_id}/cancel
     * Headers: Authorization: Bearer {token}
     * Response: { "message": "Booking cancelled successfully" }
     * 
     * ==================== KEY FEATURES ====================
     * 
     * 1. TOKEN-BASED AUTHENTICATION (Laravel Sanctum)
     *    - All protected routes require Authorization header with Bearer token
     *    - Automatic user association via request()->user()
     * 
     * 2. SEAT AVAILABILITY CHECK
     *    - Before booking, seats are checked for availability per showtime
     *    - Prevents double-booking through Seat::isBookedForShowtime()
     * 
     * 3. DYNAMIC PRICING
     *    - Regular seats: base price (showtime->price)
     *    - VIP seats: 1.5x base price
     *    - Total calculated automatically per booking
     * 
     * 4. DB TRANSACTIONS
     *    - All booking operations wrapped in DB::transaction()
     *    - Ensures data consistency if any step fails
     *    - Rollback on error prevents partial bookings
     * 
     * 5. BOOKING STATUSES
     *    - pending: Booking created, awaiting payment
     *    - paid: Payment completed
     *    - cancelled: User cancelled booking
     *    - refunded: Refund processed after cancellation
     * 
     * 6. PAYMENT VALIDATION
     *    - Amount must match booking total_amount exactly
     *    - Only pending/confirmed bookings can be paid
     *    - Duplicate payment protection
     *    - Authorization checks (user can only pay their own bookings)
     * 
     * 7. AUTHORIZATION
     *    - Users can only view/cancel their own bookings
     *    - Users can only pay for their own bookings
     *    - Authorization errors return 403 Forbidden
     */
];
