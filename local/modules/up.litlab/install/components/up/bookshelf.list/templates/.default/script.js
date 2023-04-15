window.addEventListener('load', () => {
	const buttonLike = document.querySelector('.shelf-card-rating .shelf-likes-input')
	const buttonLiked = document.querySelector('.shelf-card-rating .liked')
	const buttonSave = document.querySelector('.shelf-card-rating .shelf-save-input')
	const buttonSaved = document.querySelector('.shelf-card-rating .saved')

	let likesAmount = document.querySelector('.shelf-card-rating .likes-amount')
	let saveAmount = document.querySelector('.shelf-card-rating .save-amount')
	let countLikes = 0
	let countSave = 0

	buttonLike.onclick = function(){
		countLikes++
		if (countLikes % 2 === 1)
		{
			likesAmount.textContent = Number(likesAmount.textContent) + 1
			buttonLike.type = 'hidden'
			buttonLiked.type = 'image'
		}
	}

	buttonLiked.onclick = function(){
		countLikes--
		if (countLikes % 2 === 0){
			likesAmount.textContent = Number(likesAmount.textContent) - 1
			buttonLike.type = 'image'
			buttonLiked.type = 'hidden'
		}
	}

	buttonSave.onclick = function(){
		countSave++
		if (countSave % 2 === 1)
		{
			saveAmount.textContent = Number(saveAmount.textContent) + 1
			buttonSave.type = 'hidden'
			buttonSaved.type = 'image'
		}
	}

	buttonSaved.onclick = function(){
		countSave--
		if (countSave % 2 === 0){
			saveAmount.textContent = Number(saveAmount.textContent) - 1
			buttonSave.type = 'image'
			buttonSaved.type = 'hidden'
		}
	}
})