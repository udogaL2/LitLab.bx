DROP TABLE IF EXISTS up_LitLab_book;
DROP TABLE IF EXISTS up_LitLab_genre;
DROP TABLE IF EXISTS up_LitLab_genre_book;
DROP TABLE IF EXISTS up_LitLab_rating;
DROP TABLE IF EXISTS up_LitLab_author;
DROP TABLE IF EXISTS up_LitLab_book_author;
DROP TABLE IF EXISTS up_LitLab_image;
DROP TABLE IF EXISTS up_LitLab_bookshelf;
DROP TABLE IF EXISTS up_LitLab_user;
DROP TABLE IF EXISTS up_LitLab_user_bookshelf;
DROP TABLE IF EXISTS up_LitLab_bookshelf_book;
DROP TABLE IF EXISTS up_LitLab_tag;
DROP TABLE IF EXISTS up_LitLab_tag_bookshelf;
DROP TABLE IF EXISTS up_LitLab_likes;

DROP INDEX ix_perf_up_LitLab_bookshelf_1  ON `up_LitLab_bookshelf`;
DROP INDEX ix_perf_up_LitLab_bookshelf_2 ON `up_LitLab_bookshelf`;

DROP INDEX ix_perf_up_LitLab_book_1 ON `up_LitLab_book`;
DROP INDEX ix_perf_up_LitLab_book_2 ON `up_LitLab_book`;
DROP INDEX ix_perf_up_LitLab_book_3 ON `up_LitLab_book`;