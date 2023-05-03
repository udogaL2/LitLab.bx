<?php

namespace Up\Litlab\API;
use Bitrix\Main\ORM\Query\Query;
use Up\LitLab\Model\AuthorTable;
use Up\LitLab\Model\BookBookshelfTable;
use Up\Litlab\Model\BookTable;
use Up\LitLab\Model\GenreTable;
use Up\LitLab\Model\RatingTable;

class Book
{
	public function getListOfBook(?int $limit = 3, int $offset = 0, array $status = ['public'], string $search = '', int $genre_id = null): array
	{
		$query = BookTable::query()
			->setSelect(['ID', 'TITLE', 'DESCRIPTION', 'IMAGE_ID', 'PUBLICATION_YEAR', 'ISBN', 'STATUS', 'DATE_CREATED'])
			->whereIn('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			->setOrder(['BOOK_RATING'=>'DESC'])
			;

		if ($search)
		{
			$query = $query
				->where(Query::filter()
							 ->logic('or')
							 ->where([
								  ['ISBN', $search],
								  ['TITLE', 'like', '%'.$search.'%']
							 ]));
		}

		if ($genre_id)
		{
			$query = $query->where('GENRES.ID', $genre_id);
		}

		return $query->fetchAll();
	}
	public function checkBookInBookshelf(int $bookId, int $bookshelfId):bool
	{
		return (bool)BookBookshelfTable::query()
							   ->setSelect(['*'])
						 	   ->where('BOOK_ID', $bookId)
						 	   ->where('BOOKSHELF_ID', $bookshelfId)
							   ->fetchAll()[0];

	}


	public function getListOfRequest(?int $limit = 3, int $offset = 0, array $status = ['moderation']): array
	{
		$query = BookTable::query()
						  ->setSelect(['ID', 'TITLE', 'DESCRIPTION'])
						  ->whereIn('STATUS', $status)
						  ->setLimit($limit)
						  ->setOffset($offset)
		;

		return $query->fetchAll();
	}

	public function getListOfBookByBookshelf(int $bookshelfId, ?int $limit = 3, int $offset = 0, string $status = 'public'): array
	{
		return BookTable::query()
			->setSelect(['*'])
			->where('BOOK.BOOKSHELF_ID', $bookshelfId)
			->where('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			->fetchAll()
		;
	}

	public function getCount(string $search = '', array $status = ['public'], int $genre_id = null): int
	{
		$query = BookTable::query()
		   ->whereIn('STATUS', $status)
		;

		if ($search)
		{
			$query = $query
				->where(Query::filter()
							 ->logic('or')
							 ->where([
										 ['ISBN', $search],
										 ['TITLE', 'like', '%'.$search.'%']
									 ]));
		}

		if ($genre_id)
		{
			$query = $query->where('GENRES.ID', $genre_id);
		}

		return count($query->fetchAll());
	}

	public function getCountInBookshelf(int $bookshelfId, string $status = 'public'): int
	{
		return BookTable::getCount(
			[
				'BOOK.BOOKSHELF_ID' => $bookshelfId,
				'STATUS' => $status
			]
		);
	}

	public function getDetailsById(int $id, array $status = ['public']): array|false
	{
		return BookTable::query()
			->setSelect(['ID', 'TITLE', 'DESCRIPTION', 'IMAGE_ID', 'PUBLICATION_YEAR', 'ISBN', 'STATUS', 'DATE_CREATED', 'BOOK_RATING'])
			->where('ID', $id)
			->whereIn('STATUS', $status)
			->fetch()
			;
	}

	public function getDetailsByIdForEdit(int $id, array $status = ['public']): array|false
	{
		return BookTable::query()
						->setSelect(['ID', 'TITLE', 'DESCRIPTION', 'IMAGE_ID', 'PUBLICATION_YEAR', 'ISBN', 'STATUS', 'DATE_CREATED'])
						->where('ID', $id)
						->whereIn('STATUS', $status)
						->fetch()
			;
	}

	public function getAuthors(int $bookId): array
	{
		$query = BookTable::getByPrimary($bookId, ['select' => ['A_ID' => 'AUTHORS.ID', 'A_NAME' => 'AUTHORS.NAME']])
			->fetchAll()
			;

		$result = [];

		foreach ($query as $item)
		{
			if (!$result[$item['A_ID']])
			{
				$result[$item['A_ID']] = $item['A_NAME'];
			}
		}

		return $result;
	}

	public function getAuthorForEachBook(array $bookIds): array
	{
		$query = BookTable::query()
			->setSelect(['ID', 'NAME' => 'AUTHORS.NAME'])
			->whereIn('ID', $bookIds)
			->fetchAll()
			;

		$result = [];

		foreach ($query as $item)
		{
			if (!$result[$item['ID']])
			{
				$result[$item['ID']] = $item['NAME'];
			}
		}

		return $result;
	}

	public function getGenres(int $bookId): array
	{
		$genres = BookTable::getByPrimary($bookId, ['select' => ['G_ID' => 'GENRES.ID', 'G_TITLE' => 'GENRES.TITLE']])
						->fetchAll()
			;

		$result = [];

		foreach ($genres as $genre)
		{
			$result[$genre['G_ID']] = $genre['G_TITLE'];
		}

		return $result;
	}

	public function getImage($bookshelfId, int $limit = 1){
		$result = BookTable::query()
			->setSelect(['BS_ID' => 'BOOK.BOOKSHELF_ID', 'IMAGE_ID'])
			->setFilter(['BOOK.BOOKSHELF_ID' => $bookshelfId])
			->setLimit($limit)
			->fetchAll();

		return $result[0]['IMAGE_ID'];

	}

	public function getImages(array $bookshelfId, int $limit = 3)
	{
		$images = BookTable::query()
			->setSelect(['B_ID' => 'BOOK.BOOK_ID','BS_ID' => 'BOOK.BOOKSHELF_ID', 'IMAGE_ID'])
			->where('BOOK.BOOKSHELF_ID', 'in', $bookshelfId)
			->where('STATUS', 'public')
			->fetchAll()
			;

		$result = [];

		foreach ($images as $image)
		{
			if ($result[$image['BS_ID']] && count($result[$image['BS_ID']]) >= $limit)
			{
				continue;
			}

			$result[$image['BS_ID']][$image['B_ID']] = $image['IMAGE_ID'];
		}

		return $result;
	}

	public function getAllGenres(): array
	{
		$query = GenreTable::query()
			->setSelect(['*'])
			->fetchAll()
		;

		$result = [];

		foreach ($query as $tag)
		{
			$result[$tag['ID']] = $tag['TITLE'];
		}

		return $result;
	}

	public function addBook(array $params)
	{
		return BookTable::add($params);
	}
	public function deleteBook(int $id){
		return BookTable::delete($id);
	}

	public function updateBook(int $id, array $params){
		return BookTable::update($id, $params);
	}

	public function isMadeEstimation(int $bookId, int $userId): bool
	{
		return (bool)RatingTable::query()
								   ->setSelect(['BOOK_ID'])
								   ->where('USER_ID', $userId)
								   ->where('BOOK_ID', $bookId)
								   ->fetchAll()[0]
			;
	}

	public function getUserEstimation(int $userId, int $bookId)
	{
		$result = RatingTable::query()
						   ->setSelect(['ESTIMATION'])
						   ->where('USER_ID', $userId)
						   ->where('BOOK_ID', $bookId)
						   ->fetchAll();

		return $result[0]['ESTIMATION'];
	}

	public function addEstimation(int $userId, int $bookId, int $estimation)
	{
		RatingTable::add(['BOOK_ID' => $bookId, 'USER_ID' => $userId, 'ESTIMATION' => $estimation]);
	}

	public function deleteEstimation(int $userId, int $bookId): void
	{
		RatingTable::delete(['USER_ID' => $userId, 'BOOK_ID' => $bookId]);
	}

	public function getEstimation(int $book)
	{
		return BookTable::query()
							 ->setSelect(['BOOK_RATING'])
							 ->where('ID', $book)
							 ->fetchAll()[0]['BOOK_RATING']
			;
	}

	public function addGenre(string $genreName){
		return GenreTable::add(['TITLE'=>$genreName]);
	}

	public function addGenreOfBook(int $genreId, int $bookId){
		$book = BookTable::getByPrimary($bookId)->fetchObject();
		$genre = GenreTable::getByPrimary($genreId)->fetchObject();

		$book->addToGenres($genre);
		$book->save();
	}

	public function deleteGenre(int $bookId, int $genreId)
	{
		$book = BookTable::getByPrimary($bookId)->fetchObject();
		$genre = GenreTable::getByPrimary($genreId)->fetchObject();

		$book->removeFromGenres($genre);
		$book->save();
	}


		public function getAllAuthors(): array
	{
		$query = AuthorTable::query()
		   ->setSelect(['*'])
		   ->fetchAll()
		;

		$result = [];

		foreach ($query as $author)
		{
			$result[$author['ID']] = $author['NAME'];
		}

		return $result;
	}

	public function addAuthor(string $authorName){
		return AuthorTable::add(['NAME'=>$authorName]);
	}

	public function addAuthorOfBook(int $authorId, int $bookId){
		$book = BookTable::getByPrimary($bookId)->fetchObject();
		$author = AuthorTable::getByPrimary($authorId)->fetchObject();

		$book->addToAuthors($author);
		$book->save();
	}

	public function deleteAuthor(int $bookId, int $authorId)
	{
		$book = BookTable::getByPrimary($bookId)->fetchObject();
		$author = AuthorTable::getByPrimary($authorId)->fetchObject();

		$book->removeFromAuthors($author);
		$book->save();
	}
}