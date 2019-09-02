
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="col-sm-12 row">
                            <div class="col-sm-10">Add Work to Work List</div>
                            <div class="col-sm-2">
                                <router-link to="/all-works" class="btn btn-warning">Back</router-link>
                            </div>
                        </div>


                    </div>
                    <div class="card-body container">

                        <p v-if="errors.length" class="text-danger">
                            <b>Please correct the following error(s):</b>
                        </p>
                        <!--        <ul class="">-->
                        <!--            <li class="text-danger" v-for="error in errors">{{ error }}</li>-->
                        <!--        </ul>-->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form v-on:submit.prevent="saveForm()">
                                    <div class="form-group">
                                        <label class="control-label">Work Title</label>
                                        <input type="text" v-model="work.name" class="form-control" placeholder="Enter Work Title" required>
                                        <p class="text-danger" v-if="errors.name">{{errors.name[0]}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Work Location</label>
                                        <input type="text" id="autocomplete" v-model="work.location" class="form-control" placeholder="Enter Location of Work" required>
                                        <p class="text-danger" v-if="errors.location">{{errors.location[0]}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Select Worker For This Work</label>
                                        <select class="form-control" v-model="work.worker" multiple>
                                            <!--                                            <option value="" disabled v-model="selected" >Select Worker For This Work</option>-->
                                            <option v-for="user in users['users']" v-bind:value="user.id">{{user.name}}</option>

                                        </select>

                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                                <!--                                {{work.worker}}-->
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
                work: {
                    worker:[],
                    lat:"",
                    long :""
                },
                errors : [],
                users: [],
                autocomplete: null
            }
        },
        mounted() {
            this.generateAutoComplete();
            var app = this;
            axios.get('/api/v1/all_users')
                .then(function (resp) {
                    console.log(resp.data)
                    app.users = resp.data;
                })
                .catch(function (resp) {
                    app.users =[];
                });
        },
        methods: {
            generateAutoComplete: function(){
                let _this = this;
                this.autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), {types: ['geocode']});
                this.autocomplete.addListener('place_changed', function () {
                    let place = _this.autocomplete.getPlace();
                    _this.work.location = place.formatted_address;
                    _this.work.lat = place.geometry.location.lat();
                    _this.work.long = place.geometry.location.lng();
                    // console.log(place.geometry.location.lat())
                    // console.log(place.geometry.location.lng())
                })
            },
            saveForm() {

                var app = this;
                var work = app.work;
                // console.log(work);
                axios.post('/api/v1/works', work)
                    .then(function (resp) {
                        var response = resp;
                        console.log(response.data)
                        app.$router.push({path: '/all-works'});
                        // console.log(resp.data.error);
                        // if (response.status == 500){
                        //     app.errors = response.error;
                        //     console.log(response.error)
                        // }else {
                        //     app.$router.push({path: '/'});
                        // }


                    })
                    .catch(function (resp) {
                        // console.log(resp.data);
                        alert("Could not create your Employee");
                    });
            }
        }
    }
</script>
