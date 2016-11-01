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

    /*$(".suggestion-close-button").click(function(){
        if($(".arrow-suggestion").hasClass("fa-angle-double-right")){
            $(".arrow-suggestion").addClass("fa-angle-double-left").removeClass("fa-angle-double-right");
            $(".suggestion-form").addClass("suggestion-form-closed");
        }
        else{
            $(".arrow-suggestion").addClass("fa-angle-double-right").removeClass("fa-angle-double-left");
            $(".suggestion-form").removeClass("suggestion-form-closed");
        }
    });*/

    /*$(".filters-close-button").click(function(){
        if($(".arrow-filters").hasClass("fa-angle-double-left")){
            $(".arrow-filters").addClass("fa-angle-double-right").removeClass("fa-angle-double-left");
            $(".filters-div").addClass("filters-div-closed");
        }
        else{
            $(".arrow-filters").addClass("fa-angle-double-left").removeClass("fa-angle-double-right");
            $(".filters-div").removeClass("filters-div-closed");
        }
    });*/

    $(".div-submit-search button").click(function(e){
        var data = gatherData();
        console.log(data);
        //$(".circular").attr("class", "circular circular-open");
        //$(".showbox").attr("class", "showbox showbox-open");
        $(".showbox").slideDown("fast", "linear");
        $(".circular").slideDown("fast", "linear");
        sendDataAjax(data);
    });

    /*$(".filters-close-button").trigger("click");

    if (typeof error == 'undefined' && typeof success == 'undefined') {
        $(".suggestion-close-button").trigger("click");
    }*/

    var data = gatherData();
    //$(".circular").attr("class", "circular circular-open");
    //$(".showbox").attr("class", "showbox showbox-open");
    $(".showbox").slideDown("fast", "linear");
    $(".circular").slideDown("fast", "linear");
    sendDataAjax(data);
});

function sendDataAjax(data) {
    $.ajax({
        url: 'get-events',
        type: 'POST',
        cache: false,
        data: data,
        complete: function () {
            //$(".circular").attr("class", "circular");
            //$(".showbox").attr("class", "showbox");
            $(".showbox").slideUp("fast", "linear");
            $(".circular").slideUp("fast", "linear");
        },
        success: function (data) {
            //console.log(data["events"]);

            var contentString = [];
            setMapOnAll(null);
            if (typeof markerCluster !== 'undefined') {
                markerCluster.clearMarkers();
            }
            marker = [];
            for (var i = 0; i < data["events"].length; i++) {
                contentString[i] = "<div class='marker'>\n\
                                                <h4>\n\
                                                    <a target='_blank' href='" + data["events"][i]["personne"]["wiki"] + "'>\n\
                                                        " + data["events"][i]["personne"]["nom"] + "\n\
                                                        <img class='small-wiki' src='/public/images/wiki_small.png'>\n\
                                                    </a>\n\
                                                </h4>\n\
                                                <div>Événement : " + data["events"][i]["intitule"] + "</div>\n\
                                                <div>Lieu : " + data["events"][i]["place"]["nom"] + "</div>\n\
                                                <div>Année : " + data["events"][i]["year"] + "</div>\n\
                                                <a target='_blank' href='" + data["events"][i]["personne"]["wiki"] + "'>\n\
                                                    <img width='100px' src='/public/personne/" + data["events"][i]["personne"]["ref"] + ".jpg'>\n\
                                                </a>\n\
                                            </div>";

                var infowindow = new google.maps.InfoWindow({
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

                marker[i].addListener('click', function () {
                    infowindow.setContent(contentString[$(this)[0].id]);
                    infowindow.open(map, $(this)[0]);
                });
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

            markerCluster = new MarkerClusterer(map, marker, options);
        }
    });
}

function gatherData(){
    var data = {"gender": [], "eventType": [], "from": "1000", "to": "2000", "nom": ""};

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
// Sets the map on all markers in the array.


// Removes the markers from the map, but keeps them in the array.
/*function sendDataAjax(){
    var years_splitted = $("#amount").val().split("   ");

    var filters = {"event":{"naissance" : "false", "mort" : "false"}, "gender":{"male" : "false", "female" : "false"}};

    if($(".filter-activator[name=male]").is(':checked')){
        filters["gender"]["male"] = "true";
    }
    else{
        filters["gender"]["male"] = "false";
    }

    if($(".filter-activator[name=female]").is(':checked')){
        filters["gender"]["female"] = "true";
    }
    else{
        filters["gender"]["female"] = "false";
    }

    filters["thematique"] = $("#thematique-select").val();

    var event_filter = [];

    $(".filter-activator.event").each(function(){
        if($(this).is(':checked')){
            event_filter.push(parseInt($(this).val()));
        }
    });

    filters["event"] = event_filter;

    $(".circular").attr("class", "circular circular-open");
    $(".showbox").attr("class", "showbox showbox-open");

    $.ajax({
        url: 'get-persons-ajax/' + years_splitted[ 0 ] + '/' + years_splitted[ 1 ],
        type: 'POST',
        cache: false,
        data: filters,
        complete: function(){
            $(".circular").attr("class", "circular");
            $(".showbox").attr("class", "showbox");
        },
        success: function(data){
            var contentString = [];
            setMapOnAll(null);
            markerCluster.clearMarkers();
            marker = [];
            for (var i = 0; i < data["events"].length; i++){
                contentString[i] = "<div class='marker'>\n\
                                                    <h4>\n\
                                                        <a target='_blank' href='" + data["events"][i]["personne"]["wiki"] + "'>\n\
                                                            " + data["events"][i]["personne"]["nom"] + "\n\
                                                            <img class='small-wiki' src='public/images/wiki_small.png'>\n\
                                                        </a>\n\
                                                    </h4>\n\
                                                    <div>Événement : " + data["events"][i]["intitule"] + "</div>\n\
                                                    <div>Lieu : " + data["events"][i]["place"]["nom"] + "</div>\n\
                                                    <div>Année : " + data["events"][i]["year"] + "</div>\n\
                                                    <a target='_blank' href='" + data["events"][i]["personne"]["wiki"] + "'>\n\
                                                        <img width='100px' src='public/personne/" + data["events"][i]["personne"]["ref"] + ".jpg'>\n\
                                                    </a>\n\
                                                </div>";

                var infowindow = new google.maps.InfoWindow({
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

                marker[i].addListener('click', function() {
                    infowindow.setContent(contentString[$(this)[0].id]);
                    infowindow.open(map, $(this)[0]);
                });
            }

            var clusterStyles = [
                {
                    textColor: 'white',
                    url: 'markerclusterer/images/m1.png',
                    height: 40,
                    width: 42
                },
                {
                    textColor: '#111',
                    url: 'markerclusterer/images/m2.png',
                    height: 50,
                    width: 50
                },
                {
                    textColor: 'white',
                    url: 'markerclusterer/images/m3.png',
                    height: 60,
                    width: 60
                }
            ];

            var options = {
                imagePath: 'markerclusterer/images/m',
                textColor: "#fff",
                styles: clusterStyles
            };

            markerCluster = new MarkerClusterer(map, marker, options);
        }
    });
}*/



$(document).ready(function(){

    $(".filter-activator").click(function(){
        sendDataAjax();
    });

});
