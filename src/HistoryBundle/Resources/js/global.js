$(document).ready(function(){
    $("#thematique-select").val(1);

    var icon = "/public/images/marker-icon-";
    var icon_ext = ".png";

    $("#slider-range").slider({
        range: true,
        min: - 3000,
        max: new Date().getFullYear(),
        values: [ 1800, new Date().getFullYear() ],
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

    if(window.location.pathname == "/" || window.location.pathname == "/app_dev.php" || window.location.pathname == "/app_dev.php/") {
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
        element = $(this);

        setTimeout(function(){
            element.parent().addClass("open");
        }, 0);
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
            $(this).prop("checked", checked);
        });
    });

    $("#check-all").click(function(){
        $(".dropdown-menu-thema > li > label > input").each(function(){
            $(this).trigger("click");
        })
    });

    $(".suggestion-index #form_person_name").keyup(function(){

        $(".suggestion-search-result").html("");

        if($(this).val().length >= 3) {
            var data = {"name": $(this).val()};

            $(".suggestion-search-result").html("<i class='fa fa-cog fa-spin fa-3x fa-fw'></i>");

            $.ajax({
                url: 'get-persons-search',
                type: 'POST',
                cache: false,
                data: data,
                success: function (data) {
                    $(".suggestion-search-result").show().html("");

                    if (!data["personnes"].length) {
                        $(".suggestion-search-result").append("Aucun résultat.");
                    }

                    for (i = 0; i < data["personnes"].length; i++) {
                        $(".suggestion-search-result").append(
                            "<a href='" + data["personnes"][i].suggestionLink + "' class='result'>" +
                            "<div class='img-container'><img src='/public/personne/" + data["personnes"][i].ref + ".jpg'></div>" +
                            data["personnes"][i].nom + "" +
                            "</a>");
                    }

                }
            });
        }
        else{
            $(".suggestion-search-result").hide();
        }
    });

    $("#display-route-panel").click(function(){
        if($(".route-people").hasClass("display")){
            $(".route-people").removeClass("display");
        }
        else{
            $(".route-people").addClass("display");
        }
    });

    $(".route-people .search-people-route").keyup(function(){

        $(".route-people .search-result").html("");

        if($(this).val().length >= 3) {
            var data = {"name": $(this).val()};

            $(".route-people .search-result").html("<i class='fa fa-cog fa-spin fa-3x fa-fw'></i>");

            $.ajax({
                url: '/get-persons-search',
                type: 'POST',
                cache: false,
                data: data,
                success: function (data) {
                    $(".route-people .search-result").html("");

                    if (!data["personnes"].length) {
                        $(".route-people .search-result").append("Aucun résultat.");
                    }

                    for (i = 0; i < data["personnes"].length; i++) {

                        var html = '<a data-id="' + data["personnes"][i].id + '" class="person-search-result" title="' + data["personnes"][i].nom + '">' +
                                '<div class="img-container">' +
                                    '<img src="/public/personne/' + data["personnes"][i].ref + '.jpg">' +
                                '</div>' +
                                '<div class="person-name-route"><span>' +
                                data["personnes"][i].nom +
                                '</span></div>' +
                                '<button class="btn btn-info btn-xs info-button" title="Fiche de la personnalité" data-id="' + data["personnes"][i].id + '"><i class="fa fa-info"></i></button>' +
                                '<button class="btn btn-info btn-xs route-button" title="Parcours de la personnalité" data-id="' + data["personnes"][i].id + '"><i class="fa fa-map-marker"></i></button>' +
                            '</a>';

                        $(".route-people .search-result").append(html);
                    }

                    $(".person-search-result .route-button").each(function(){
                        $(this).click(function(){
                            $(".showbox").slideDown("fast", "linear");
                            $(".circular").slideDown("fast", "linear");
                            sendDataAjaxRoute($(this).attr("data-id"));
                        });
                    });

                    $(".person-search-result .info-button").each(function(){
                        $(this).click(function(){
                            var url = "infos-personnalite/" + $(this).attr("data-id");
                            window.location.replace(url)
                        });
                    });
                }
            });
        }
    });
});

