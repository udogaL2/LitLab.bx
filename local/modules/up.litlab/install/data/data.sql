insert into up_LitLab_author (NAME)
values ('Александра Маринина'),
       ('Тамара Петкевич'),
       ('Ольга Громыко'),
       ('Сергей Лукьяненко'),
       ('Наталья Андреева'),
       ('Майк Омер'),
       ('Эдит Ева Эгер'),
       ('Anne Dar');

insert into up_LitLab_book_author(BOOK_ID, AUTHOR_ID)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (5, 5),
       (6, 6),
       (7, 7),
       (8, 8),
       (9, 2);

insert into up_LitLab_genre(TITLE)
values ('Биографии и мемуары'),     # 1
       ('Зарубежная публицистика'), # 2
       ('Истории из жизни'),        # 3
       ('Современные детективы'),   # 4
       ('Публицистика'),            # 5
       ('Биографии и мемуары'),     # 6
       ('Историческая литература'), # 7
       ('Литература 20 века'),      # 8
       ('Боевая фантастика'),       # 9
       ('Космическая фантастика'),  # 10
       ('Научная фантастика'),      # 11
       ('Триллеры'),                # 12
       ('Современные любовные романы'); # 13

insert into up_LitLab_genre_book (GENRE_ID, BOOK_ID)
VALUES (1, 7),
       (2, 7),
       (3, 7),
       (4, 1),
       (5, 2),
       (1, 2),
       (6, 2),
       (7, 2),
       (9, 3),
       (10, 3),
       (10, 4),
       (11, 4),
       (11, 5),
       (4, 6),
       (12, 6),
       (4, 8),
       (12, 8),
       (13, 8),
       (5, 9),
       (1, 9),
       (6, 9),
       (7, 9);

insert into up_LitLab_bookshelf (CREATOR_ID, TITLE, DESCRIPTION, LIKES, DATE_CREATED, DATE_UPDATED, STATUS)
values (1, 'Тестовая полка', 'Полка для тестов', 1, '2023-04-14 14:42:13', '2023-04-14 14:42:13', 'public');

insert into up_LitLab_bookshelf_book (BOOKSHELF_ID, BOOK_ID, COMMENT)
VALUES (1, 1, 'интересно'),
       (1, 2, 'читать обязательно'),
       (1, 3, 'дада');

insert into up_LitLab_tag (TITLE)
values ('книги 2022');

insert into up_LitLab_tag_bookshelf (TAG_ID, BOOKSHELF_ID)
values (1, 1);