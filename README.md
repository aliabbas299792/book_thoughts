# Book Thoughts
Simple app to keep track of quotes/excerpts/interesting information relating to stuff I've read

- Uses a MySQL database, and PHP for the backend, the `book_thoughts.sql` file contains the SQL to generate the required tables into whichever database you choose.
- The front end uses yarn, and after everything is setup type in `./build.sh` to build the frontend and copy it to the parent directory.
- `db.link.php` in the `api` folder contains details to connect to the database and admin credentials
