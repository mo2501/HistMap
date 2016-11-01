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
