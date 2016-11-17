$(document).ready(function(){
    $(document).keypress(function(e) {
        if(e.which == 13 && $("#get-person-input").is(":focus")) {
            $("#get-person-submit").trigger("click");
        }
    });

    $("#get-person-submit").click(function(){
        var data = {"name": $("#get-person-input").val()};

        $.ajax({
            url: '/get-persons-search',
            type: 'POST',
            cache: false,
            data: data,
            success: function (data) {
                console.log(data);

                $(".search-results").html("<h4>Résultats de la recherche :</h4>")

                if(!data["personnes"].length){
                    $(".search-results").append("Aucun résultat.");
                }

                for(i=0; i<data["personnes"].length; i++){
                    $(".search-results").append(
                        "<a href='"+data["personnes"][i].link+"' class='person-search-result'>"+
                        "<div class='img-container'><img src='/public/personne/"+data["personnes"][i].ref+".jpg'></div>" +
                        data["personnes"][i].nom+"" +
                        "</a>");
                }

            }
        });
    });

    $('[data-toggle="tooltip"]').tooltip({html: true});


    if(typeof nodesArray !== "undefined") {

        var nodes = new vis.DataSet(nodesArray);

        // create an array with edges
        var edges = new vis.DataSet(edgesArray);

        // create a network
        var container = document.getElementById('mynetwork');

        // provide the data in the vis format
        var data = {
            nodes: nodesArray,
            edges: edgesArray
        };
        var options = {
            autoResize: true,
            height: '100%',
            width: '100%',
            locale: 'fr',
            clickToUse: false,
            nodes: {
                borderWidth: 4,
                size: 45,
                color: {
                    border: '#265bb2',
                    hover: {
                        border: '#f5f5f5',
                    }

                },
                shapeProperties: {
                    useBorderWithImage: true,
                    interpolation: false,
                },
                mass: 1.5,
                shadow: {
                    enabled: true,
                    color: 'rgba(0,0,0,0.9)',
                    size: 5,
                    x: 0,
                    y: 0
                },
            },
            edges: {
                color: '#265bb2',
                hoverWidth: 0,
            },
            interaction: {
                dragNodes: true,
                dragView: true,
                hideEdgesOnDrag: false,
                hideNodesOnDrag: false,
                hover: true,
                hoverConnectedEdges: false,
                keyboard: {
                    enabled: true,
                    speed: {x: 10, y: 10, zoom: 0.02},
                    bindToWindow: true
                },
                selectable: true,
                selectConnectedEdges: false,
                tooltipDelay: 0,
                zoomView: true
            }
        };

        var network = new vis.Network(container, data, options);

        network.on('click', function (properties) {
            if (properties.nodes.length) {
                var key = properties["nodes"][0] - 1;
                console.log(properties);
                window.location.href = nodesLinks[key].url;
            }
        });

        network.on('hoverNode', function (properties) {
            document.body.style.cursor = "pointer";
        });

        network.on('blurNode', function (properties) {
            document.body.style.cursor = "default";
        });
    }
});