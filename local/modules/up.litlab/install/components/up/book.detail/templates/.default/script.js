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