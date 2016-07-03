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
        $("#amount").val(ui.values[ 0 ] + "   " + ui.values[ 1 ]);
        },
        stop: function(event, ui){
            sendDataAjax();
        }
    });
    
    $("#thematique-select").change(function(){
        $("#slider-range").slider("values", [-3000, new Date().getFullYear()]);
        $("#amount").val(-3000 + "   " + new Date().getFullYear());
        sendDataAjax();
    });
    
    $(".era-filter-button").click(function(){
        $(".era-filter-button").each(function(){
            $(this).removeClass("era-filter-button-colored");
        });
        
        $(this).addClass("era-filter-button-colored");
        var from = $(this).attr("from");
        var to = $(this).attr("to");
        
        $("#slider-range").slider("values", [from, to]);
        $("#amount").val(from + "   " + to);
        sendDataAjax();
    });

    $("#amount").val($("#slider-range").slider("values", 0) + "   " + $("#slider-range").slider("values", 1));
    
    // Sets the map on all markers in the array.    
    function setMapOnAll(map) {
        for (var i = 0; i < marker.length; i++) {
            marker[i].setMap(map);
        }
    }
    
    $(".filter-activator").click(function(){
        sendDataAjax();
    });

    // Removes the markers from the map, but keeps them in the array.    
    function sendDataAjax(){
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
                          textColor: 'black',
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
    }

    function clearMarkers() {
        setMapOnAll(null);
    }

    $(".localize-place").click(function(){
        var place = $(".place-name").val();
        var address = place.replace(" ", "+");
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + address + '&key=%20AIzaSyDiisfAuSDNvst54ZUdoNxOtr-pqQt59AU',
                type: 'POST',
                cache: false,
                success: function(data){
                    $(".data_localization").html(syntaxHighlight(data));
                    if(typeof data["results"][0] != "undefined"){
                        var location = data["results"][0]["geometry"]["location"];
                        $(".lat").val(location.lat);
                        $(".lng").val(location.lng);
                    }
                }
        });
    });
    
    function syntaxHighlight(json) {
        if (typeof json != 'string') {
             json = JSON.stringify(json, undefined, 4);
        }
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }
    
    $(".localize-place-suggestions").click(function(){
        var place = $(this).prev().val();
        var address = place.replace(" ", "+");
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + address + '&key=%20AIzaSyDiisfAuSDNvst54ZUdoNxOtr-pqQt59AU',
                type: 'POST',
                cache: false,
                success: function(data){
                var location = data["results"][0]["geometry"]["location"];
                        $(".lat").val(location.lat);
                        $(".lng").val(location.lng);
                }
        });
    });
    
    $(".suggestion-close-button").click(function(){
        if($(".arrow-suggestion").hasClass("fa-angle-double-right")){
            $(".arrow-suggestion").addClass("fa-angle-double-left").removeClass("fa-angle-double-right");
            $(".suggestion-form").addClass("suggestion-form-closed");
        }
        else{
            $(".arrow-suggestion").addClass("fa-angle-double-right").removeClass("fa-angle-double-left");
            $(".suggestion-form").removeClass("suggestion-form-closed");
        }
    });
    
    $(".filters-close-button").click(function(){
        if($(".arrow-filters").hasClass("fa-angle-double-left")){
            $(".arrow-filters").addClass("fa-angle-double-right").removeClass("fa-angle-double-left");
            $(".filters-div").addClass("filters-div-closed");
        }
        else{
            $(".arrow-filters").addClass("fa-angle-double-left").removeClass("fa-angle-double-right");
            $(".filters-div").removeClass("filters-div-closed");
        }
    });
    
    $(".filters-close-button").trigger("click");
    
    if (typeof error == 'undefined' && typeof success == 'undefined') {
        $(".suggestion-close-button").trigger("click");
    }
});