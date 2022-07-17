const body = document.querySelector('body');
const slides = document.getElementsByClassName('swiper-slide');
const close_btn = document.querySelector('.close-btn');
const popup = document.querySelector('.popup');
const main_popup = document.querySelector('.main-popup');
const stationId = document.querySelector('.station-id');
const stationTitle = document.querySelector('.station-title');
const darkModBtn = document.querySelector('.dark-mod');

//Use to compare the time of a measure
const compareTime = (a, b) => {
    const objA = a.x.split(' ');
    const objB = b.x.split(' ');
    if(objA[0] < objB[0]) {
        return -1;
    }
    if(objA[0] > objB[0]) {
        return 1;
    }
    if(objA[0] == objB[0]) {
        if(objA[1] < objB[1]) {
            return -1;
        }
        if(objA[1] > objB[1]) {
            return 1;
        } else {
            return 0;
        }
    }
}

//Transform json in chart data format
const jsonToChartData = (json) => {
    let data = [];
    //console.log(json);
    for(const object in json) {
        //console.log(json[object]);
        const station = json[object];
        data.push({x: station.date_mesure_temp+" "+station.heure_mesure_temp, y: parseFloat(station.resultat.toPrecision(3))});
    }
    data.sort(compareTime);
    return data;
}

//Recover data and display chart
[...slides].forEach(slide => {
    slide.addEventListener('click', () => {
        popup.style.display = 'flex';
        popup.scrollIntoView({behavior: "smooth"});
	    main_popup.style.cssText = 'animation:slide-in .5s ease; animation-fill-mode: forwards;';
        const chart = document.querySelector('.chart');
        chart.style.display = "none";
        chart.innerHTML = "<canvas id=\"line-chart\"></canvas>"
        //stationId.innerHTML = slide.id;
        //console.log(slide.id);
        $.ajax({
            url: 'https://hubeau.eaufrance.fr/api/v1/temperature/chronique?code_station=' + slide.id + '&size=100&sort=desc&pretty',
            beforeSend: () => {
                $('.wait').show();
                $('#line-chart').hide();
            },
            complete: (data) => {
                stationTitle.innerHTML = data.responseJSON.data[0].libelle_station;
                //console.log(data.responseJSON.data);
                const ctx = document.querySelector('#line-chart').getContext('2d');
                const config = {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            data: jsonToChartData(data.responseJSON.data),
                            label: "Temperature (°C)",
                            borderColor: "#3e95cd",
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                type: 'time',
                                distribution: 'linear',
                            }],
                            title: {
                                display: false,
                            }
                        }
                    }
                };
                new Chart(ctx, config);
                $('.wait').hide();
                $('.chart').show();
                $('#line-chart').show();
            }
        });
    })
});

close_btn.addEventListener('click', () => {
    const navBar = document.querySelector('.nav-bar');
    navBar.scrollIntoView({behavior: "smooth"});
	main_popup.style.cssText = 'animation:slide-out .5s ease; animation-fill-mode: forwards;';
	setTimeout(() => {
		popup.style.display = 'none';
	}, 500);
});

darkModBtn.addEventListener('click', () => {
    close_btn.classList.remove('close-btn-dark', 'close-btn-light');
    if(popup.classList.toggle('active')) {
        body.style.background = "white";
        popup.style.background = "white";
        main_popup.style.background = "white";
        popup.style.color = "#222";
        close_btn.style.border = ".6px solid white";
        close_btn.classList.add('close-btn-light');
    } else {
        body.style.background = "#111";
        popup.style.background = "#222";
        main_popup.style.background = "#222";
        popup.style.color = "white";
        close_btn.style.border = ".6px solid #222";
        close_btn.classList.add('close-btn-dark');
    }
});