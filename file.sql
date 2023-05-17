DROP database `etistudents`;

CREATE database `etistudents` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE `etistudents`.`users` (
    `user_id` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` BOOLEAN NOT NULL DEFAULT false,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `etistudents`.`groups` (
    `group_id` INTEGER NOT NULL AUTO_INCREMENT,
    `group_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `etistudents`.`user_groups` (
     id SERIAL PRIMARY KEY,
     user_id INTEGER NOT NULL REFERENCES `etistudents`.`users`(user_id),
     group_id INTEGER NOT NULL REFERENCES `etistudents`.`groups`(group_id),
     CONSTRAINT `fk_user_groups_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `etistudents`.`users` (`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_user_groups_group_id`
        FOREIGN KEY (`group_id`) REFERENCES `etistudents`.`groups` (`group_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `etistudents`.`user_details` (
    `user_id` INTEGER NOT NULL,
    `user_name` VARCHAR(50) NOT NULL,
    `user_lastname` VARCHAR(50) NOT NULL,
    `user_dob` DATE NOT NULL,
    `user_course` INT NOT NULL,
    `user_speciality` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_user_details_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `etistudents`.`users` (`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `etistudents`.`news`
(
    `news_id` INTEGER NOT NULL AUTO_INCREMENT,
    `news_title` VARCHAR(255) NOT NULL,
    `news_short_title` VARCHAR(40) NOT NULL,
    `news_content` TEXT NOT NULL,
    `news_user_id` INT NOT NULL,
    `news_user_login` VARCHAR(64) NOT NULL,
    `image_url` VARCHAR(255) NULL,
    `news_date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `news_date_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`news_id`),
    CONSTRAINT `fk_news_user_id`
        FOREIGN KEY (`news_user_id`) REFERENCES `etistudents`.`users` (`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `etistudents`.`news_comments` (
    comment_id SERIAL PRIMARY KEY,
    user_id INT,
    news_id INT,
    comment_text TEXT,
    created_at TIMESTAMP,
    CONSTRAINT `fk_news_comments_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `etistudents`.`users` (`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_news_comments_news_id`
        FOREIGN KEY (`news_id`) REFERENCES `etistudents`.`news` (`news_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('admin');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('moderator');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('user');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('student');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('teacher');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('guest');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('schoolchild');
INSERT INTO `etistudents`.`groups` (`group_name`) VALUES ('administration');

