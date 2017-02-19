$(document).ready(function(){
    $("#thematique-select").val(1);

    var icon = "public/images/marker-icon.png";

    $("#slider-range").slider({
        range: true,
        min: - 3000,
        max: new Date().getFullYear(),
        values: [ 1000, new Date().getFullYear() ],
        animate: "fast",
        slide: function(event, ui){
            $("#input-from").val(ui.values[0]);
            $("#input-to").val(ui.values[1]);
        }
    });

    $("#input-from").val($("#slider-range").slider("values", 0));
    $("#input-to").val($("#slider-range").slider("values", 1));

    $("#input-from").keyup(function(e){
        if(!$("#input-from").val().match(/^-?[0-9]\d*(\.\d+)?$/)) {
            if($("#input-from").val().charAt(0) == "-"){
                var addMinus = "-";
            }
            else{
                var addMinus = "";
            }
            $("#input-from").val(addMinus + $("#input-from").val().replaceAll("-", ""));

        }
        else {

            if ($(this).val() < -3000) {
                $(this).val(-3000);
            }

            if (parseInt($(this).val()) > parseInt($("#input-to").val())) {
                $(this).val($("#input-to").val());
            }

            $("#slider-range").slider("values", 0, $(this).val());
        }
    });

    $("#input-to").keyup(function(e){

        if(!$("#input-to").val().match(/^-?[0-9]\d*(\.\d+)?$/)) {
            if($("#input-to").val().charAt(0) == "-"){
                var addMinus = "-";
            }
            else{
                var addMinus = "";
            }
            $("#input-to").val(addMinus + $("#input-to").val().replaceAll("-", ""));

        }
        else {

            var thisYear = new Date().getFullYear();
            if ($(this).val() > thisYear) {
                $(this).val(thisYear);
            }

            if (parseInt($(this).val()) < parseInt($("#input-from").val())) {
                $(this).val($("#input-from").val());
            }

            $("#slider-range").slider("values", 1, $(this).val());
        }
    });

    $(".div-submit-search button").click(function(e){
        var data = gatherData();
        $(".showbox").slideDown("fast", "linear");
        $(".circular").slideDown("fast", "linear");
        sendDataAjax(data);
    });

    if(window.location.pathname == "/" || window.location.pathname == "/app_dev.php") {
        setTimeout(function () {
            oms = new OverlappingMarkerSpiderfier(map, {"keepSpiderfied": true});

            oms.addListener('click', function (marker, event) {
                infowindow.setContent(contentString[marker.id]);
                infowindow.open(map, marker);
            });
        }, 1000);

        setTimeout(function () {
            var data = gatherData();

            sendDataAjax(data);
        }, 1500);
    }

    $(document).keypress(function(e) {
        if(e.which == 13 && $("#person-name").is(":focus")) {
            $(".div-submit-search button").trigger("click");
        }
    });

    var options = [];

    $('.dropdown-menu').click(function(event){
        $(event.target).blur();
        return false;
    });

    $('.dropdown-menu label').click(function(event){
        var $target = $(event.currentTarget),
            val = $target.attr('data-value'),
            $inp = $target.find('input'),
            idx;

        if ((idx = options.indexOf(val)) > -1) {
            options.splice(idx, 1);
            setTimeout(function() { $inp.prop('checked', true) }, 0);
        } else {
            options.push(val);
            setTimeout(function() { $inp.prop('checked', false) }, 0);
        }

        $(event.target).blur();
        return false;
    });

    $(".dropdown-menu-thema > li > span").click(function(){

        $(".dropdown-submenu").each(function(){
            $(this).slideUp();
        });

        if($(this).parent().next().css("display") == "none"){
            $(this).parent().next().slideDown();
        }
    });

    $(".dropdown-menu-thema > li > label > input").click(function(){

        checked = $(this).is(":checked");

        $(this).parent().parent().next().find("ul > li > label > input").each(function(){
            $(this).prop("checked", checked).triggerHandler('click');
        });
    });
});

function sendDataAjax(data) {
    $.ajax({
        url: 'get-events',
        type: 'POST',
        cache: false,
        data: data,
        success: function (data) {
            //console.log(data["events"]);

            contentString = [];
            setMapOnAll(null);
            if (typeof markerCluster !== 'undefined') {
                markerCluster.clearMarkers();
            }
            marker = [];
            infowindow = [];

            for (var i = 0; i < data["events"].length; i++) {
                contentString[i] = "<div class='marker'>\n\
                                                <a target='_blank' href='" + data["events"][i]["personne"]["wiki"] + "'>\n\
                                                    <img width='100px' src='/public/personne/" + data["events"][i]["personne"]["ref"] + ".jpg'>\n\
                                                </a>\n\
                                                <div>\n\
                                                    <h4>\n\
                                                        <a target='_blank' href='" + data["events"][i]["personne"]["wiki"] + "'>\n\
                                                            " + data["events"][i]["personne"]["nom"] + "\n\
                                                            <img class='small-wiki' src='/public/images/wiki_small.png'>\n\
                                                        </a>\n\
                                                    </h4>\n\
                                                    <div>Événement : " + data["events"][i]["intitule"] + "</div>\n\
                                                    <div>Lieu : " + data["events"][i]["place"]["nom"] + "</div>\n\
                                                    <div>Année : " + data["events"][i]["year"] + "</div>\n\
                                                </div>\n\
                                            </div>";

                infowindow = new google.maps.InfoWindow({
                    content: contentString[i]
                });

                title = data["events"][i]["personne"]["nom"];

                latlng = new google.maps.LatLng(parseFloat(data["events"][i]["place"]["lat"]), parseFloat(data["events"][i]["place"]["lng"]));

                marker[i] = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: title,
                    icon: icon
                });
                marker[i].id = i;

                var thisMarker = marker[i];
                oms.addMarker(marker[i]);
            }

            var clusterStyles = [
                {
                    textColor: 'white',
                    url: '/markerclusterer/images/m1.png',
                    height: 40,
                    width: 42
                },
                {
                    textColor: '#111',
                    url: '/markerclusterer/images/m2.png',
                    height: 50,
                    width: 50
                },
                {
                    textColor: 'white',
                    url: '/markerclusterer/images/m3.png',
                    height: 60,
                    width: 60
                }
            ];

            var options = {
                imagePath: 'markerclusterer/images/m',
                textColor: "#fff",
                styles: clusterStyles
            };

            $(".showbox").slideUp("fast", "linear");
            $(".circular").slideUp("fast", "linear");
        }
    });
}

function gatherData(){
    var data = {"gender": [], "eventType": [], "thematique": [], "from": "1000", "to": "2000", "nom": ""};

    if($(".div-gender-filter #female").is(":checked")){
        data["gender"].push(2);
    }
    if($(".div-gender-filter #male").is(":checked")){
        data["gender"].push(1);
    }

    $(".eventType").each(function(e){
        var value = $(this).val();
        if($(this).is(":checked")){
            data["eventType"].push(parseInt(value));
        }
    });

    $(".thematique-input").each(function(e){
        var value = $(this).val();
        if($(this).is(":checked")){
            data["thematique"].push(parseInt(value));
        }
    });

    data["from"] = $("#input-from").val();
    data["to"] = $("#input-to").val();
    data["nom"] = $("#person-name").val();

    return data;
}

String.prototype.replaceAll = function(str1, str2, ignore){
    return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
}

function setMapOnAll(map) {
    for (var i = 0; i < marker.length; i++) {
        marker[i].setMap(map);
    }
}

function clearMarkers() {
    setMapOnAll(null);
}