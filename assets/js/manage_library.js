function addToMyLibrary(url, comicId) {
    event.preventDefault();
    const request = new Request(url, {method: 'GET'});
    fetch(request)
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            $('#' + comicId).addClass("bg-primary");
        });
}
