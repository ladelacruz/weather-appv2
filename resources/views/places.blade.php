<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Forecast</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Styles -->
    </head>
    <style>
    .city-select-wrapper {
        padding-top: 16px;
        padding-bottom: 16px;
        padding-left: 46px;
        padding-right: 46px;
        background-color: #3c415b;
    }

    .city-details {
        padding-top: 50px
    }

    .city-name {
        color: #FFFFFF;
        font-weight: 400;
        font-size: 50px;
        margin-bottom:0px;
    }
    .city-temperature {
        color: #FFFFFF;
        font-weight: 400;
        font-size: 72px;
        line-height: 50px;
    }

    .temp-details {
        color:#FFFFFF;
        font-weight: 500;
        font-size: 18px;
    }

    .weather {
        padding-top: 72px;
        padding-left: 16px;
        padding-right: 16px;
    }

    .hour-forecast {
        padding-left: 16px;
        padding-right: 16px;
        padding-top: 8px;
        padding-bottom: 8px;
        background-color: rgb(99, 154, 192, 0.6);
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .weather-time {
        color: #FFFFFF;
        font-size: 32px;
    }

    .weather-type {
        color: #FFFFFF;
        font-size: 22px;
    }

    .weather-extra-info {
        color: #FFFFFF;
        font-size: 16px;
    }

    .weather-image {
        vertical-align: top !important;
    }

    .sticky-footer {
        background-color: #3c415b;
        width: 100%;
        text-align: center;
    }

    .view-forecast-btn {
        color: #FFFFFF !important;
        font-weight: 500;
        font-size: 16px;
        width: 100%;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .place-image-container {
        position: relative;
        display: inline-block;
        padding-right: 0px !important;
        padding-left: 0px !important;
    }

    .place-detail-container {
        position: absolute;
        bottom: 10%;
        padding-left: 16px;
        color: #FFFFFF;
        background-color: rgb(60, 65, 91, 0.5);
        width: 100%;
    }

    .place-name {
        font-weight: 500;
        font-size: 20px;
    }

    .place-info {
        font-weight: 400;
        font-size: 16px;
    }
</style>
<body>
    <div class="city-select-wrapper">
        <select name="cities"  class="form-select cities">
            <option value="Tokyo">Tokyo</option>
            <option value="Yokohama">Yokohama</option>
            <option value="Kyoto">Kyoto</option>
            <option value="Osaka">Osaka</option>
            <option value="Sapporo">Sapporo</option>
            <option value="Nagoya">Nagoya</option>
        </select>
    </div>
    <div class="container-fluid forecast">
        <div class="row places-container">
            <!-- js -->
        </div>
    </div>
    <div class="sticky-footer fixed-bottom">
        <button class="btn view-forecast-btn">View Forecast</button>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        const degreeIcon = '°';
        let city = (new URL(location.href)).searchParams.get('city')
        $('.cities').val(city)
        // on load retrieve
        $.ajax({
            type: 'GET',
            // default to Tokyo onload
            url: `http://127.0.0.1:8000/api/places?city=${city}`,
            success: function(result) {
                let places = result;
                // forecasts
                let placeHtml = '';
                $.each(places, (i, place) => {
                    let tags = place.categories.other_count > 0 ? `${place.categories.name} and ${place.categories.other_count} other` : `${place.categories.name}`;
                    placeHtml += `
                        <div class="col-12 col-md-6 place-image-container">
                            <img width="100%" src="${place.image}">
                            <div class="place-detail-container">
                                <p class="place-name">${place.name}</p>
                                <p class="place-info"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> ${place.formatted_address}</p>
                                <p class="place-info"><i class="fas fa-tags"></i> ${tags}</p>
                            </div>
                        </div>
                    `;
                })
                $('.places-container').html(placeHtml);
            }
        });
        $('.cities').change((e) => {
            // re-call with city param
            $.ajax({
                type: 'GET',
                // default to Tokyo onload
                url: `http://127.0.0.1:8000/api/places?city=${e.target.value}`,
                success: function(result) {
                    let places = result;
                    // forecasts
                    let placeHtml = '';
                    $.each(places, (i, place) => {
                        let tags = place.categories.other_count > 0 ? `${place.categories.name} and ${place.categories.other_count} other` : `${place.categories.name}`;
                        placeHtml += `
                            <div class="col-12 col-md-6 place-image-container">
                                <img width="100%" src="${place.image}">
                                <div class="place-detail-container">
                                    <p class="place-name">${place.name}</p>
                                    <p class="place-info"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> ${place.formatted_address}</p>
                                    <p class="place-info"><i class="fas fa-tags"></i> ${tags}</p>
                                </div>
                            </div>
                        `;
                    })
                    $('.places-container').html(placeHtml);
                }
            });
        })

        $('.view-forecast-btn').click((e) => {
            console.log($('.cities').val());
            let city = $('.cities').val();
            window.location.href = `forecast?city=${city}`;
        })
    })
</script>
</html>
