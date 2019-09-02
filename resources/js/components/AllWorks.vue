<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'CreateWork'}" class="btn btn-info"><b>Add Work</b></router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Work List</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Workers</th>
                        <th width="100px">Action &nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="work, index in works">
                        <td>{{ work.name }}</td>
                        <td>{{ work.location }}</td>
                        <td>{{ work.worker }}</td>
                        <td>
                            <div class="row">
                                <div class="col-sm-4">
                                    <router-link :to="{name: 'EditWork', params: {id: work.id}}" class="btn btn-xs badge badge-primary">
                                        Edit
                                    </router-link>
                                </div>
                                <div class="col-sm-4">
                                    <router-link :to="{name: 'PreviewWork', params: {workId: work.id,workName :work.name,Location : work.location, worker:work.worker}}" class="btn btn-xs badge badge-info">
                                        Preview
                                    </router-link>
                                </div>
                            </div>


                            <a href="#"
                               class="btn btn-xs badge badge-danger"
                               v-on:click="deleteEntry(work.id, index)">
                                Delete
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                works: [],
                auth : window.auth
            }
        },
        mounted() {
            // (!auth)?this.$router.push({path: '/'}) :this.$router.push({path: '/all-works'});


            axios.get('/user-auth-check')
                .then(function (resp) {
                    // console.log(resp.data);
                    this.auth = resp.data;
                })

            var app = this;
            axios.get('/api/v1/works')
                .then(function (resp) {
                    console.log(resp.data)
                    app.works = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load companies");
                });
        },
        methods: {
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('/api/v1/works/' + id)
                        .then(function (resp) {
                            app.works.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            }
        }
    }
</script>
