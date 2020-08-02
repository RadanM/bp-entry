CREATE TABLE answers (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `question_id` int(11) NOT NULL,
    `text` varchar(255) NOT NULL,
    `right` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE entry_codes (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mail` varchar(255) NOT NULL,
    `code` varchar(5) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (mail),
    UNIQUE (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE questions (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `text` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE results (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `question_id` int(11) NOT NULL,
    `answer_id` int(11) NOT NULL,
    `entry_code_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `results_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
    CONSTRAINT `results_ibfk_2` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
    CONSTRAINT `results_ibfk_3` FOREIGN KEY (`entry_code_id`) REFERENCES `entry_codes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;