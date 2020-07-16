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
                                      @reward="reward"
                                      @add-product="addProduct"
                                      @patch-product="patchProduct"
                                      @delete-product="deleteProduct">
                            </products>
                        </spinner>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 h-100 bg-success">
                <div class="card mt-1 mb-1 mh-100 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="questions === null || answers === null">
                            <questions :questions="questions"
                                       :answers="answers"
                                       :authorized-user="selectedUser"
                                       @reward="reward"
                                       @add-question="addQuestion"
                                       @patch-question="patchQuestion"
                                       @delete-queston="deleteQuestion"
                                       @add-answer="addAnswer"
                                       @patch-answer="patchAnswer"
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
                axios.patch("/api/users/" + user.id + "/reward", {
                    "karma": reward
                }).then((response) => {
                    Object.assign(user, response.data);
                });
            },
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
                axios.patch("/api/users/" + id, user).then((response) => {
                    Object.assign(user, response.data);
                });
            },
            deleteUser: function (id) {
                axios.delete("/api/users/" + id).then(() => {
                    let user = this.users.find(p => p.id === id);
                    let index = this.users.indexOf(user);
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
                axios.patch("/api/products/" + id, product).then((response) => {
                    Object.assign(product, response.data);
                });
            },
            deleteProduct: function (id) {
                axios.delete("/api/products/" + id).then(() => {
                    let product = this.products.find(p => p.id === id);
                    let index = this.products.indexOf(product);
                    this.$delete(this.products, index)
                });
            },
            addQuestion: function (question) {
                axios.post("/api/questions", question).then((response) => {
                    Object.assign(question, response.data);
                    this.questions.push(question);
                });
            },
            patchQuestion: function (id, question) {
                axios.patch("/api/questions/" + id, question).then((response) => {
                    Object.assign(question, response.data);
                });
            },
            deleteQuestion: function (id) {
                axios.delete("/api/questions/" + id).then(() => {
                    let question = this.questions.find(p => p.id === id);
                    let index = this.questions.indexOf(question);
                    this.$delete(this.questions, index)
                });
            },
            addAnswer: function (answer) {
                axios.post("/api/answers", answer).then((response) => {
                    Object.assign(answer, response.data);
                    this.answers.push(answer);
                });
            },
            patchAnswer: function (id, answer) {
                axios.patch("/api/answers/" + id, answer).then((response) => {
                    Object.assign(answer, response.data);
                });
            },
            deleteAnswer: function (id) {
                axios.delete("/api/answers/" + id).then(() => {
                    let answer = this.answers.find(p => p.id === id);
                    let index = this.answers.indexOf(answer);
                    this.$delete(this.answers, index)
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
