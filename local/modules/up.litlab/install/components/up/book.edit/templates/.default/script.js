window.addEventListener('load', () => {
	let buttonGenreAdd = document.querySelector('.button-add-genre');

	buttonGenreAdd.onclick = function createField() {
		var tag = document.createElement('input');

		tag.classList.add('book-edit-genre');
		tag.style.width = '130px';
		tag.value = 'Новый жанр';
		tag.name = 'genre[]';
		document.querySelector('.book-genre-list').appendChild(tag);
		return false;
	};

	let buttonGenreRemove = document.querySelector('.button-remove-genre');

	buttonGenreRemove.onclick = function deleteField() {
		let elems = document.querySelectorAll('.book-edit-genre');
		if (elems[0])
		{
			let el = elems[elems.length - 1];
			if (el.classList[0] === 'book-edit-genre')
			{
				el.parentNode.removeChild(el);
			}
		}
		return false;
	};

	let buttonAuthorAdd = document.querySelector('.button-add-author');

	buttonAuthorAdd.onclick = function createField() {
		var tag = document.createElement('input');

		tag.classList.add('book-edit-author');
		tag.style.width = '130px';
		tag.value = 'Новый автор';
		tag.name = 'author[]';
		document.querySelector('.book-author-list').appendChild(tag);
		return false;
	};

	let buttonAuthorRemove = document.querySelector('.button-remove-author');

	buttonAuthorRemove.onclick = function deleteField() {
		let elems = document.querySelectorAll('.book-edit-author');
		if (elems[0])
		{
			let el = elems[elems.length - 1];
			if (el.classList[0] === 'book-edit-author')
			{
				el.parentNode.removeChild(el);
			}
		}
		return false;
	};
});

function removeGenre(bookId, genreId)
{
	const shouldRemove = confirm('Вы точно хотите удалить данный жанр? Действие будет применено немедленно.');

	if (!shouldRemove)
	{
		return;
	}

	BX.ajax.runComponentAction('up:book.edit', 'removeGenre', {
			mode: 'ajax',
			data: {
				bookId: bookId,
				genreId: genreId,
				action: 'delete',
			},
			method: 'POST',
		})
		.then(function(response) {

			if (!response.data.result)
			{
				console.log('Error');
				return;
			}
			const div = document.getElementById(`genre_${genreId}`);

			if (div)
			{
				div.remove();
			}
		});
}

function removeAuthor(bookId, authorId)
{
	const shouldRemove = confirm('Вы точно хотите удалить данного автора? Действие будет применено немедленно.');

	if (!shouldRemove)
	{
		return;
	}

	BX.ajax.runComponentAction('up:book.edit', 'removeAuthor', {
			mode: 'ajax',
			data: {
				bookId: bookId,
				authorId: authorId,
				action: 'delete',
			},
			method: 'POST',
		})
		.then(function(response) {

			if (!response.data.result)
			{
				console.log('Error');
				return;
			}
			const div = document.getElementById(`author_${authorId}`);

			if (div)
			{
				div.remove();
			}
		});
}

function removeBook(bookId)
{
	const shouldRemove = confirm('Вы точно хотите удалить данную книгу? Действие будет применено немедленно.');

	if (!shouldRemove)
	{
		return;
	}

	BX.ajax.runComponentAction('up:book.edit', 'removeBook', {
			mode: 'ajax',
			data: {
				bookId: bookId,
				action: 'delete',
			},
			method: 'POST',
		})
		.then(function(response) {

			if (!response.data.result)
			{
				console.log('Error');
				return;
			}
			window.location.replace(/books/);
		});
}
