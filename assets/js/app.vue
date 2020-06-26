<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-3 h-100 d-flex flex-column">
                <div class="card mt-1">
                    <div class="card-body">
                        <spinner :spin="users === null">
                            <accounts :users="users"
                                      @select-user="selectUser"
                                      @add-user="addUser"
                                      @patch-user="patchUser"
                                      @delete-user="deleteUser"
                            ></accounts>
                        </spinner>
                    </div>
                </div>
                <div class="card mt-1 mb-1 flex-lg-grow-1 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="products === null">
                            <products :products="products"
                                      :authorized="authorized"
                                      @add-product="addProduct"
                                      @patch-product="patchProduct"
                                      @delete-product="deleteProduct">
                            </products>
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
</template>W

<script>
    import moment from "moment";
    import axios from "axios";
    import Spinner from "./components/Spinner"
    import Accounts from "./components/Accounts"
    import Products from "./components/Products"

    import Noty from 'noty';

    const lang = document.documentElement.lang.substr(0, 2);
    moment.locale(lang);

    const axiosPatchConfig = {
        headers: {
            'Content-Type': 'application/merge-patch+json'
        }
    }

    export default {
        data: function () {
            return {
                products: null,
                users: null,
                events: null,
                questions: null,
                tasks: null,
                settings: null,
                selectedUser: null,
                secret: null
            }
        },
        components: {
            Spinner,
            Accounts,
            Products
        },
        methods: {
            selectUser: function (user, secret) {
                this.selectedUser = user;
                this.secret = secret
            },
            addUser: function (user) {
                axios.post("/api/users", user).then((response) => {
                    Object.assign(user, response.data);
                    this.users.push(user);
                });
            },
            patchUser: function (id, user) {
                axios.patch("/api/users/" + id, user, axiosPatchConfig).then((response) => {
                    Object.assign(user, response.data);
                });
            },
            deleteUser: function (id) {
                axios.delete("/api/users/" + id).then(() => {
                    let product = this.users.find(p => p.id === id);
                    let index = this.users.indexOf(product);
                    this.$delete(this.users, index)
                });
            },
            addProduct: function (product) {
                axios.post("/api/products", product).then((response) => {
                    Object.assign(product, response.data);
                    this.products.push(product);
                });
            },
            patchProduct: function (id, product) {
                axios.patch("/api/products/" + id, product, axiosPatchConfig).then((response) => {
                    Object.assign(product, response.data);
                });
            },
            deleteProduct: function (id) {
                axios.delete("/api/products/" + id).then(() => {
                    let product = this.products.find(p => p.id === id);
                    let index = this.products.indexOf(product);
                    this.$delete(this.products, index)
                });
            }
        },
        computed: {
            authorized: function () {
                return this.selectedUser !== null;
            }
        },
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
