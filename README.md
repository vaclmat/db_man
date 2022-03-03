"# db_man" 
Wordpress plugin for manipulation video database.

SQL command for creating databases:

CREATE TABLE wp_mbvideos ( id int(11) NOT NULL AUTO_INCREMENT, videoname varchar(255) NOT NULL, linktv varchar(255) NOT NULL, PRIMARY KEY (id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE wp_mbvideos_userwa ( id int(11) NOT NULL AUTO_INCREMENT, user_name varchar(255) NOT NULL, vid int(11) NOT NULL, PRIMARY KEY (id), FOREIGN KEY (vid) REFERENCES wp_mbvideos(id) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
