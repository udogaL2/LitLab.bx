
window.addEventListener('load', () => {
	let buttonAdd = document.querySelector('.button-add-tag');

	buttonAdd.onclick = function createTag() {
		var tag = document.createElement('input');

		tag.classList.add('bookshelf-edit-tag')
		tag.style.width = '130px';
		tag.value = 'Новый тег';
		tag.name = 'tags[]';
		document.querySelector('.shelf-card-tags-list').appendChild(tag);
		return false;
	};
})

// function addTag(bookshelfId)
// {
// 	var tag = document.createElement('input');
// 	tag.classList.add('bookshelf-edit-tag')
// 	tag.style.width = '130px';
// 	tag.style.marginBottom = '10px';
// 	tag.value = 'Новый тег';
// 	tag.name = 'tags[]';
// 	BX.ajax.runComponentAction('up:bookshelf.edit', 'addTag', {
// 			mode: 'ajax',
// 			data: {
// 				bookshelfId: bookshelfId,
// 				// tagId: tagId,
// 				tag: tag.textContent,
// 				action: 'addTag'
// 			},
// 		})
// 		.then(function (response) {
// 			console.log(response);
// 			// let tag = document.getElementById(tagId);
// 			// tag.remove();
// 			// window.location = '/edit/bookshelf/' + bookshelfId + '/';
// 			if (!response.data.result)
// 			{
// 				console.log('Error');
// 				return;
// 			}
//
// 			if(response.data.action === 'addTag'){
// 				// tag.classList.add('bookshelf-edit-tag')
// 				// tag.style.width = '130px';
// 				// tag.style.marginBottom = '10px';
// 				// tag.value = 'Новый тег';
// 				// tag.name = 'tags[]';
// 				document.querySelector('.shelf-card-tags-list').appendChild(tag);
// 			}
// 		});
// }

function removeBook(id, bookshelfId)
{
	const shouldRemove = confirm("Вы точно хотите удалить данную книгу?");
	if (!shouldRemove)
	{
		return;
	}
	BX.ajax.runComponentAction('up:bookshelf.edit', 'deleteBook', {
		mode: 'ajax',
		data: {
			bookId: id,
			bookshelfId: bookshelfId,
			action: 'delete'
		},

	})
	.then(function (response) {
		console.log(response);
		if (!response.data.result)
		{
			console.log('Error');
			return;
		}
	});

}

function removeBookshelf(bookshelfId)
{
	const shouldRemove = confirm("Вы точно хотите удалить данную полку?");
	if (!shouldRemove)
	{
		return;
	}
	BX.ajax.runComponentAction('up:bookshelf.edit', 'deleteBookshelf', {
			mode: 'ajax',
			data: {
				bookshelfId: bookshelfId,
				action: 'delete'
			},

		})
		.then(function (response) {
			if (!response.data.result)
			{
				console.log('Error');
				return;
			}
			window.location = "/user/" + userId + "/";
		});
}

function removeTag(tagId, bookshelfId){
	BX.ajax.runComponentAction('up:bookshelf.edit', 'deleteTag', {
			mode: 'ajax',
			data: {
				bookshelfId: bookshelfId,
				tagId: tagId,
				action: 'delete'
			},
		method: 'POST'
		})
		.then(function (response) {
			console.log(response);
			// let tag = document.getElementById(tagId);
			// tag.remove();
			// window.location = '/edit/bookshelf/' + bookshelfId + '/';
			if (!response.data.result)
			{
				console.log('Error');
				return;
			}

			if(response.data.action === 'delete'){
				const tag = document.getElementById(tagId);
				tag.remove();
				// window.location = '/edit/bookshelf/' + bookshelfId + '/';
			}
		});
}

