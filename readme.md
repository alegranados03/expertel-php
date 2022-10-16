# EXPERTEL PHP

## Challenge Information

Assume we have this table in our database

```sql
CREATE TABLE `meetings` (id integer not null auto_increment primary key,
user_id  integer not null,
start_time datetime not null,
end_time datetime not null,
meeting_name varchar(100)
);
```

Your task is to write a function that will try to schedule a meeting for one or more users. Your function should detect any potential conflicts, if any user has a conflicting meeting it should print the user_id and the meeting name. Assume all times are in the same time zone. Meetings might span for more than 1 day. Feel free to write helper functions if needed.

```php
<?php
/*
    $start_time  datetime in format  Y-m-d H:i:s
    $end_time  datetime in format  Y-m-d H:i:s
    $users array  [1,2,4,5]
*/
function schedule_meeting($meeting_name, $start_time, $end_time, $users)
{
//your code here
}
?>
```

Sample usage:

```php
<?php
schedule_meeting('Status Meeting', '2022-09-27 09:00:00', '2022-09-27 10:00:00', [1,2,3]);
?>
```

Expected output:
`The meeting has been successfully booked.`

```php
<?php
schedule_meeting('Peer Review', '2022-09-27 09:30:00', '2022-09-27 10:00:00', [3,4]);
?>
```

Expected output:
`User 3 has a conflicting meeting: Status Meeting. The meeting has not been booked.`

## Solution explained

Within the project I have created a file: `index.php`, which contains the solution of this problem. In this file I have created a connection to a database, this means that the current solution could be used with a database with the following tables:

```sql
CREATE TABLE `users` (
    id integer NOT NULL PRIMARY KEY,
    name varchar(100) NOT NULL
    );


  CREATE TABLE `meetings` (
    id integer not null auto_increment primary key,
    user_id  integer not null,
    start_time datetime not null,
    end_time datetime not null,
    meeting_name varchar(100),
    FOREIGN KEY (user_id) REFERENCES users(id)
   );
```

Please remember to create the tables in the database before you use the solution.

### modifications

I modified the `schedule_meeting` method in order to pass a `mysqli` object as parameter, which will help the method to make transactions with the database.

```php
function schedule_meeting($mysqli, $meeting_name, $start_time, $end_time, $users){}
```

Remember to create the users in the `users` table first, in order to have some data to test the algorithm.

The `index.php` file currently has the following parameters for connection with mysql database:

```php
$serverName = 'localhost'; //server name
$user = 'root';
$password = '';
$db = 'expertel'; //database name
```

## Owner

[Alejandro Granados](https://github.com/alegranados03)
