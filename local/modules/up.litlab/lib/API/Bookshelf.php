<?php

namespace Up\Litlab\API;

use Bitrix\Main\Filter\Filter;
use Up\LitLab\Model\BookBookshelfTable;
use Up\Litlab\Model\BookshelfTable;
use Up\LitLab\Model\BookTable;
use Up\LitLab\Model\LikesTable;
use Up\LitLab\Model\TagTable;
use Up\LitLab\Model\UserTable;

class Bookshelf
{
	public function getListOfBookshelf(?int $limit = 3, int $offset = 0, array $status = ['public'], string $search = null, bool $notEmpty = false): array
	{
		$query = BookshelfTable::query()
		 	->setSelect(['ID', 'CREATOR_ID', 'TITLE', 'DESCRIPTION', 'LIKES', 'DATE_CREATED', 'DATE_UPDATED', 'STATUS', 'BOOK_COUNT'])
			->whereIn('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			->setOrder(['LIKES'=>'DESC'])
			;

		if ($search)
		{
			$query = $query->whereLike('TITLE', '%' . $search . '%');
		}

		if ($notEmpty)
		{
			$query = $query->where('BOOK_COUNT', '>', 0);
		}

		return $query->fetchAll();
	}
	public function getListOfUserBookshelf(int $userId, array $status = ['public', 'private', 'moderated'], ?int $limit = 3, int $offset = 0): array
	{
		return BookshelfTable::query()
							 ->setSelect(['*'])
							 ->setFilter(array(['CREATOR_ID'=>$userId]))
							 ->wherein('STATUS', $status)
							 ->setLimit($limit)
							 ->setOffset($offset)
							 ->fetchAll()
			;
	}
	public function getListOfSavedBookshelf(int $userId, ?int $limit = 3, int $offset = 0)
	{

		$bookshelves = UserTable::query()
					  ->setSelect(['BS_ID' => 'BOOKSHELVES.ID', 'STATUS'=>'BOOKSHELVES.STATUS', 'CREATOR_ID'=>'BOOKSHELVES.CREATOR_ID'])
					  ->setFilter(array(['ID'=>$userId]))
					  ->setLimit($limit)
					  ->setOffset($offset)
					  ->fetchAll();

		$result = [];

		foreach ($bookshelves as $bookshelf){
			if($bookshelf['STATUS'] === 'public' && $bookshelf['CREATOR_ID']!==(string)$userId)
			{
				$result[] = $this->getBookshelfById($bookshelf['BS_ID']);
			}
		}
		return $result;

	}

	public function getCount(string $search = null, array $status = ['public'], bool $notEmpty = false): int
	{
		$query = BookshelfTable::query()
			->whereIn('STATUS', $status)
			;

		if ($search)
		{
			$query = $query->whereLike('TITLE', '%' . $search . '%');
		}

		if ($notEmpty)
		{
			$query = $query->where('BOOK_COUNT', '>', 0);
		}

		return count($query->fetchAll());
	}

	public function getUserBookshelfCount($userId, array $status = ['public', 'private', 'moderated']): int
	{
		return BookshelfTable::getCount(['CREATOR_ID'=> $userId, 'STATUS'=> $status]);
	}

	public function getDetailsById(int $id, int $userId, array $status = ['public', 'private', 'moderated']): array|false
	{
		return BookshelfTable::query()
							 ->setSelect(['ID', 'CREATOR_ID', 'TITLE', 'DESCRIPTION', 'LIKES', 'DATE_CREATED', 'DATE_UPDATED', 'STATUS', 'BOOK_COUNT'])
							 ->where('ID', $id)
							 ->where('CREATOR_ID', $userId)
							 ->where('STATUS', 'in', $status)
							 ->fetch()
			;
	}

	public function getTagsInEachBookshelf(array $bookshelfIds): bool|array
	{
		$bookshelvesTag = BookshelfTable::query()
			->setSelect(['BS_ID' => 'ID', 'T_TITLE' => 'TAGS.TITLE'])
			->where('ID', 'in', $bookshelfIds)
			->fetchAll()
		;

		$result = [];

		foreach ($bookshelvesTag as $value)
		{
			if ($result[$value['BS_ID']] && count($result[$value['BS_ID']]) >= 3)
			{
				continue;
			}

			$result[$value['BS_ID']][] = $value['T_TITLE'];
		}

		return $result;
	}

	public function getTags(int $bookshelfId): array
	{
		$tags = BookshelfTable::getByPrimary($bookshelfId, ['select' => ['T_TITLE' => 'TAGS.TITLE']])
						->fetchAll()
			;

		$result = [];

		foreach ($tags as $tag){
			$result[] = $tag['T_TITLE'];
		}
		return $result;
	}

	public function getComments(int $bookshelfId): array|false
	{
		$comments = BookshelfTable::query()
								  ->setSelect(['B_COMMENT' => 'BOOKSHELF.COMMENT',
											   'B_BOOK_ID' => 'BOOKSHELF.BOOK_ID'])
								  ->where('ID', $bookshelfId)
								  // ->where('STATUS', $status)
								  ->fetchAll()
		;

		$result = [];

		foreach ($comments as $comment)
		{
			$result[$comment['B_BOOK_ID']] = $comment['B_COMMENT'];
		}

		return $result;
	}

	public function getCountOfSavedBookshelves(int $bookshelfId)
	{
		return count(BookshelfTable::query()
			->setSelect(['USERS.ID'])
			->where('ID', $bookshelfId)
			->fetchAll())
			;
	}

	public function getCountOfSavedUserBookshelves(int $userId)
	{
		$result = count(UserTable::query()
								   ->setSelect(['BOOKSHELVES.ID'])
								   ->setFilter(['ID'=>$userId, 'BOOKSHELVES.STATUS'=>'public'])
								   ->fetchAll())
			;
		return $result;
	}

	public function getCountOfSavedBookshelvesForEach(array $bookshelfId)
	{
		$counts = BookshelfTable::query()
		   ->setSelect(['ID', 'U_ID' => 'USERS.ID'])
		   ->where('ID', 'in', $bookshelfId)
		   ->fetchAll()
			;

		$result = [];

		foreach ($counts as $count)
		{
			$result[$count['ID']][] = $count['U_ID'];
		}

		return $result;
	}

	public function addBookshelf(array $params){
		return BookshelfTable::add($params);
	}

	public function autoAddedUserBookshelfs(int $userId){
		BookshelfTable::add([
								'CREATOR_ID'=>$userId,
								'TITLE' => 'Буду читать',
								'DESCRIPTION' => 'Полка, в которую вы можете добавить понравившиеся вам книги.',
								'DATE_CREATED' => new \Bitrix\Main\Type\DateTime(),
								'DATE_UPDATED' => new \Bitrix\Main\Type\DateTime(),
								'STATUS' => 'private'
							]);
		BookshelfTable::add([
								'CREATOR_ID'=>$userId,
								'TITLE' => 'Прочитано',
								'DESCRIPTION' => 'Полка, в которую вы можете добавить книги, которые уже прочитали.',
								'DATE_CREATED' => new \Bitrix\Main\Type\DateTime(),
								'DATE_UPDATED' => new \Bitrix\Main\Type\DateTime(),
								'STATUS' => 'private'
							]);
	}

	public function getBookshelfById(int $bookshelfId){
		return BookshelfTable::getById($bookshelfId)->fetch();

	}

	public function getTagByName(string $tagName){
		return TagTable::query()
			->setSelect(['*'])
			->setFilter(['TITLE'=>$tagName])
			->fetch();
	}

	public function updateBookshelf(int $bookshelfId, array $updateFields){
		return BookshelfTable::update($bookshelfId, $updateFields);

	}

	public function updateStatus(int $bookshelfId, string $status){
		return BookshelfTable::update($bookshelfId, ['STATUS'=>$status]);
	}

	public function addTag(string $tagName){
		return TagTable::add(['TITLE'=>$tagName]);
	}

	public function addTagsOfBookshelf(int $tagId, int $bookshelfId){
		$bookshelf = BookshelfTable::getByPrimary($bookshelfId)->fetchObject();
		$tag = TagTable::getByPrimary($tagId)->fetchObject();

		$bookshelf->addToTags($tag);
		$bookshelf->save();
	}

	public function deleteTagsOfBookshelf(array $tagsId, int $bookshelfId){
		$bookshelf = BookshelfTable::getByPrimary($bookshelfId)->fetchObject();
		foreach ($tagsId as $tagId)
		{
			$tag = TagTable::getByPrimary($tagId)->fetchObject();
			$bookshelf->removeFromTags($tag);
			$bookshelf->save();
		}
	}
	public function deleteTagOfBookshelf(int $tagId, int $bookshelfId){
		$bookshelf = BookshelfTable::getByPrimary($bookshelfId)->fetchObject();
			$tag = TagTable::getByPrimary($tagId)->fetchObject();
			$bookshelf->removeFromTags($tag);
			$bookshelf->save();
	}

	public function addComments(int $bookshelfId, int $bookId, string $comment)
	{
		return BookBookshelfTable::update([
									   'BOOKSHELF_ID' => $bookshelfId,
									   'BOOK_ID' => $bookId
								   ], ['COMMENT' => $comment]);

	}

	public function getStatus(int $bookshelfId): ?string
	{
		return BookshelfTable::query()
							 ->setSelect(['STATUS'])
							 ->where('ID', $bookshelfId)
							 ->fetchAll()[0]['STATUS']
			;
	}

	public function getLikesCount(int $bookshelfId): int
	{
		return BookshelfTable::query()
			->setSelect(['LIKES'])
			->where('ID', $bookshelfId)
			->fetchAll()[0]['LIKES'];
	}

	public function isLiked(int $bookshelfId, int $userId): bool
	{
		return (bool)LikesTable::query()
			->setSelect(['*'])
			->where('USER_ID', $userId)
			->where('BOOKSHELF_ID', $bookshelfId)
			->fetchAll()[0]
			;
	}

	public function addLike(int $bookshelfId, int $userId): void
	{
		LikesTable::add(['USER_ID' => $userId, 'BOOKSHELF_ID' => $bookshelfId]);
	}

	public function deleteLike(int $bookshelfId, int $userId): void
	{
		LikesTable::delete(['USER_ID' => $userId, 'BOOKSHELF_ID' => $bookshelfId]);
	}

	public function getSavesCount(int $bookshelfId): int
	{
		return BookshelfTable::query()
							 ->setSelect(['SAVES'])
							 ->where('ID', $bookshelfId)
							 ->fetchAll()[0]['SAVES'];
	}

	public function isSaved(int $bookshelfId, int $userId): bool
	{
		return (bool)BookshelfTable::query()
							   	->setSelect(['ID'])
							   ->where('USERS.ID', $userId)
							   ->where('ID', $bookshelfId)
							   ->fetchAll()[0]
			;
	}

	public function saveBookshelfToUserCollection(int $bookshelfId, int $userId): void
	{
		$bookshelf = BookshelfTable::getByPrimary($bookshelfId)->fetchObject();
		$user = UserTable::getByPrimary($userId)->fetchObject();

		$bookshelf->addToUsers($user);
		$bookshelf->save();
	}

	public function deleteBookshelfToUserCollection(int $bookshelfId, int $userId): void
	{
		$bookshelf = BookshelfTable::getByPrimary($bookshelfId)->fetchObject();
		$user = UserTable::getByPrimary($userId)->fetchObject();

		$bookshelf->removeFromUsers($user);
		$bookshelf->save();
	}

	public function deleteBookshelf(int $bookshelfId){
		return BookshelfTable::delete($bookshelfId);
	}

	public function deleteBookOfBookshelf(array $booksId, int $bookshelfId){
		foreach ($booksId as $bookId)
		{
			BookBookshelfTable::delete(['BOOKSHELF_ID'=>$bookshelfId, 'BOOK_ID'=>$bookId]);
		}
	}

	public function getBookshelfIdByTitle(int $userId, string $bookshelfTitle){
		$result = BookshelfTable::query()
							 ->setSelect(['ID'])
							 ->setFilter(array(['CREATOR_ID'=>$userId]))
							 ->wherein('TITLE', $bookshelfTitle)
							 ->fetchAll()
			;
		return $result[0]['ID'];
	}

	public function addBookToBookshelf(int $bookId, int $bookshelfId){
		return BookBookshelfTable::add([
			'BOOK_ID'=>$bookId,
			'BOOKSHELF_ID'=>$bookshelfId
		]);
	}

}