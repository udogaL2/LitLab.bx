function likeBookshelf(bookshelfId)
{
	BX.ajax.runComponentAction('up:bookshelf.detail', 'addLike', {
			mode: 'ajax',
			data:
				{
					action: 'like',
					bookshelfId: bookshelfId,
				},
		})
		.then((response) => {
			if (!response.data.result)
			{
				console.error('Error');
				return;
			}

			if (response.data.likedFlag)
			{
				const buttonLikeDiv = document.querySelector('.bookshelf-detail-buttons .shelf-likes');
				if (buttonLikeDiv)
				{
					buttonLikeDiv.innerHTML = `<input class="liked" onclick="likeBookshelf(${bookshelfId})" type="image" src="\\local\\modules\\up.litlab\\install\\templates\\litlab\\images\\icon-like-liked.png" height="25px" width="30px"> <p class="likes-amount">${response.data.likesCount}</p>`;
				}
			}
			else
			{
				const buttonLikeDiv = document.querySelector('.bookshelf-detail-buttons .shelf-likes');
				if (buttonLikeDiv)
				{
					buttonLikeDiv.innerHTML = `<input class="shelf-likes-input" onclick="likeBookshelf(${bookshelfId})" type="image" src="\\local\\modules\\up.litlab\\install\\templates\\litlab\\images\\icon-like.png" height="25px" width="30px"> <p class="likes-amount">${response.data.likesCount}</p>`;
				}
			}
		});
}

function saveBookshelf(bookshelfId)
{
	BX.ajax.runComponentAction('up:bookshelf.detail', 'save', {
			mode: 'ajax',
			data:
				{
					action: 'save',
					bookshelfId: bookshelfId,
				},
		})
		.then((response) => {
			if (!response.data.result)
			{
				console.error('Error');
				return;
			}

			if (response.data.savedFlag)
			{
				const buttonSaveDiv = document.querySelector('.bookshelf-detail-buttons .shelf-saves');
				if (buttonSaveDiv)
				{
					buttonSaveDiv.innerHTML = `<input name="unsave" class="saved" onclick="saveBookshelf(${bookshelfId})" type="image" src="\\local\\modules\\up.litlab\\install\\templates\\litlab\\images\\icon-save-saved.png" height="25px" width="20px"> <p class="save-amount">${response.data.savesCount}</p>`;
				}
			}
			else
			{
				const buttonSaveDiv = document.querySelector('.bookshelf-detail-buttons .shelf-saves');
				if (buttonSaveDiv)
				{
					buttonSaveDiv.innerHTML = `<input name="unsave" class="shelf-save-input" onclick="saveBookshelf(${bookshelfId})" type="image" src="\\local\\modules\\up.litlab\\install\\templates\\litlab\\images\\icon-save.png" height="25px" width="20px"> <p class="save-amount">${response.data.savesCount}</p>`;
				}
			}
		});
}

