<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-3 h-100 bg-secondary">
                <accounts class="mt-3" :users="users" :active-user="activeUser"></accounts>
            </div>
            <div class="col-md-6 h-100 bg-success">

            </div>
            <div class="col-md-3 h-100 bg-warning">

            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import Spinner from "./components/Spinner"
    import Accounts from "./components/Accounts"

    const lang = document.documentElement.lang.substr(0, 2);
    moment.locale(lang);

    export default {
        data: function () {
            return {
                loaded: false,
                activeUser: null,
                users: [{id: 12, name: "Cédric", karma: 200}, {id: 13, name: "Florian", karma: 12}, {id: 14, name: "Xenia", karma: 213}]
            }
        },
        components: {
            Spinner,
            Accounts
        },
        methods: {}
    }
</script>

<style>
</style>
