<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><router-link :to="{name: 'AllWorks'}" class="btn btn-success"><b>All Works</b></router-link></div>
                    <div class="card-header">Google Map with Vue Js</div>
                    <div class="card-body">
                        <div id="map" style="height: 500px;width: 100%;display: inline-block;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                map: null,
                auth : window.auth
            }
        },
        methods:{
            generateMap: function(){
                if (auth.length == 0){
                    this.$router.push({path: '/login'});
                }
                let _this = this;
                let _image_path = 'image/user/';
                let userLatLong = [];
                let locations = {
                    chicago: {
                        center: {lat: 23.7439732, lng: 90.3705565},
                        population: 0.1
                    }
                };

                // 23.7439732,90.3705565
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var directionsService = new google.maps.DirectionsService;
                        var directionsDisplay = new google.maps.DirectionsRenderer({
                            suppressInfoWindows: true,
                            suppressMarkers: true
                        });
                        _this.map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: 23.7439732, lng: 90.3705565},
                            zoom: 15
                        });
                        //route /{lat: lat, lng: long}
                        // for (let index = 0 ; index <= _this.userLatLong.length; ++index) {
                        //     let lat = parseFloat(_this.userLatLong[index].lat);
                        //     let long = parseFloat(_this.userLatLong[index].long);
                        //     directionsDisplay.setMap(_this.map);
                        //     directionsService.route({
                        //         origin: {lat: 23.807308, lng: 90.402999},
                        //         destination: {lat: 23.7439732, lng: 90.3705565},
                        //         travelMode: 'DRIVING'
                        //     }, function(response, status) {
                        //         if (status === 'OK') {
                        //             directionsDisplay.setDirections(response);
                        //         } else {
                        //             window.alert('Directions request failed due to ' + status);
                        //         }
                        //     });

                        // }


                        ///end route
                        new google.maps.Marker({
                            position: {lat: 23.7439732, lng: 90.3705565},
                            map: _this.map,
                            title: "Target Location"
                        });

                        for (var city in locations) {
                            // Add the circle for this city to the map.
                            var cityCircle = new google.maps.Circle({
                                strokeColor: '#FF0000',
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: '#FF0000',
                                fillOpacity: 0.35,
                                map: _this.map,
                                center: locations[city].center,
                                radius: Math.sqrt(locations[city].population) * 100
                            });
                        }

                        //api for update users
                        var UserLatLong = {id:_this.auth.id,lat :position.coords.latitude,long: position.coords.longitude}


                        axios.put('/api/v1/users/'+_this.auth.id, UserLatLong).then(function (resp) {
                            // console.log(resp.data);
                        });
                        axios.get('/api/v1/users').then(function (resp) {
                            _this.userLatLong = resp.data.users;
                            for (let index = 0 ; index <= _this.userLatLong.length; ++index) {
                                let lat = parseFloat(_this.userLatLong[index].latitude);
                                let long = parseFloat(_this.userLatLong[index].longitude);
                                let name = _this.userLatLong[index].name;
                                let image = _this.userLatLong[index].avatar;
                                new google.maps.Marker({
                                    position: {lat: parseFloat(lat), lng: parseFloat(long)},
                                    map: _this.map,
                                    icon: image,
                                    title: name
                                });
                                var cityCircle = new google.maps.Circle({
                                    strokeColor: '#1e1aff',
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: '#fa88ff',
                                    fillOpacity: 0.35,
                                    map: _this.map,
                                    center: {lat: lat, lng: long},
                                    radius: 0.1 * 100
                                });

                                directionsDisplay.setMap(_this.map);
                                directionsService.route({
                                    origin: {lat: parseFloat(lat), lng: parseFloat(long)},
                                    destination: {lat: 23.7439732, lng: 90.3705565},
                                    travelMode: 'DRIVING'
                                }, function(response, status) {
                                    if (status === 'OK') {
                                        directionsDisplay.setDirections(response);
                                    } else {
                                        // window.alert('Directions request failed due to ' + status);
                                    }
                                });

                            }

                        });


                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }


            }
        },
        mounted: function () {
            this.generateMap();
        }
    }
</script>
