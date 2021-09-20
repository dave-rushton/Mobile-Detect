
//MAP>JS
window.onload = function (t) {
    //LEGACY
    if(document.querySelectorAll('#mapDiv').length > 0){
        lonstr = document.getElementById("mapDiv").getAttribute("data-lon"),
            latstr = document.getElementById("mapDiv").getAttribute("data-lat"),
            maxzom = document.getElementById("mapDiv").getAttribute("data-maxzom"),
            setzom = document.getElementById("mapDiv").getAttribute("data-setzom");
        dataicon = document.getElementById("mapDiv").getAttribute("data-icon");

        lat = latstr.split(",")
        lon = lonstr.split(",")

        if(maxzom == null){
            maxzom = 20
        }
        if(setzom == null){
            setzom = 20
        }

        lataverage = 0;
        lonaverage = 0;

        if(lat.length > 0 && lon.length > 0){
            for(i=0; i < lat.length;i++){
                lataverage += parseFloat(lat[i]);
            }
            for(i=0; i < lon.length;i++){
                lonaverage += parseFloat(lon[i]);
            }
            lataverage = lataverage / lat.length;
            lonaverage = lonaverage / lon.length;
        }
        map = L.map("mapDiv").setView([lataverage,lonaverage], setzom), L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            scrollWheelZoom:false,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: maxzom
        }).addTo(map);

        for(i = 0; i < lat.length; i++){
            if(dataicon != null){
                marker = L.marker([lat[i], lon[i]],{icon: dataicon}).addTo(map);
            }
            else{
                marker = L.marker([lat[i], lon[i]]).addTo(map);
            }
        }

        map.scrollWheelZoom.disable();
        action = 0;
        $(document).keydown(function(event) {
            if(event.keyCode == "17"){
                action = 1;
                map.scrollWheelZoom.enable();
            }
        });
        $(document).keyup(function(event) {
            if(event.keyCode == "17"){
                action = 0;
                map.scrollWheelZoom.disable();
            }
        });
    }

    //MULTIPLE

    if(document.querySelectorAll('.location-map').length > 0){
        mapcount =0;
        map = new Array();
        document.querySelectorAll('.location-map').forEach(function(e){

            lonstr = e.getAttribute("data-lon"),
                latstr = e.getAttribute("data-lat"),
                maxzom = e.getAttribute("data-maxzom"),
                setzom = e.getAttribute("data-setzom"),
                dataicon = e.getAttribute("data-icon");

            lat = latstr.split(",")
            lon = lonstr.split(",")

            if(maxzom == null){
                maxzom = 20
            }
            if(setzom == null){
                setzom = 20
            }
            if(lat == null){
                lat = 0.1
            }
            if(lon == null){
                lon = 0.1
            }

            lataverage = 0;
            lonaverage = 0;

            if(lat.length > 0 && lon.length > 0){
                for(i=0; i < lat.length;i++){
                    lataverage += parseFloat(lat[i]);
                }
                for(i=0; i < lon.length;i++){
                    lonaverage += parseFloat(lon[i]);
                }
                lataverage = lataverage / lat.length;
                lonaverage = lonaverage / lon.length;
            }
            map[mapcount] = L.map(e).setView([lataverage,lonaverage], setzom), L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                scrollWheelZoom:false,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                maxZoom: maxzom
            }).addTo(map[mapcount]);

            for(i = 0; i < lat.length; i++){
                if(dataicon != null){
                    marker = L.marker([lat[i], lon[i]],{icon: dataicon}).addTo(map[mapcount]);
                }
                else{
                    marker = L.marker([lat[i], lon[i]]).addTo(map[mapcount]);
                }
            }

            map[mapcount].scrollWheelZoom.disable();
            action = 0;
            mapcount =+1;
        });
        $(document).keydown(function(event) {
            if(event.keyCode == "17"){
                for(i=0 ; i <= mapcount; i++){
                    action = 1;
                    map[i].scrollWheelZoom.enable();
                }
            }
        });
        $(document).keyup(function(event) {
            if(event.keyCode == "17"){
                for(i=0 ; i <= mapcount; i++){
                    action = 1;
                    map[i].scrollWheelZoom.disable();
                }
            }
        });
    }

    document.getElementById("mapmessage").addEventListener("wheel", mapzoom);
};