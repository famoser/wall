<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-3 h-100 d-flex flex-column">
                <div class="card mt-1">
                    <div class="card-body">
                        <spinner :spin="users === null">
                            <accounts :users="users"
                                      @select-user="selectUser"
                                      @post-user="postUser"
                                      @patch-user="patch"
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
                                      @reward="reward"
                                      @post-product="postProduct"
                                      @patch-product="patch"
                                      @delete-product="deleteProduct">
                            </products>
                        </spinner>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 h-100 bg-success">
                <div class="card mt-1 mb-1 mh-100 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="questions === null || answers === null || users === null">
                            <questions :questions="questions"
                                       :answers="answers"
                                       :users="users"
                                       :authorized-user="selectedUser"
                                       @reward="reward"
                                       @post-question="postQuestion"
                                       @patch-question="patch"
                                       @delete-queston="deleteQuestion"
                                       @post-answer="postAnswer"
                                       @patch-answer="patch"
                                       @delete-answer="deleteAnswer">
                            </questions>
                        </spinner>
                    </div>
                </div>
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
    import Questions from "./components/Questions"

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
                answers: null,
                tasks: null,
                settings: null,
                selectedUser: null,
                secret: null
            }
        },
        components: {
            Spinner,
            Accounts,
            Products,
            Questions
        },
        methods: {
            reward: function (reward) {
                let user = this.selectedUser;
                axios.patch(user["@id"] + "/reward", {
                    "karma": reward
                }).then((response) => {
                    Object.assign(user, response.data);
                });
            },
            selectUser: function (user, secret) {
                this.selectedUser = user;
                this.secret = secret
            },
            postUser: function (user) {
                this.post("/api/users", user, this.users);
            },
            deleteUser: function (entity) {
                this.delete(entity, this.users);
            },
            postProduct: function (product) {
                this.post("/api/products", product, this.products);
            },
            deleteProduct: function (entity) {
                this.delete(entity, this.products);
            },
            post: function (url, payload, list) {
                axios.post(url, payload).then((response) => {
                    Object.assign(payload, response.data);
                    list.push(payload);
                });
            },
            patch: function (entity, payload) {
                axios.patch(entity['@id'], payload).then((response) => {
                    Object.assign(entity, response.data);
                });
            },
            delete: function (entity, list) {
                axios.delete(entity['@id']).then(() => {
                    let index = list.indexOf(entity);
                    this.$delete(list, index)
                });
            },
            postQuestion: function (question) {
                this.post("/api/questions", question, this.questions);
            },
            deleteQuestion: function (question) {
                this.delete(question, this.questions);
            },
            postAnswer: function (answer) {
                this.post("/api/answers", answer, this.answers);
            },
            deleteAnswer: function (answer) {
                this.delete(answer, this.answers);
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

            axios.get("/api/questions").then((response) => {
                this.questions = response.data["hydra:member"];
            });

            axios.get("/api/answers").then((response) => {
                this.answers = response.data["hydra:member"];
            });

            axios.get("/api/users").then((response) => {
                this.users = response.data["hydra:member"];
            });
        }
    }
</script>

<style>
</style>
