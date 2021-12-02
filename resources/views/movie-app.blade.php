<!doctype html>
<html>
    <head>
        <title>Movie App</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row m-3">
                <div class="col-6">
                    <h3>Movie Search App</h3>
                </div>
                
                <div class="col-4">
                    <input id="search-movie-input" type="text" placeholder="Search for a Movie title" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                </div>

                <div class="col-2">
                    <button class="btn btn-info" id="search-movie-button">Search Movie</button>
                </div>
            </div>
        
            <div class="row text-center">
                <h5 class="mt-5" id="movie-message">Movie Results will be displayed here</h5>

                <table class="table table-striped table-bordered d-none" id="movie-table">
                    <tbody id="movie-table-body">
                        <tr>
                            <td style="width: 25%">Title</td>
                            <td id='title-cell' style="width:75%"></td>
                        </tr>
                        <tr>
                            <td>Overview</td>
                            <td id='overview-cell'></td>
                        </tr>
                        <tr>
                            <td>Release Date</td>
                            <td id='release-cell'></td>
                        </tr>
                        <tr>
                            <td>Runtime</td>
                            <td id='runtime-cell'></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h3>Cast</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Name</h5>
                            </td>
                            <td>
                                <h5>Character</h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script>
    $("document").ready(function(){
        $('#search-movie-button').click(function() {
            $.ajax({
                url: '/api/search-movies',
                data: {
                    'search': $('#search-movie-input').val()
                },
                type: 'GET',
                success: function(result) {
                    let movie = result.movie;
                    
                    if (!Object.keys(movie).length) {
                        $('#movie-table').addClass('d-none');
                        $('#movie-message').removeClass('d-none');
                        $('#movie-message').text('No movie matches search');
                        return;
                    }

                    $('.cast-row').remove();

                    $('#movie-table').removeClass('d-none');
                    $('#movie-message').addClass('d-none');

                    $('#title-cell').text(movie.title);
                    $('#overview-cell').text(movie.overview);
                    $('#release-cell').text(movie.release_date);
                    $('#runtime-cell').text(movie.runtime);
                    $('#runtime-cell').text(movie.runtime);

                    movie.cast.forEach(actor => {
                        $('#movie-table-body').append(
                            '<tr class="cast-row">' +
                                '<td>' + actor.name +'</td>' +
                                '<td>' + actor.character +'</td>' +
                            '</tr>'
                        );
                    });
                },
                error: function(result) {
                    alert(result.responseJSON.error);
                }
            });
        });
    });
    </script>
</html>
