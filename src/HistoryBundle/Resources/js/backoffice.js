$(document).ready(function(){
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
                    map.setCenter({lat: location.lat, lng: location.lng});
                    map.setZoom(8);
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
        place = $(this).prev().val();
        var address = place.replace(" ", "+");
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + address + '&key=%20AIzaSyDiisfAuSDNvst54ZUdoNxOtr-pqQt59AU',
            type: 'POST',
            cache: false,
            success: function(data){
                if(typeof data["results"][0] !== "undefined") {
                    var location = data["results"][0]["geometry"]["location"];
                    $(".lat").val(location.lat);
                    $(".lng").val(location.lng);

                    var place = encodeURIComponent($(".localize-place-suggestions").prev().val());
                    var geoHackUrl = "http://tools.wmflabs.org/geohack/geohack.php?language=fr&pagename="+
                        place+
                        "&params="+location.lat+"_N_"+location.lng+"_E";

                    var win = window.open(geoHackUrl, '_blank');
                }
            }
        });
    });
});