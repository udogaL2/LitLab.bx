

window.addEventListener('load', () => {
	let parentDiv = document.querySelector('.bookshelf-create-description .two');

	let buttonAdd = document.querySelector('.button-add-tag');

	buttonAdd.onclick = function createTag() {
		var tag = document.createElement('input');

		tag.classList.add('bookshelf-edit-tag')
		tag.style.width = '130px';
		tag.style.marginBottom = '10px';
		tag.value = 'Новый тег';
		tag.name = 'tags[]';
		document.querySelector('.shelf-card-tags-list').appendChild(tag);
		return false;
	};

})