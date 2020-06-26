<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-3 h-100 d-flex flex-column">
                <div class="card mt-1">
                    <div class="card-body">
                        <spinner :spin="users === null">
                            <accounts :users="users" @user-changed="activeUser = props"></accounts>
                        </spinner>
                    </div>
                </div>
                <div class="card mt-1 mb-1 flex-lg-grow-1">
                    <div class="card-body">
                        <spinner :spin="products === null">
                            <products :products="products" :active-user="activeUser"></products>
                        </spinner>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 h-100 bg-success">

            </div>
            <div class="col-lg-3 h-100 bg-warning">

            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios";
    import Spinner from "./components/Spinner"
    import Accounts from "./components/Accounts"
    import Products from "./components/Products"

    import Noty from 'noty';

    const lang = document.documentElement.lang.substr(0, 2);
    moment.locale(lang);

    export default {
        data: function () {
            return {
                products: null,
                users: null,
                events: null,
                questions: null,
                tasks: null,
                settings: null,
                activeUser: null
            }
        },
        components: {
            Spinner,
            Accounts,
            Products
        },
        methods: {},
        mounted() {
            axios.interceptors.response.use(
                response => {
                    return response;
                },
                error => {
                    new Noty({
                        text: this.$t("messages.danger.request_failed") + " (" + error.response.data.status + ": " + error.response.data.detail + ")",
                        theme: 'bootstrap-v4',
                        type: 'error'
                    }).show();

                    console.log("request failed");
                    console.log(error.response.data);
                    return Promise.reject(error);
                }
            );

            axios.get("/api/products").then((response) => {
                this.products = response.data["hydra:member"];
            });

            axios.get("/api/users").then((response) => {
                this.users = response.data["hydra:member"];
            });
        }
    }
</script>

<style>
</style>
