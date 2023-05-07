function makeSearch()
{
	let form = document.getElementById('searchForm');
	if (!form)
	{
		return;
	}

	form.action = 'javascript:void(0);';

	let searchInput = document.getElementById('searchInput');
	if (!searchInput)
	{
		return;
	}

	if (searchInput.value && searchInput.value.length < 250)
	{
		let newUrl = new URL(document.location);
		newUrl.searchParams.set('search', searchInput.value);
		window.location.href = newUrl;
	}
}