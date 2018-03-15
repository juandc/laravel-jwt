# larostore-api
Laravel (sorry 😭) API for Larostore.

### TODOS
 - **Fix** migrations files (droping tables in "up" and "down") - *low priority*
 - **Edit** users table to divide name in first name and last name, add description
    and all that stuff - *medium priority*
 - **Edit** separe update-email, update-password, update-username and update basics
    (name and all updates from previous todo) in UserController - *medium priority*
 - **Create** a new table for deleted users, dont care about the time to really delete,
    just move the "deleted" user and add method to recover it. - *medium priority*
 - **Understand** how do JWT works, and if them doesn't save tokens in DB, how is
    posible to "refresh" them? - *super low priority*