function sendDataAjax(data) {
    $.ajax({
        url: 'get-events',
        type: 'POST',
        cache: false,
        data: data,
        success: function (data) {
            contentString = [];
            setMapOnAll(null);
            if (typeof markerCluster !== 'undefined') {
                markerCluster.clearMarkers();
            }
            marker = [];
            infowindow = [];

            for(var i = 0; i < data["events"].length; i++) {
                contentString[i] = "<div class='marker'>\n\
                                        <a target='_blank' href='" + data["events"][i]["personne"]["wiki"].replace(/'/g, "%27") + "'>\n\
                                            <img width='100px' src='/public/personne/" + data["events"][i]["personne"]["ref"] + ".jpg'>\n\
                                        </a>\n\
                                        <div>\n\
                                            <h4>\n\
                                                <a target='_blank' href='" + data["events"][i]["personne"]["wiki"].replace(/'/g, "%27") + "'>\n\
                                                    " + data["events"][i]["personne"]["nom"] + "\n\
                                                    <img class='small-wiki' src='/public/images/wiki_small.png'>\n\
                                                </a>\n\
                                            </h4>\n\
                                            <div><b>Événement :</b> " + data["events"][i]["intitule"] + "</div>\n\
                                            <div><b>Lieu :</b> " + data["events"][i]["place"]["nom"] + "</div>\n\
                                            <div><b>Année :</b> " + data["events"][i]["year"] + "</div>\n\
                                            <div style='max-width: 200px;'><b>Titres :</b><br> ";

                if(typeof data["events"][i]["thematiques"] !== "undefined") {
                    for (var j = 0; j < data["events"][i]["thematiques"].length; j++) {
                        contentString[i] += data["events"][i]["thematiques"][j];

                        if (j != (data["events"][i]["thematiques"].length - 1)) {
                            contentString[i] += ", ";
                        }
                    }
                }
                else{
                    contentString[i] += "Aucun";
                }

                contentString[i] += "   </div></div>\n\
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
                    icon: icon + data["events"][i]["eventType"]["id"] + icon_ext
                });
                marker[i].id = i;

                var thisMarker = marker[i];
                oms.addMarker(marker[i]);
            }

            $(".showbox").slideUp("fast", "linear");
            $(".circular").slideUp("fast", "linear");
        }
    });
}

function sendDataAjaxRoute(person_id) {
    $.ajax({
        url: 'get-person-route/' + person_id,
        type: 'GET',
        cache: false,
        success: function (data) {

            contentString = [];
            setMapOnAll(null);
            setMapOnAllLines(null);
            if (typeof markerCluster !== 'undefined') {
                markerCluster.clearMarkers();
            }
            marker = [];
            line = [];
            infowindow = [];

            for(var i = 0; i < data["events"].length; i++) {
                contentString[i] = "<div class='marker'>\n\
                                        <a target='_blank' href='" + data["events"][i]["personne"]["wiki"].replace(/'/g, "%27") + "'>\n\
                                            <img width='100px' src='/public/personne/" + data["events"][i]["personne"]["ref"] + ".jpg'>\n\
                                        </a>\n\
                                        <div>\n\
                                            <h4>\n\
                                                <a target='_blank' href='" + data["events"][i]["personne"]["wiki"].replace(/'/g, "%27") + "'>\n\
                                                    " + data["events"][i]["personne"]["nom"] + "\n\
                                                    <img class='small-wiki' src='/public/images/wiki_small.png'>\n\
                                                </a>\n\
                                            </h4>\n\
                                            <div><b>Événement :</b> " + data["events"][i]["intitule"] + "</div>\n\
                                            <div><b>Lieu :</b> " + data["events"][i]["place"]["nom"] + "</div>\n\
                                            <div><b>Année :</b> " + data["events"][i]["year"] + "</div>\n\
                                            <div style='max-width: 200px;'><b>Titres :</b><br> ";

                if(typeof data["events"][i]["thematiques"] !== "undefined") {
                    for (var j = 0; j < data["events"][i]["thematiques"].length; j++) {
                        contentString[i] += data["events"][i]["thematiques"][j];

                        if (j != (data["events"][i]["thematiques"].length - 1)) {
                            contentString[i] += ", ";
                        }
                    }
                }
                else{
                    contentString[i] += "Aucun";
                }

                contentString[i] += "   </div></div>\n\
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
                    icon: icon + data["events"][i]["eventType"]["id"] + icon_ext
                });
                marker[i].id = i;

                var thisMarker = marker[i];
                oms.addMarker(marker[i]);
            }

            lineSymbol = {
                path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
                strokeColor: "#15418A",
                strokeOpacity: 1,
                scale: 2
            };

            for(var i = 0; i < marker.length; i++){

                if(i != (marker.length - 1)) {

                    line[i] = new google.maps.Polyline({
                        path: [
                            new google.maps.LatLng(marker[i].position.lat(), marker[i].position.lng()),
                            new google.maps.LatLng(marker[i+1].position.lat(), marker[i+1].position.lng())
                        ],
                        icons: [{
                            icon: lineSymbol,
                            offset: '100%'
                        }],
                        strokeColor: "#15418A",
                        strokeOpacity :1,
                        strokeWeight: 2
                    });
                    line[i].setMap(map);
                }

            }

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

function setMapOnAllLines(map) {
    for (var i = 0; i < line.length; i++) {
        line[i].setMap(null);
    }
}

function clearMarkers() {
    setMapOnAll(null);
}

function clearLines() {
    setMapOnAllLines(null);
}