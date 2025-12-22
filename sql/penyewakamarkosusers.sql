SELECT * from users u inner join rented_rooms rr on rr.user_id = u.id inner join rooms ro on rr.room_id = ro.id 
inner join kos k on ro.kos_id = k.id where k.id = 2;