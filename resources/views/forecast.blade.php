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
    .forecast {
        background-image: url('https://images.pexels.com/photos/4529062/pexels-photo-4529062.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }
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

    .hour-forecast:last-of-type {
        margin-bottom: 58px;
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

    .view-places-btn {
        color: #FFFFFF !important;
        font-weight: 500;
        font-size: 16px;
        width: 100%;
        padding-top: 16px;
        padding-bottom: 16px;
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
        <div class="row">
            <div class="col-12 city-details text-center">
                <span class="city-name"></span>
            </div>
            <div class="col-12 text-center">
                <p class="city-temperature"></p>
                <p class="temp-details"></p>
            </div>
        </div>
        <div class="row-weather">
            <!-- js -->
        </div>
    </div>
    <div class="sticky-footer fixed-bottom">
        <button class="btn view-places-btn">View Places</button>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        const degreeIcon = '°';
        let city = (new URL(location.href)).searchParams.get('city') || 'Tokyo'
        $('.cities').val(city)
        // on load retrieve
        $.ajax({
            type: 'GET',
            // default to Tokyo onload
            url: `http://127.0.0.1:8000/api/forecast?city=${city}`,
            success: function(result) {
                let firstForecast = Object.values(result)[0];
                let forecasts = result;
                // set fields
                $('.city-name').text(`${city}, Japan`);
                $('.city-temperature').text(firstForecast.temp + degreeIcon);
                // <p class="temp-details">Partly Cloudly | H: 36°, L: 22° </p>
                let tempDetails = `${firstForecast.type} | H: ${firstForecast.temp_max}${degreeIcon}, L: ${firstForecast.temp_min}${degreeIcon}`;
                $('.temp-details').text(tempDetails);
                // forecasts
                let forecastHtml = '';
                $.each(forecasts, (i, forecast) => {
                    forecastHtml += `
                        <div class="col-12 hour-forecast">
                            <div class="row">
                                <div class="col-4">
                                    <span class="weather-time">${forecast.time}</span>
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-12 col-md-6 px-0">
                                            <span class="weather-type">${forecast.type}, </span>
                                            <span class="weather-extra-info">${forecast.description}</span>
                                            <img class="weather-image" src="${forecast.icon}" width="22" height="22">
                                        </div>
                                        <div class="col-12 col-md-6 px-0">
                                            <i class="fa-solid fa-temperature-half text-white"></i>
                                            <span class="weather-extra-info">H: ${forecast.temp_max}° </span>
                                            <span class="weather-extra-info">L: ${forecast.temp_min}° </span> 
                                            <i class="fas fa-wind text-white"></i>
                                            <span class="weather-extra-info">S: ${forecast.wind.speed}m/s </span>
                                            <span class="weather-extra-info">D: ${forecast.wind.deg}${degreeIcon}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                })
                $('.row-weather').html(forecastHtml);
            }
        });
        $('.cities').change((e) => {

            // re-call with city param
            $.ajax({
                type: 'GET',
                // default to Tokyo onload
                url: `http://127.0.0.1:8000/api/forecast?city=${e.target.value}`,
                success: function(result) {
                    let firstForecast = Object.values(result)[0];
                    let forecasts = result;
                    // set fields
                    $('.city-name').text(`${e.target.value}, Japan`);
                    $('.city-temperature').text(firstForecast.temp + degreeIcon);
                    // <p class="temp-details">Partly Cloudly | H: 36°, L: 22° </p>
                    let tempDetails = `${firstForecast.type} | H: ${firstForecast.temp_max}${degreeIcon}, L: ${firstForecast.temp_min}${degreeIcon}`;
                    $('.temp-details').text(tempDetails);
                    // forecasts
                    let forecastHtml = '';
                    $.each(forecasts, (i, forecast) => {
                        forecastHtml += `
                            <div class="col-12 hour-forecast">
                                <div class="row">
                                    <div class="col-4">
                                        <span class="weather-time">${forecast.time}</span>
                                    </div>
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col-12 col-md-6 px-0">
                                                <span class="weather-type">${forecast.type}, </span>
                                                <span class="weather-extra-info">${forecast.description}</span>
                                                <img class="weather-image" src="${forecast.icon}" width="22" height="22">
                                            </div>
                                            <div class="col-12 col-md-6 px-0">
                                                <i class="fa-solid fa-temperature-half text-white"></i>
                                                <span class="weather-extra-info">H: ${forecast.temp_max}° </span>
                                                <span class="weather-extra-info">L: ${forecast.temp_min}° </span> 
                                                <i class="fas fa-wind text-white"></i>
                                                <span class="weather-extra-info">S: ${forecast.wind.speed}m/s </span>
                                                <span class="weather-extra-info">D: ${forecast.wind.deg}${degreeIcon}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    })
                    $('.row-weather').html(forecastHtml);
                }
            });
        })

        $('.view-places-btn').click((e) => {
            console.log($('.cities').val());
            let city = $('.cities').val();
            window.location.href = `places?city=${city}`;
        })
    })
</script>
</html>
