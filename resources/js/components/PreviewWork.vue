<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header col-sm-12">

                        <h4>Work : {{$route.params.workName}}</h4>   <h6>Location : {{$route.params.Location}}</h6>
                        <router-link :to="{name: 'AllWorks'}" class="btn btn-success"><b>All Works</b>
                        </router-link>
                    </div>
                    <div class="card-header">Google Map with Vue Js</div>
                    <!--                    {{$route.params.worker}}-->
                    <!--                    {{$route.params.workId}}-->
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
                auth: window.auth,
                work: {
                    lat: 0,
                    long: 0
                },
                worker: [],
                workId: null
            }
        },

        methods: {
            generateMap: function () {
                // (!auth)?this.$router.push({path: '/'}) :this.$router.push({path: '/all-works'});

                let _this = this;
                _this.workId = _this.$route.params.workId;
                _this.worker = _this.$route.params.worker;


                let data = {};
                data.ids = _this.worker;
                data.workeId = _this.workId;


                axios.get('/api/v1/works/' + data.workeId)
                    .then(function (resp) {
                        _this.work.lat = parseFloat(resp.data.lat)
                        _this.work.long = parseFloat(resp.data.long)

                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                var directionsService = new google.maps.DirectionsService;
                                var directionsDisplay = new google.maps.DirectionsRenderer({
                                    suppressInfoWindows: true,
                                    suppressMarkers: true
                                });

                                _this.map = new google.maps.Map(document.getElementById('map'), {
                                    center: {lat: _this.work.lat, lng: _this.work.long},
                                    zoom: 15
                                });

                                ///end route
                                new google.maps.Marker({
                                    position: {lat: parseFloat(_this.work.lat), lng: parseFloat(_this.work.long)},
                                    // position: {lat: 23.810332, lng: 90.41251809999994},
                                    map: _this.map,
                                    title: "Target Location"
                                });

                                var trafficLayer = new google.maps.TrafficLayer();
                                trafficLayer.setMap(_this.map);

                                console.log(_this.work)
                                var cityCircle = new google.maps.Circle({
                                    strokeColor: '#FF0000',
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: '#FF0000',
                                    fillOpacity: 0.35,
                                    map: _this.map,
                                    center: {lat: _this.work.lat, lng: _this.work.long},
                                    // center: {lat: 23.810332, lng: 90.41251809999994},
                                    radius: .1 * 100
                                });
                                if (data.ids.length !== 0) {
                                    axios.post('/api/v1/worker-for-map', data).then(function (resp) {

                                        _this.userLatLong = resp.data.users;
                                        // console.log(_this.userLatLong)
                                        for (let index = 0; index <= _this.userLatLong.length; ++index) {
                                            let lat = parseFloat(_this.userLatLong[index].latitude);
                                            let long = parseFloat(_this.userLatLong[index].longitude);
                                            let name = _this.userLatLong[index].name;
                                            let image = _this.userLatLong[index].avatar;

                                            //distance calculate function call
                                            let dist = _this.distance(_this.work.lat,_this.work.long,lat,long,'K');


                                            new google.maps.Marker({
                                                position: {lat: parseFloat(lat), lng: parseFloat(long)},
                                                map: _this.map,
                                                icon: image,
                                                title: name +' ('+  dist.toFixed(2) + ' KM From Work)'
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
                                                destination: {lat: _this.work.lat, lng: _this.work.long},
                                                travelMode: 'DRIVING'
                                            }, function (response, status) {
                                                if (status === 'OK') {
                                                    directionsDisplay.setDirections(response);
                                                } else {
                                                    window.alert('Directions request failed due to ' + status);
                                                }
                                            });



                                        }

                                    });
                                }




                            });
                        } else {
                            alert("Geolocation is not supported by this browser.");
                        }



                    })

            },
            distance: function (lat1, lon1, lat2, lon2,unit) {
                if ((lat1 == lat2) && (lon1 == lon2)) {
                    return 0;
                } else {
                    var radlat1 = Math.PI * lat1 / 180;
                    var radlat2 = Math.PI * lat2 / 180;
                    var theta = lon1 - lon2;
                    var radtheta = Math.PI * theta / 180;
                    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
                    if (dist > 1) {
                        dist = 1;
                    }
                    dist = Math.acos(dist);
                    dist = dist * 180 / Math.PI;
                    dist = dist * 60 * 1.1515;
                    if (unit === "K") {
                        dist = dist * 1.609344
                    }
                    if (unit === "N") {
                        dist = dist * 0.8684
                    }
                    return dist;
                    // console.log(dist);
                }
            }
        },
        mounted: function () {
            axios.get('/user-auth-check')
                .then(function (resp) {
                    // console.log(resp.data);
                    this.auth = resp.data;
                })
            this.generateMap();

            if (!this.worker) {
                this.$router.push('/all-works');
            }
        }
    }
</script>
