function makeEstimation(bookId, estimation)
{
	BX.ajax.runComponentAction('up:book.detail', 'addRating', {
			mode: 'ajax',
			data:
				{
					action: 'estimation',
					bookId: bookId,
					estimation: estimation
				},
		})
		.then((response) => {
			if (!response.data.result)
			{
				console.error('Error');
				return;
			}

			const ratingDiv = document.querySelector('.book-detail-card-description .r-boxes');

			if (response.data.estimationFlag)
			{
				if (ratingDiv)
				{
					let rating = ``;
					for (let i = 5; i > estimation; i--)
					{
						rating += `<button onclick="makeEstimation(${bookId}, ${i})" class="r-box-lu-${i}">`;
					}
					for (let i = estimation; i > 0; i--)
					{
						rating += `<button onclick="makeEstimation(${bookId}, ${i})" class="r-box-lu-${i}-active">`;
					}
					ratingDiv.innerHTML = rating;
				}
			}
			else
			{
				if (ratingDiv)
				{
					let rating = ``;
					for (let i = 5; i > 0; i--)
					{
						rating += `<button onclick="makeEstimation(${bookId}, ${i})" class="r-box-lu-${i}">`;
					}
					ratingDiv.innerHTML = rating;
				}
			}
			const ratingNumber = document.querySelector('.book-detail-card-description .rating-num');
			if (ratingNumber)
			{
				ratingNumber.innerHTML = response.data.averageEstimation;
			}
			else{
				console.log(123);
			}
		});
}

function addBookToWillReadBookshelf(bookId, bookshelfId)
{
	BX.ajax.runComponentAction('up:book.detail', 'addBookToUserBookshelf', {
			mode: 'ajax',
			data: {
				bookId: bookId,
				bookshelfId: bookshelfId,
				action: 'add',
			},
		})
		.then((response)=> {
			console.log(response);
			if (!response.data.result)
			{
				console.log('Error');
				return;
			}

			if (!response.data.addedFlag)
			{
				const buttonWillRead = document.getElementById('button-add-willread');
				if(buttonWillRead)
				{
					buttonWillRead.style.backgroundColor="#ededed";
				}
			}
			else
			{
				const buttonWillRead = document.getElementById('button-add-willread');
				if(buttonWillRead)
				{
					buttonWillRead.style.backgroundColor="#84c25c";
				}
			}
		});
}


function addBookToReadBookshelf(bookId, bookshelfId)
{
	BX.ajax.runComponentAction('up:book.detail', 'addBookToUserBookshelf', {
			mode: 'ajax',
			data: {
				bookId: bookId,
				bookshelfId: bookshelfId,
				action: 'add',
			},
		})
		.then((response)=> {
			if (!response.data.result)
			{
				console.log('Error');
				return;
			}

			if (!response.data.addedFlag)
			{
				const buttonRead = document.getElementById('button-add-read');
				if(buttonRead)
				{
					buttonRead.style.backgroundColor="#ededed";
				}
			}
			else
			{
				const buttonRead = document.getElementById('button-add-read');
				if(buttonRead)
				{
					buttonRead.style.backgroundColor="#84c25c";
				}
			}
		})
}

function addBook(bookId, bookshelfId){

	BX.ajax.runComponentAction('up:book.detail', 'addBookToUserBookshelf', {
			mode: 'ajax', //это означает, что мы хотим вызывать действие из ajax.php
			data: {
				bookId: bookId,
				bookshelfId: bookshelfId,
				action: 'add',
				//данные будут автоматически замаплены на параметры метода
			},
		})
		.then((response)=> {
			console.log(response);
			if (!response.data.result)
			{
				console.log('Error');
				return;
			}

			if (!response.data.addedFlag)
			{
				const button = document.getElementById(bookshelfId);
				if(button)
				{
					button.style.backgroundColor="#ededed";
					button.textContent='Добавить в полку';
				}
			}
			else
			{
				const button = document.getElementById(bookshelfId);
				if(button)
				{
					button.style.backgroundColor="#84c25c";
					button.textContent='Добавлено';
				}
			}
		})
}

window.addEventListener('load', () => {
	let buttonClose = document.querySelector('.list-user-bookshelf input'),
		element = document.getElementById('listUserBookshelves'),
		buttonOpen = document.querySelector('.popup');

	buttonClose.onclick = function() {
		element.style.display = 'none';
	}

	buttonOpen.onclick = function() {
		element.style.display = 'flex';
	}

});