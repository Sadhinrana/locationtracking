
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="col-sm-12 row">
                            <div class="col-sm-10">My Profile</div>
                            <div class="col-sm-2 text-right">
                                <!--                                <router-link to="/" >Back to Home</router-link>-->
                                <a href="javascript:void(0)" class="btn btn-info" @click="$router.go(-1)">Back</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body container">

                        <div v-if="success" class="alert alert-success" role="alert">
                            {{success}}
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form v-on:submit.prevent="updateForm()">
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input type="text" v-model="user.name" class="form-control" placeholder="Name" required>
                                        <p class="text-danger" v-if="errors.name">{{errors.name[0]}}</p>
                                    </div>
                                    <!--<div class="form-group">
                                        <label class="control-label">My Location</label>
                                        <input type="text" id="autocomplete" v-model="user.location" class="form-control" placeholder="Enter Location" required>
                                        <p class="text-danger" v-if="errors.location">{{errors.location[0]}}</p>
                                    </div>-->

                                    <div id="somecomponent" style="width: 100%; height: 400px;"></div>
                                    <br>

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
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
                userId: null,
                user: {
                    name:"",
                    location:"",
                    lat:"",
                    long :""
                },
                errors : [],
                success: '',
                autocomplete: null
            }
        },

        methods: {
            generateAutoComplete: function(){
                let _this = this;
                this.autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), {types: ['geocode']});
                this.autocomplete.addListener('place_changed', function () {
                    // let place = _this.autocomplete.getPlace();
                    // _this.user.location = place.formatted_address;
                    // _this.user.lat = place.geometry.location.lat();
                    // _this.user.long = place.geometry.location.lng();
                })
            },
            updateForm() {
                var app = this;
                var user = app.user;
                // console.log(user);
                axios.post('/userProfileUpdate', user)
                    .then(function (resp) {
                        var response = resp;
                        console.log(response.data)
                        app.success = response.data.success;
                        setTimeout(function () {
                            $('.alert').hide();
                        }, 3000);
                        app.$router.push({path: '/my-profile'});
                    })
                    .catch(function (resp) {
                        // console.log(resp.data);
                        alert("Could not create your Employee");
                    });
            }
        },

        mounted() {
            this.generateAutoComplete();
            let app = this;

            axios.get('/userProfile')
                .then(function (resp) {
                    app.user = resp.data;
                })
                .catch(function () {
                    alert("Could not load your company")
                });

            setTimeout(function () {
                $('#somecomponent').locationpicker({
                    location: {
                        latitude: app.user.lat,
                        longitude: app.user.long
                    },
                    locationName: "",
                    radius: 500,
                    zoom: 15,
                    onchanged: function(currentLocation, radius, locationName) {
                        console.log(currentLocation);
                        app.user.lat = currentLocation.latitude;
                        app.user.long = currentLocation.longitude;
                        app.user.location = currentLocation.locationName;
                    }

                });
            }, 1000);
        }
    }
</script>
